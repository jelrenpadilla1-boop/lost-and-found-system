<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\Notification;
use App\Services\AIMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostItemController extends Controller
{
    protected $matchingService;

    public function __construct(AIMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Display a listing of lost items.
     * EXCLUDES pending and rejected items from public view (same as API)
     */
    public function index(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        
        // ─────────────────────────────────────────────────────
        // 1. PENDING ITEMS QUERY (For Admin Review Section Only)
        // ─────────────────────────────────────────────────────
        $pendingItems = collect();
        
        if ($isAdmin && !$request->filled('status') && !$request->filled('category') && !$request->filled('search')) {
            $pendingItems = LostItem::with('user')
                ->where('status', 'pending')
                ->latest()
                ->get();
        }
        
        // ─────────────────────────────────────────────────────
        // 2. MAIN ITEMS QUERY - EXCLUDE pending & rejected
        // ─────────────────────────────────────────────────────
        $query = LostItem::with('user');
        
        if (!$isAdmin) {
            // Regular users: Only see approved, found, returned, recovered
            // EXCLUDE: pending and rejected completely
            $query->whereIn('status', ['approved', 'found', 'returned', 'recovered']);
        } else {
            // Admin: See everything EXCEPT pending (shown separately) and rejected
            $query->whereNotIn('status', ['pending', 'rejected']);
        }
        
        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Apply status filter (with restrictions for non-admin)
        if ($request->filled('status')) {
            if ($isAdmin) {
                $query->where('status', $request->status);
            } else {
                // Non-admin can only filter to visible statuses
                $allowedStatuses = ['approved', 'found', 'returned', 'recovered'];
                if (in_array($request->status, $allowedStatuses)) {
                    $query->where('status', $request->status);
                }
                // Ignore filter if trying to see pending/rejected
            }
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('lost_location', 'like', "%{$search}%");
            });
        }
        
        $perPage = $request->get('per_page', 12);
        $lostItems = $query->latest()->paginate($perPage)->withQueryString();
        
        // Calculate statistics based on user role
        if ($isAdmin) {
            $totalItems = LostItem::count();
            $pendingCount = LostItem::where('status', 'pending')->count();
            $approvedCount = LostItem::where('status', 'approved')->count();
            $foundCount = LostItem::where('status', 'found')->count();
            $returnedCount = LostItem::where('status', 'returned')->count();
            $recoveredCount = LostItem::where('status', 'recovered')->count();
            $rejectedCount = LostItem::where('status', 'rejected')->count();
            $activeReporters = LostItem::distinct('user_id')->count('user_id');
        } else {
            // Regular users only see stats for visible items
            $totalItems = LostItem::whereIn('status', ['approved', 'found', 'returned', 'recovered'])->count();
            $pendingCount = 0;
            $approvedCount = LostItem::where('status', 'approved')->count();
            $foundCount = LostItem::where('status', 'found')->count();
            $returnedCount = LostItem::where('status', 'returned')->count();
            $recoveredCount = LostItem::where('status', 'recovered')->count();
            $rejectedCount = 0;
            $activeReporters = LostItem::whereIn('status', ['approved', 'found', 'returned', 'recovered'])
                ->distinct('user_id')
                ->count('user_id');
        }
        
        return view('lost-items.index', compact(
            'lostItems',
            'pendingItems',
            'totalItems',
            'pendingCount',
            'approvedCount',
            'foundCount',
            'returnedCount',
            'recoveredCount',
            'rejectedCount',
            'activeReporters',
            'isAdmin'
        ));
    }

    /**
     * Show the form for creating a new lost item.
     */
    public function create()
    {
        return view('lost-items.create');
    }

    /**
     * Store a newly created lost item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'date_lost' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'lost_location' => 'nullable|string|max:255',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('lost-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['user_id'] = Auth::id();
        
        // Set initial status based on user role
        if (Auth::user()->isAdmin()) {
            // Admin reports are auto-approved
            $validated['status'] = 'approved';
            $validated['approved_at'] = now();
            $validated['approved_by'] = Auth::id();
        } else {
            // Regular user reports need approval
            $validated['status'] = 'pending';
        }
        
        // Use user's location if not provided
        if (!$request->latitude && Auth::user()->latitude) {
            $validated['latitude'] = Auth::user()->latitude;
            $validated['longitude'] = Auth::user()->longitude;
        }

        $lostItem = LostItem::create($validated);

        // ALWAYS trigger AI matching, regardless of status
        $matches = $this->matchingService->findMatchesForLostItem($lostItem);
        
        \Log::info('Matches created for lost item ID ' . $lostItem->id . ': ' . count($matches));

        $message = Auth::user()->isAdmin()
            ? 'Lost item reported successfully!'
            : 'Lost item reported successfully! It will be visible after admin approval.';

        // Notify the reporter
        try {
            $notifBody = Auth::user()->isAdmin()
                ? "Your lost item \"{$lostItem->item_name}\" has been reported and is now active."
                : "Your lost item \"{$lostItem->item_name}\" has been submitted and is pending admin approval.";

            Notification::create([
                'user_id' => Auth::id(),
                'type'    => 'lost',
                'title'   => '🔍 Lost Item Reported',
                'body'    => $notifBody,
                'url'     => route('lost-items.show', $lostItem),
                'data'    => json_encode(['icon' => 'search', 'color' => '#f0b400']),
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create lost item notification: ' . $e->getMessage());
        }

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', $message)
            ->with('matches_found', count($matches) > 0);
    }

    /**
     * Display the specified lost item.
     * Hides pending and rejected items from non-admin/non-owner users
     */
    public function show(LostItem $lostItem)
    {
        $isAdmin = Auth::user()->isAdmin();
        $isOwner = Auth::id() === $lostItem->user_id;
        
        // Hide pending and rejected items from non-owners and non-admins
        $hiddenStatuses = ['pending', 'rejected'];
        
        if (!$isAdmin && !$isOwner && in_array($lostItem->status, $hiddenStatuses)) {
            abort(403, 'This item is not available for public viewing.');
        }
        
        // Load matches with proper relationships
        $matches = $lostItem->matches()
            ->with(['foundItem.user'])
            ->orderBy('match_score', 'desc')
            ->get();
        
        return view('lost-items.show', compact('lostItem', 'matches', 'isAdmin', 'isOwner'));
    }

    /**
     * Show the form for editing the specified lost item.
     */
    public function edit(LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);
        return view('lost-items.edit', compact('lostItem'));
    }

    /**
     * Update the specified lost item.
     */
    public function update(Request $request, LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'date_lost' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'lost_location' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:pending,approved,rejected,found,returned,recovered',
            'remove_photo' => 'sometimes|boolean'
        ]);

        // Handle photo removal if requested
        if ($request->has('remove_photo') && $request->remove_photo == 1) {
            if ($lostItem->photo) {
                Storage::disk('public')->delete($lostItem->photo);
            }
            $validated['photo'] = null;
        }
        
        // Handle new photo upload
        if ($request->hasFile('photo')) {
            if ($lostItem->photo) {
                Storage::disk('public')->delete($lostItem->photo);
            }
            $photoPath = $request->file('photo')->store('lost-items', 'public');
            $validated['photo'] = $photoPath;
        }

        // Only admins can change status
        if (!Auth::user()->isAdmin()) {
            unset($validated['status']);
        } else {
            // Handle status changes with timestamps for admin only
            $oldStatus = $lostItem->status;
            $newStatus = $validated['status'] ?? $lostItem->status;
            
            if ($oldStatus !== $newStatus) {
                switch ($newStatus) {
                    case 'approved':
                        $validated['approved_at'] = now();
                        $validated['approved_by'] = Auth::id();
                        $validated['rejected_at'] = null;
                        $validated['rejected_by'] = null;
                        $validated['rejection_reason'] = null;
                        break;
                    case 'rejected':
                        $validated['rejected_at'] = now();
                        $validated['rejected_by'] = Auth::id();
                        $validated['approved_at'] = null;
                        $validated['approved_by'] = null;
                        break;
                    case 'found':
                        $validated['found_at'] = now();
                        break;
                    case 'returned':
                        $validated['returned_at'] = now();
                        break;
                    case 'recovered':
                        $validated['recovered_at'] = now();
                        break;
                }
            }
        }

        // Update only the fields that are present
        $lostItem->update($validated);

        // Re-run matching if relevant fields changed
        $relevantFields = ['item_name', 'description', 'category', 'latitude', 'longitude', 'lost_location'];
        $hasChanges = false;
        foreach ($relevantFields as $field) {
            if ($request->has($field) && $request->$field != $lostItem->getOriginal($field)) {
                $hasChanges = true;
                break;
            }
        }
        
        if ($hasChanges) {
            $this->matchingService->findMatchesForLostItem($lostItem);
        }

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', 'Lost item updated successfully!');
    }

    /**
     * Remove the specified lost item.
     */
    public function destroy(LostItem $lostItem)
    {
        $this->authorize('delete', $lostItem);
        
        if ($lostItem->photo) {
            Storage::disk('public')->delete($lostItem->photo);
        }
        
        $lostItem->matches()->delete();
        $lostItem->delete();
        
        return redirect()->route('lost-items.index')
            ->with('success', 'Lost item deleted successfully!');
    }

    /**
     * Display the current user's lost items.
     * Users can see ALL their own items regardless of status
     */
    public function myItems()
    {
        $lostItems = Auth::user()->lostItems()->latest()->paginate(10);
        
        $totalItems = $lostItems->total();
        $pendingCount = Auth::user()->lostItems()->where('status', 'pending')->count();
        $approvedCount = Auth::user()->lostItems()->where('status', 'approved')->count();
        $foundCount = Auth::user()->lostItems()->where('status', 'found')->count();
        $returnedCount = Auth::user()->lostItems()->where('status', 'returned')->count();
        $recoveredCount = Auth::user()->lostItems()->where('status', 'recovered')->count();
        $rejectedCount = Auth::user()->lostItems()->where('status', 'rejected')->count();
        
        return view('lost-items.my-items', compact(
            'lostItems', 
            'totalItems', 
            'pendingCount',
            'approvedCount',
            'foundCount', 
            'returnedCount',
            'recoveredCount',
            'rejectedCount'
        ));
    }

    /**
     * Approve a lost item (Admin only)
     */
    public function approve(LostItem $lostItem)
    {
        $this->authorize('approve', $lostItem);
        
        $lostItem->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejected_at' => null,
            'rejected_by' => null,
            'rejection_reason' => null
        ]);

        $matches = $this->matchingService->findMatchesForLostItem($lostItem);

        return redirect()->back()
            ->with('success', 'Lost item approved successfully! ' . count($matches) . ' potential matches found.');
    }

    /**
     * Reject a lost item (Admin only)
     */
    public function reject(Request $request, LostItem $lostItem)
    {
        $this->authorize('reject', $lostItem);
        
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $lostItem->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'approved_at' => null,
            'approved_by' => null
        ]);

        return redirect()->back()
            ->with('success', 'Lost item rejected successfully.');
    }

    /**
     * Bulk approve multiple items (Admin only)
     */
    public function bulkApprove(Request $request)
    {
        $this->authorize('bulkApprove', LostItem::class);
        
        $validated = $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:lost_items,id'
        ]);

        $count = LostItem::whereIn('id', $validated['item_ids'])
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

        $approvedItems = LostItem::whereIn('id', $validated['item_ids'])
            ->where('status', 'approved')
            ->get();
            
        foreach ($approvedItems as $item) {
            $this->matchingService->findMatchesForLostItem($item);
        }

        return redirect()->back()
            ->with('success', "{$count} items approved successfully.");
    }

    /**
     * Get pending items count for admin (AJAX)
     */
    public function getPendingCount()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['count' => 0]);
        }

        $count = LostItem::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Mark a lost item as found (User action)
     */
    public function markAsFound(LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);
        
        if ($lostItem->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Only approved items can be marked as found.');
        }
        
        $lostItem->update([
            'status' => 'found',
            'found_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Item marked as found! We\'ll help connect you with the finder.');
    }

    /**
     * Mark a lost item as recovered (User action)
     */
    public function markAsRecovered(LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);
        
        if ($lostItem->status !== 'found') {
            return redirect()->back()
                ->with('error', 'Item must be marked as found before it can be recovered.');
        }
        
        $lostItem->update([
            'status' => 'recovered',
            'recovered_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Item marked as recovered! Congratulations!');
    }

    /**
     * Mark a lost item as returned (User action)
     */
    public function markAsReturned(LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);
        
        if ($lostItem->status !== 'found') {
            return redirect()->back()
                ->with('error', 'Item must be marked as found before it can be returned.');
        }
        
        $lostItem->update([
            'status' => 'returned',
            'returned_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Item marked as returned! Thank you for completing the process.');
    }

    /**
     * Get address from coordinates using reverse geocoding
     */
    private function getAddressFromCoordinates($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Foundify App/1.0');
            $response = curl_exec($ch);
            curl_close($ch);
            
            $data = json_decode($response, true);
            return $data['display_name'] ?? null;
        } catch (\Exception $e) {
            \Log::error('Reverse geocoding failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Export lost items data (Admin only)
     */
    public function export(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $query = LostItem::with('user');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $items = $query->latest()->get();
        
        $filename = 'lost-items-export-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($items) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, [
                'ID', 'Item Name', 'Category', 'Description', 'Status',
                'Lost Location', 'Date Lost', 'Reported By', 'Reported Date',
                'Approved At', 'Found At', 'Recovered At', 'Returned At'
            ]);
            
            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->item_name,
                    $item->category,
                    $item->description,
                    $item->status,
                    $item->lost_location,
                    $item->date_lost->format('Y-m-d'),
                    $item->user->name ?? 'Unknown',
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->approved_at?->format('Y-m-d H:i:s'),
                    $item->found_at?->format('Y-m-d H:i:s'),
                    $item->recovered_at?->format('Y-m-d H:i:s'),
                    $item->returned_at?->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($handle);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistics for dashboard (AJAX)
     */
    public function getStats()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $stats = [
            'total' => LostItem::count(),
            'pending' => LostItem::where('status', 'pending')->count(),
            'approved' => LostItem::where('status', 'approved')->count(),
            'found' => LostItem::where('status', 'found')->count(),
            'recovered' => LostItem::where('status', 'recovered')->count(),
            'returned' => LostItem::where('status', 'returned')->count(),
            'rejected' => LostItem::where('status', 'rejected')->count(),
            'last_7_days' => LostItem::where('created_at', '>=', now()->subDays(7))->count(),
            'categories' => LostItem::select('category', \DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get()
        ];
        
        return response()->json($stats);
    }
}