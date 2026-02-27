<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
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

    public function index(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        
        $query = LostItem::with('user');
        
        // Apply filters based on user role
        if ($isAdmin) {
            // Admins can see all items
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        } else {
            // Regular users only see approved items or their own items
            $query->where(function($q) {
                $q->where('status', 'approved')
                  ->orWhere(function($subQ) {
                      $subQ->where('user_id', Auth::id())
                           ->whereIn('status', ['pending', 'approved', 'found', 'returned', 'rejected']);
                  });
            });
            
            // Apply status filter for users
            if ($request->filled('status')) {
                if ($request->status == 'pending') {
                    $query->where(function($q) {
                        $q->where('status', 'pending')
                          ->where('user_id', Auth::id());
                    });
                } else {
                    $query->where('status', $request->status);
                }
            }
        }
        
        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
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
        
        // Get paginated results with preserved query string
        $lostItems = $query->latest()->paginate(10)->withQueryString();
        
        // Get pending items for admin
        $pendingItems = collect();
        if ($isAdmin) {
            $pendingItems = LostItem::with('user')
                ->where('status', 'pending')
                ->latest()
                ->get();
        }
        
        // Calculate stats for the cards
        $totalItems = LostItem::count();
        $pendingCount = LostItem::where('status', 'pending')->count();
        $approvedCount = LostItem::where('status', 'approved')->count();
        $foundCount = LostItem::where('status', 'found')->count();
        $returnedCount = LostItem::where('status', 'returned')->count();
        $rejectedCount = LostItem::where('status', 'rejected')->count();
        $activeReporters = LostItem::distinct('user_id')->count('user_id');
        
        return view('lost-items.index', compact(
            'lostItems',
            'pendingItems',
            'totalItems',
            'pendingCount',
            'approvedCount',
            'foundCount',
            'returnedCount',
            'rejectedCount',
            'activeReporters',
            'isAdmin'
        ));
    }

    public function create()
    {
        return view('lost-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        // This creates potential matches even for pending items
        $matches = $this->matchingService->findMatchesForLostItem($lostItem);
        
        // Log the matches for debugging
        \Log::info('Matches created for lost item ID ' . $lostItem->id . ': ' . count($matches));

        $message = Auth::user()->isAdmin() 
            ? 'Lost item reported successfully!' 
            : 'Lost item reported successfully! It will be visible after admin approval.';

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', $message)
            ->with('matches_found', count($matches) > 0);
    }

    public function show(LostItem $lostItem)
    {
        $isAdmin = Auth::user()->isAdmin();
        $isOwner = Auth::id() === $lostItem->user_id;
        
        // Check if user can view this item
        if (!$isAdmin && !$isOwner && $lostItem->status === 'pending') {
            abort(403, 'This item is pending approval and not visible to the public.');
        }
        
        if (!$isAdmin && !$isOwner && $lostItem->status === 'rejected') {
            abort(403, 'This item has been rejected and is not visible.');
        }
        
        // Load matches with proper relationships
        $matches = $lostItem->matches()
            ->with(['foundItem.user'])
            ->orderBy('match_score', 'desc')
            ->get();
        
        return view('lost-items.show', compact('lostItem', 'matches', 'isAdmin', 'isOwner'));
    }

    public function edit(LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);
        return view('lost-items.edit', compact('lostItem'));
    }

    public function update(Request $request, LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_lost' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'lost_location' => 'nullable|string|max:255',
            'status' => 'sometimes|in:pending,approved,rejected,found,returned',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($lostItem->photo) {
                Storage::disk('public')->delete($lostItem->photo);
            }
            $photoPath = $request->file('photo')->store('lost-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $lostItem->update($validated);

        // Re-run matching if relevant fields changed
        if ($request->hasAny(['item_name', 'description', 'category', 'latitude', 'longitude', 'lost_location'])) {
            $this->matchingService->findMatchesForLostItem($lostItem);
        }

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', 'Lost item updated successfully!');
    }

    public function destroy(LostItem $lostItem)
    {
        $this->authorize('delete', $lostItem);
        
        if ($lostItem->photo) {
            Storage::disk('public')->delete($lostItem->photo);
        }
        
        $lostItem->delete();
        
        return redirect()->route('lost-items.index')
            ->with('success', 'Lost item deleted successfully!');
    }

    public function myItems()
    {
        $lostItems = Auth::user()->lostItems()->latest()->paginate(10);
        
        // Calculate stats for user's items
        $totalItems = $lostItems->total();
        $pendingCount = Auth::user()->lostItems()->where('status', 'pending')->count();
        $approvedCount = Auth::user()->lostItems()->where('status', 'approved')->count();
        $foundCount = Auth::user()->lostItems()->where('status', 'found')->count();
        $returnedCount = Auth::user()->lostItems()->where('status', 'returned')->count();
        $rejectedCount = Auth::user()->lostItems()->where('status', 'rejected')->count();
        
        return view('lost-items.my-items', compact(
            'lostItems', 
            'totalItems', 
            'pendingCount',
            'approvedCount',
            'foundCount', 
            'returnedCount',
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
            'approved_by' => Auth::id()
        ]);

        // Trigger AI matching for newly approved item
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
            'rejection_reason' => $validated['rejection_reason'] ?? null
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

        return redirect()->back()
            ->with('success', "{$count} items approved successfully.");
    }

    /**
     * Get pending items count for admin
     */
    public function getPendingCount()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['count' => 0]);
        }

        $count = LostItem::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }

    private function getAddressFromCoordinates($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Your App Name');
            $response = curl_exec($ch);
            curl_close($ch);
            
            $data = json_decode($response, true);
            return $data['display_name'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}