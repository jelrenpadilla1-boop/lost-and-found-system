<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Models\ItemClaim;
use App\Models\Notification;
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
        
        // ─────────────────────────────────────────────────────
        // 1. PENDING ITEMS QUERY (For Admin Review Section)
        // ─────────────────────────────────────────────────────
        $pendingItems = collect();
        
        if ($isAdmin && !$request->filled('status') && !$request->filled('category') && !$request->filled('search')) {
            // Only fetch pending items when NO filters are applied
            $pendingItems = FoundItem::with('user')
                ->where('status', 'pending')
                ->latest()
                ->get();
        }
        
        // ─────────────────────────────────────────────────────
        // 2. MAIN ITEMS QUERY (For the main grid - EXCLUDING PENDING & REJECTED)
        // ─────────────────────────────────────────────────────
        $query = FoundItem::with('user');
        
        if (!$isAdmin) {
            // For regular users: show everything EXCEPT pending and rejected
            $query->whereNotIn('status', ['pending', 'rejected']);
        } else {
            // For admins in the main grid: show ALL EXCEPT pending
            // Pending items have their own separate section
            $query->where('status', '!=', 'pending');
        }
        
        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Apply status filter for main grid (only if user explicitly wants to filter)
        if ($request->filled('status')) {
            if ($isAdmin) {
                $query->where('status', $request->status);
            } else {
                // Regular users cannot filter by pending or rejected
                if (!in_array($request->status, ['pending', 'rejected'])) {
                    $query->where('status', $request->status);
                }
            }
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
        $foundItems = $query->latest()->paginate(12)->withQueryString();
        
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
            $validated['status'] = 'approved';
            $validated['approved_at'] = now();
            $validated['approved_by'] = Auth::id();
        } else {
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

        // Notify the reporter
        try {
            $notifBody = Auth::user()->isAdmin()
                ? "Your found item \"{$foundItem->item_name}\" has been reported and is now active."
                : "Your found item \"{$foundItem->item_name}\" has been submitted and is pending admin approval.";

            Notification::create([
                'user_id' => Auth::id(),
                'type'    => 'found',
                'title'   => '📦 Found Item Reported',
                'body'    => $notifBody,
                'url'     => route('found-items.show', $foundItem),
                'data'    => json_encode(['icon' => 'box', 'color' => '#22d37a']),
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create found item notification: ' . $e->getMessage());
        }

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
        
        $userClaim = ItemClaim::where('found_item_id', $foundItem->id)
            ->where('user_id', Auth::id())
            ->first();
        
        $allClaims = null;
        if ($isOwner || $isAdmin) {
            $allClaims = ItemClaim::where('found_item_id', $foundItem->id)
                ->with('user')
                ->latest()
                ->get();
        }
        
        $matches = $foundItem->matches()->with('lostItem.user')->orderBy('match_score', 'desc')->get();
        
        return view('found-items.show', compact('foundItem', 'matches', 'isAdmin', 'isOwner', 'userClaim', 'allClaims'));
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

    public function approve(FoundItem $foundItem)
    {
        $this->authorize('approve', $foundItem);
        
        $foundItem->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        $matches = $this->matchingService->findMatchesForFoundItem($foundItem);

        return redirect()->back()
            ->with('success', 'Found item approved successfully! ' . count($matches) . ' potential matches found.');
    }

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

    public function getPendingCount()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['count' => 0]);
        }

        $count = FoundItem::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }

    public function submitClaim(Request $request, FoundItem $foundItem)
    {
        $request->validate([
            'claim_reason' => 'required|string|min:10|max:1000',
            'proof_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $existingClaim = ItemClaim::where('found_item_id', $foundItem->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingClaim) {
            return redirect()->back()->with('error', 'You have already submitted a claim for this item.');
        }

        $proofPath = null;
        if ($request->hasFile('proof_photo')) {
            $proofPath = $request->file('proof_photo')->store('claim-proofs', 'public');
        }

        $claim = ItemClaim::create([
            'found_item_id' => $foundItem->id,
            'user_id' => Auth::id(),
            'claim_reason' => $request->claim_reason,
            'proof_photo' => $proofPath,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Your claim has been submitted successfully! The finder will review it.');
    }

    public function approveClaim(Request $request, FoundItem $foundItem, ItemClaim $claim)
    {
        if ($claim->found_item_id !== $foundItem->id) {
            abort(404);
        }

        if (Auth::id() !== $foundItem->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized to approve this claim.');
        }

        $claim->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now()
        ]);

        $foundItem->update([
            'status' => 'claimed'
        ]);

        return redirect()->back()->with('success', 'Claim approved successfully! The user has been notified.');
    }

    public function rejectClaim(Request $request, FoundItem $foundItem, ItemClaim $claim)
    {
        if ($claim->found_item_id !== $foundItem->id) {
            abort(404);
        }

        if (Auth::id() !== $foundItem->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized to reject this claim.');
        }

        $request->validate([
            'admin_notes' => 'required|string|min:10|max:500'
        ]);

        $claim->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now()
        ]);

        return redirect()->back()->with('success', 'Claim rejected successfully.');
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