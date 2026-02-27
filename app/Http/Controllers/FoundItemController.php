<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Services\AIMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoundItemController extends Controller
{
    protected $matchingService;

    public function __construct(AIMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function index(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        
        $query = FoundItem::with('user');
        
        // Apply filters based on user role
        if ($isAdmin) {
            // Admins can see all items including pending
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        } else {
            // Regular users only see approved items or their own items
            $query->where(function($q) {
                $q->where('status', 'approved')
                  ->orWhere(function($subQ) {
                      $subQ->where('user_id', Auth::id())
                           ->whereIn('status', ['pending', 'approved', 'claimed', 'returned', 'disposed', 'rejected']);
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
                  ->orWhere('found_location', 'like', "%{$search}%");
            });
        }
        
        // Get paginated results with preserved query string
        $foundItems = $query->latest()->paginate(10)->withQueryString();
        
        // Get pending items for admin
        $pendingItems = collect();
        if ($isAdmin) {
            $pendingItems = FoundItem::with('user')
                ->where('status', 'pending')
                ->latest()
                ->get();
        }
        
        // Calculate stats for the cards
        $totalItems = FoundItem::count();
        $pendingCount = FoundItem::where('status', 'pending')->count();
        $approvedCount = FoundItem::where('status', 'approved')->count();
        $rejectedCount = FoundItem::where('status', 'rejected')->count();
        $claimedCount = FoundItem::where('status', 'claimed')->count();
        $returnedCount = FoundItem::where('status', 'returned')->count();
        $disposedCount = FoundItem::where('status', 'disposed')->count();
        $activeReporters = FoundItem::distinct('user_id')->count('user_id');
        
        return view('found-items.index', compact(
            'foundItems',
            'pendingItems',
            'totalItems',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'claimedCount',
            'returnedCount',
            'disposedCount',
            'activeReporters',
            'isAdmin'
        ));
    }

    public function create()
    {
        return view('found-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_found' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'found_location' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('found-items', 'public');
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
        
        if (!$request->latitude && Auth::user()->latitude) {
            $validated['latitude'] = Auth::user()->latitude;
            $validated['longitude'] = Auth::user()->longitude;
        }

        $foundItem = FoundItem::create($validated);

        // Only trigger AI matching for approved items
        if ($foundItem->status === 'approved') {
            $matches = $this->matchingService->findMatchesForFoundItem($foundItem);
        }

        $message = Auth::user()->isAdmin() 
            ? 'Found item reported successfully!' 
            : 'Found item reported successfully! It will be visible after admin approval.';

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', $message);
    }

    public function show(FoundItem $foundItem)
    {
        $isAdmin = Auth::user()->isAdmin();
        $isOwner = Auth::id() === $foundItem->user_id;
        
        // Check if user can view this item
        if (!$isAdmin && !$isOwner && $foundItem->status === 'pending') {
            abort(403, 'This item is pending approval and not visible to the public.');
        }
        
        if (!$isAdmin && !$isOwner && $foundItem->status === 'rejected') {
            abort(403, 'This item has been rejected and is not visible.');
        }
        
        $matches = $foundItem->matches()->with('lostItem.user')->orderBy('match_score', 'desc')->get();
        return view('found-items.show', compact('foundItem', 'matches', 'isAdmin', 'isOwner'));
    }

    public function edit(FoundItem $foundItem)
    {
        $this->authorize('update', $foundItem);
        return view('found-items.edit', compact('foundItem'));
    }

    public function update(Request $request, FoundItem $foundItem)
    {
        $this->authorize('update', $foundItem);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_found' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'found_location' => 'nullable|string|max:255',
            'status' => 'sometimes|in:pending,approved,rejected,claimed,returned,disposed',
        ]);

        if ($request->hasFile('photo')) {
            if ($foundItem->photo) {
                Storage::disk('public')->delete($foundItem->photo);
            }
            $photoPath = $request->file('photo')->store('found-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $foundItem->update($validated);

        // Re-run matching if relevant fields changed and item is approved
        if ($foundItem->status === 'approved' && $request->hasAny(['item_name', 'description', 'category', 'latitude', 'longitude', 'found_location'])) {
            $this->matchingService->findMatchesForFoundItem($foundItem);
        }

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', 'Found item updated successfully!');
    }

    public function destroy(FoundItem $foundItem)
    {
        $this->authorize('delete', $foundItem);
        
        if ($foundItem->photo) {
            Storage::disk('public')->delete($foundItem->photo);
        }
        
        $foundItem->delete();
        
        return redirect()->route('found-items.index')
            ->with('success', 'Found item deleted successfully!');
    }

    public function myItems()
    {
        $foundItems = Auth::user()->foundItems()->latest()->paginate(10);
        
        // Calculate stats for user's items
        $totalItems = $foundItems->total();
        $pendingCount = Auth::user()->foundItems()->where('status', 'pending')->count();
        $approvedCount = Auth::user()->foundItems()->where('status', 'approved')->count();
        $rejectedCount = Auth::user()->foundItems()->where('status', 'rejected')->count();
        $claimedCount = Auth::user()->foundItems()->where('status', 'claimed')->count();
        $returnedCount = Auth::user()->foundItems()->where('status', 'returned')->count();
        $disposedCount = Auth::user()->foundItems()->where('status', 'disposed')->count();
        
        return view('found-items.my-items', compact(
            'foundItems', 
            'totalItems', 
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'claimedCount', 
            'returnedCount',
            'disposedCount'
        ));
    }

    /**
     * Approve a found item (Admin only)
     */
    public function approve(FoundItem $foundItem)
    {
        $this->authorize('approve', $foundItem);
        
        $foundItem->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        // Trigger AI matching for newly approved item
        $matches = $this->matchingService->findMatchesForFoundItem($foundItem);

        return redirect()->back()
            ->with('success', 'Found item approved successfully! ' . count($matches) . ' potential matches found.');
    }

    /**
     * Reject a found item (Admin only)
     */
    public function reject(Request $request, FoundItem $foundItem)
    {
        $this->authorize('reject', $foundItem);
        
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $foundItem->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $validated['rejection_reason'] ?? null
        ]);

        return redirect()->back()
            ->with('success', 'Found item rejected successfully.');
    }

    /**
     * Bulk approve multiple items (Admin only)
     */
    public function bulkApprove(Request $request)
    {
        $this->authorize('bulkApprove', FoundItem::class);
        
        $validated = $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:found_items,id'
        ]);

        $count = FoundItem::whereIn('id', $validated['item_ids'])
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

        $count = FoundItem::where('status', 'pending')->count();
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