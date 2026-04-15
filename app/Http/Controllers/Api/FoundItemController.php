<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoundItem;
use App\Models\ItemClaim;
use App\Models\Notification;
use App\Services\AIMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FoundItemController extends Controller
{
    protected $matchingService;

    public function __construct(AIMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Display a listing of found items
     * EXCLUDES pending and rejected items from public view (same as web)
     */
    public function index(Request $request)
    {
        try {
            $isAdmin = Auth::user()->isAdmin();
            
            // ─────────────────────────────────────────────────────
            // 1. PENDING ITEMS QUERY (For Admin Review Section Only)
            // ─────────────────────────────────────────────────────
            $pendingItems = collect();
            
            if ($isAdmin && !$request->filled('status') && !$request->filled('category') && !$request->filled('search')) {
                $pendingItems = FoundItem::with('user')
                    ->where('status', 'pending')
                    ->latest()
                    ->get();
            }
            
            // ─────────────────────────────────────────────────────
            // 2. MAIN ITEMS QUERY - EXCLUDE pending & rejected
            // ─────────────────────────────────────────────────────
            $query = FoundItem::with('user');
            
            if (!$isAdmin) {
                // Regular users: Show everything EXCEPT pending and rejected
                $query->whereNotIn('status', ['pending', 'rejected']);
            } else {
                // Admin: See everything EXCEPT pending (shown separately) and rejected
                $query->where('status', '!=', 'pending');
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
            
            $perPage = $request->get('per_page', 12);
            $foundItems = $query->latest()->paginate($perPage);
            
            // Calculate statistics based on user role
            if ($isAdmin) {
                $totalItems = FoundItem::count();
                $pendingCount = FoundItem::where('status', 'pending')->count();
                $approvedCount = FoundItem::where('status', 'approved')->count();
                $rejectedCount = FoundItem::where('status', 'rejected')->count();
                $claimedCount = FoundItem::where('status', 'claimed')->count();
                $returnedCount = FoundItem::where('status', 'returned')->count();
                $disposedCount = FoundItem::where('status', 'disposed')->count();
                $activeReporters = FoundItem::distinct('user_id')->count('user_id');
            } else {
                // Regular users only see stats for visible items (excluding pending and rejected)
                $totalItems = FoundItem::whereNotIn('status', ['pending', 'rejected'])->count();
                $pendingCount = 0;
                $approvedCount = FoundItem::where('status', 'approved')->count();
                $rejectedCount = 0;
                $claimedCount = FoundItem::where('status', 'claimed')->count();
                $returnedCount = FoundItem::where('status', 'returned')->count();
                $disposedCount = FoundItem::where('status', 'disposed')->count();
                $activeReporters = FoundItem::whereNotIn('status', ['pending', 'rejected'])
                    ->distinct('user_id')
                    ->count('user_id');
            }
            
            return response()->json([
                'success' => true,
                'data' => $foundItems->items(),
                'pending_items' => $pendingItems,
                'pagination' => [
                    'current_page' => $foundItems->currentPage(),
                    'last_page' => $foundItems->lastPage(),
                    'per_page' => $foundItems->perPage(),
                    'total' => $foundItems->total(),
                ],
                'stats' => [
                    'total' => $totalItems,
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'rejected' => $rejectedCount,
                    'claimed' => $claimedCount,
                    'returned' => $returnedCount,
                    'disposed' => $disposedCount,
                    'active_reporters' => $activeReporters,
                ],
                'is_admin' => $isAdmin,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Found items index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch found items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created found item
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'date_found' => 'required|date',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'found_location' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('found-items', 'public');
                $validated['photo'] = $photoPath;
            }

            $validated['user_id'] = Auth::id();
            
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

            $matches = [];
            if ($foundItem->status === 'approved') {
                $matches = $this->matchingService->findMatchesForFoundItem($foundItem);
            }

            $message = Auth::user()->isAdmin()
                ? 'Found item reported successfully!'
                : 'Found item reported successfully! It will be visible after admin approval.';

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
                Log::error('Failed to create found item notification: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $foundItem->load('user'),
                'matches_found' => count($matches)
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Found item store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create found item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified found item
     * Hides pending and rejected items from non-admin/non-owner users
     */
    public function show($id)
    {
        try {
            $foundItem = FoundItem::with('user')->findOrFail($id);
            $isAdmin = Auth::user()->isAdmin();
            $isOwner = Auth::id() === $foundItem->user_id;
            
            // Hide pending and rejected items from non-owners and non-admins
            $hiddenStatuses = ['pending', 'rejected'];
            
            if (!$isAdmin && !$isOwner && in_array($foundItem->status, $hiddenStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This item is not available for public viewing.'
                ], 403);
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
            
            $matches = $foundItem->matches()
                ->with('lostItem.user')
                ->orderBy('match_score', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $foundItem,
                'matches' => $matches,
                'user_claim' => $userClaim,
                'all_claims' => $allClaims,
                'is_admin' => $isAdmin,
                'is_owner' => $isOwner
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Found item show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch found item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified found item
     */
    public function update(Request $request, $id)
    {
        try {
            $foundItem = FoundItem::findOrFail($id);
            
            if ($foundItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this item'
                ], 403);
            }
            
            $validator = Validator::make($request->all(), [
                'item_name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'category' => 'sometimes|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'date_found' => 'sometimes|date',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'found_location' => 'nullable|string|max:255',
                'status' => 'sometimes|in:pending,approved,rejected,claimed,returned,disposed',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $validated = $validator->validated();
            
            if ($request->hasFile('photo')) {
                if ($foundItem->photo) {
                    Storage::disk('public')->delete($foundItem->photo);
                }
                $photoPath = $request->file('photo')->store('found-items', 'public');
                $validated['photo'] = $photoPath;
            }
            
            $isAdmin = Auth::user()->isAdmin();
            $isOwner = Auth::id() === $foundItem->user_id;
            
            if ($isAdmin) {
                // Admin can change any status
            } elseif ($isOwner) {
                if (isset($validated['status']) && $validated['status'] === 'claimed') {
                    // Keep the status change
                } else {
                    unset($validated['status']);
                }
            } else {
                unset($validated['status']);
            }
            
            $foundItem->update($validated);
            
            if ($foundItem->status === 'approved' && $request->hasAny(['item_name', 'description', 'category', 'latitude', 'longitude', 'found_location'])) {
                $this->matchingService->findMatchesForFoundItem($foundItem);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Found item updated successfully!',
                'data' => $foundItem->load('user')
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Found item update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update found item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified found item
     */
    public function destroy($id)
    {
        try {
            $foundItem = FoundItem::findOrFail($id);
            
            if ($foundItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this item'
                ], 403);
            }
            
            if ($foundItem->photo) {
                Storage::disk('public')->delete($foundItem->photo);
            }
            
            $foundItem->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Found item deleted successfully!'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Found item destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete found item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user's found items
     * Users can see ALL their own items regardless of status
     */
    public function myItems(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            // Users can see all their own items (including pending and rejected)
            $foundItems = Auth::user()->foundItems()->latest()->paginate($perPage);
            
            $totalItems = $foundItems->total();
            $pendingCount = Auth::user()->foundItems()->where('status', 'pending')->count();
            $approvedCount = Auth::user()->foundItems()->where('status', 'approved')->count();
            $rejectedCount = Auth::user()->foundItems()->where('status', 'rejected')->count();
            $claimedCount = Auth::user()->foundItems()->where('status', 'claimed')->count();
            $returnedCount = Auth::user()->foundItems()->where('status', 'returned')->count();
            $disposedCount = Auth::user()->foundItems()->where('status', 'disposed')->count();
            
            return response()->json([
                'success' => true,
                'data' => $foundItems->items(),
                'pagination' => [
                    'current_page' => $foundItems->currentPage(),
                    'last_page' => $foundItems->lastPage(),
                    'per_page' => $foundItems->perPage(),
                    'total' => $foundItems->total(),
                ],
                'stats' => [
                    'total' => $totalItems,
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'rejected' => $rejectedCount,
                    'claimed' => $claimedCount,
                    'returned' => $returnedCount,
                    'disposed' => $disposedCount,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('My found items error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch your found items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a found item (Admin only)
     */
    public function approve($id)
    {
        try {
            if (!Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }
            
            $foundItem = FoundItem::findOrFail($id);
            
            $foundItem->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);
            
            $matches = $this->matchingService->findMatchesForFoundItem($foundItem);
            
            return response()->json([
                'success' => true,
                'message' => 'Found item approved successfully! ' . count($matches) . ' potential matches found.',
                'data' => $foundItem,
                'matches_found' => count($matches)
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Found item approve error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve found item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a found item (Admin only)
     */
    public function reject(Request $request, $id)
    {
        try {
            if (!Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }
            
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'nullable|string|max:500'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $foundItem = FoundItem::findOrFail($id);
            
            $foundItem->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason ?? null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Found item rejected successfully.',
                'data' => $foundItem
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Found item reject error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject found item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve multiple items (Admin only)
     */
    public function bulkApprove(Request $request)
    {
        try {
            if (!Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }
            
            $validator = Validator::make($request->all(), [
                'item_ids' => 'required|array',
                'item_ids.*' => 'exists:found_items,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $count = FoundItem::whereIn('id', $request->item_ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => Auth::id()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} items approved successfully.",
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            Log::error('Bulk approve error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk approve items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending items count for admin
     */
    public function getPendingCount()
    {
        try {
            if (!Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => true,
                    'count' => 0
                ]);
            }
            
            $count = FoundItem::where('status', 'pending')->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            Log::error('Pending count error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a found item as claimed (Owner action) - DEDICATED ENDPOINT
     */
    public function markAsClaimed(Request $request, $id)
    {
        try {
            $foundItem = FoundItem::findOrFail($id);
            
            if (Auth::id() !== $foundItem->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only the owner can mark this item as claimed.'
                ], 403);
            }
            
            if ($foundItem->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved items can be marked as claimed.'
                ], 400);
            }
            
            $validated = $request->validate([
                'claim_details' => 'nullable|string|max:1000'
            ]);
            
            $foundItem->update([
                'status' => 'claimed',
                'claimed_at' => now()
            ]);
            
            if (!empty($validated['claim_details'])) {
                Log::info('Claim details for found item ' . $foundItem->id . ': ' . $validated['claim_details']);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Item marked as claimed successfully!',
                'data' => $foundItem->load('user')
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Mark as claimed error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark item as claimed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit a claim for a found item
     */
    public function submitClaim(Request $request, $id)
    {
        try {
            $foundItem = FoundItem::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'claim_reason' => 'required|string|min:10|max:1000',
                'proof_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $existingClaim = ItemClaim::where('found_item_id', $foundItem->id)
                ->where('user_id', Auth::id())
                ->first();
            
            if ($existingClaim) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted a claim for this item.'
                ], 400);
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
            
            return response()->json([
                'success' => true,
                'message' => 'Your claim has been submitted successfully! The finder will review it.',
                'data' => $claim
            ], 201);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Submit claim error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit claim',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a claim (Owner or Admin only)
     */
    public function approveClaim(Request $request, $foundItemId, $claimId)
    {
        try {
            $foundItem = FoundItem::findOrFail($foundItemId);
            $claim = ItemClaim::where('id', $claimId)
                ->where('found_item_id', $foundItemId)
                ->firstOrFail();
            
            if (Auth::id() !== $foundItem->user_id && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to approve this claim.'
                ], 403);
            }
            
            $claim->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now()
            ]);
            
            $foundItem->update([
                'status' => 'claimed'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Claim approved successfully! The user has been notified.',
                'data' => $claim
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item or claim not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Approve claim error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve claim',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a claim (Owner or Admin only)
     */
    public function rejectClaim(Request $request, $foundItemId, $claimId)
    {
        try {
            $foundItem = FoundItem::findOrFail($foundItemId);
            $claim = ItemClaim::where('id', $claimId)
                ->where('found_item_id', $foundItemId)
                ->firstOrFail();
            
            if (Auth::id() !== $foundItem->user_id && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to reject this claim.'
                ], 403);
            }
            
            $validator = Validator::make($request->all(), [
                'admin_notes' => 'required|string|min:10|max:500'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $claim->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Claim rejected successfully.',
                'data' => $claim
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Found item or claim not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Reject claim error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject claim',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}