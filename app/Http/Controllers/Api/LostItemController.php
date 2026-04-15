<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Models\Notification;
use App\Services\AIMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LostItemController extends Controller
{
    protected $matchingService;

    public function __construct(AIMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Display a listing of lost items.
     * EXCLUDES pending and rejected items from public view
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
            $lostItems = $query->latest()->paginate($perPage);
            
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
            
            return response()->json([
                'success' => true,
                'data' => $lostItems->items(),
                'pending_items' => $pendingItems,
                'pagination' => [
                    'current_page' => $lostItems->currentPage(),
                    'last_page' => $lostItems->lastPage(),
                    'per_page' => $lostItems->perPage(),
                    'total' => $lostItems->total(),
                ],
                'stats' => [
                    'total' => $totalItems,
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'found' => $foundCount,
                    'returned' => $returnedCount,
                    'recovered' => $recoveredCount,
                    'rejected' => $rejectedCount,
                    'active_reporters' => $activeReporters,
                ],
                'is_admin' => $isAdmin,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lost items index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lost items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created lost item.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'date_lost' => 'required|date',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'lost_location' => 'nullable|string|max:255',
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
                $photoPath = $request->file('photo')->store('lost-items', 'public');
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

            $lostItem = LostItem::create($validated);
            $matches = $this->matchingService->findMatchesForLostItem($lostItem);
            
            Log::info('Matches created for lost item ID ' . $lostItem->id . ': ' . count($matches));

            $message = Auth::user()->isAdmin()
                ? 'Lost item reported successfully!'
                : 'Lost item reported successfully! It will be visible after admin approval.';

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
                Log::error('Failed to create lost item notification: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $lostItem->load('user'),
                'matches_found' => count($matches) > 0,
                'matches_count' => count($matches)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Lost item store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lost item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified lost item.
     * Hides pending and rejected items from non-admin/non-owner users
     */
    public function show($id)
    {
        try {
            $lostItem = LostItem::with('user')->findOrFail($id);
            $isAdmin = Auth::user()->isAdmin();
            $isOwner = Auth::id() === $lostItem->user_id;
            
            // Hide pending and rejected items from non-owners and non-admins
            $hiddenStatuses = ['pending', 'rejected'];
            
            if (!$isAdmin && !$isOwner && in_array($lostItem->status, $hiddenStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This item is not available for public viewing.'
                ], 403);
            }
            
            $matches = $lostItem->matches()
                ->with(['foundItem.user'])
                ->orderBy('match_score', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $lostItem,
                'matches' => $matches,
                'is_admin' => $isAdmin,
                'is_owner' => $isOwner
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Lost item show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lost item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified lost item.
     */
    public function update(Request $request, $id)
    {
        try {
            $lostItem = LostItem::findOrFail($id);
            $this->authorize('update', $lostItem);

            $validator = Validator::make($request->all(), [
                'item_name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'category' => 'sometimes|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'date_lost' => 'sometimes|date',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'lost_location' => 'nullable|string|max:255',
                'status' => 'sometimes|in:pending,approved,rejected,found,returned,recovered',
                'remove_photo' => 'sometimes|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            if ($request->has('remove_photo') && $request->remove_photo == 1) {
                if ($lostItem->photo) {
                    Storage::disk('public')->delete($lostItem->photo);
                }
                $validated['photo'] = null;
            }
            
            if ($request->hasFile('photo')) {
                if ($lostItem->photo) {
                    Storage::disk('public')->delete($lostItem->photo);
                }
                $photoPath = $request->file('photo')->store('lost-items', 'public');
                $validated['photo'] = $photoPath;
            } else {
                unset($validated['photo']);
            }

            $isAdmin = Auth::user()->isAdmin();
            $isOwner = Auth::id() === $lostItem->user_id;
            
            if ($isAdmin) {
                if (isset($validated['status'])) {
                    $oldStatus = $lostItem->status;
                    $newStatus = $validated['status'];
                    
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
            } elseif ($isOwner) {
                if (isset($validated['status']) && $validated['status'] === 'found') {
                    $validated['found_at'] = now();
                } else {
                    unset($validated['status']);
                }
            } else {
                unset($validated['status']);
            }

            $lostItem->update($validated);

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

            return response()->json([
                'success' => true,
                'message' => 'Lost item updated successfully!',
                'data' => $lostItem->load('user')
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this item'
            ], 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Lost item update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lost item: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified lost item.
     */
    public function destroy($id)
    {
        try {
            $lostItem = LostItem::findOrFail($id);
            $this->authorize('delete', $lostItem);
            
            if ($lostItem->photo) {
                Storage::disk('public')->delete($lostItem->photo);
            }
            
            $lostItem->matches()->delete();
            $lostItem->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Lost item deleted successfully!'
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this item'
            ], 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Lost item destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete lost item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the current user's lost items.
     * Users can see ALL their own items regardless of status
     */
    public function myItems(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            // Users can see all their own items (including pending and rejected)
            $lostItems = Auth::user()->lostItems()->latest()->paginate($perPage);
            
            $totalItems = $lostItems->total();
            $pendingCount = Auth::user()->lostItems()->where('status', 'pending')->count();
            $approvedCount = Auth::user()->lostItems()->where('status', 'approved')->count();
            $foundCount = Auth::user()->lostItems()->where('status', 'found')->count();
            $returnedCount = Auth::user()->lostItems()->where('status', 'returned')->count();
            $recoveredCount = Auth::user()->lostItems()->where('status', 'recovered')->count();
            $rejectedCount = Auth::user()->lostItems()->where('status', 'rejected')->count();
            
            return response()->json([
                'success' => true,
                'data' => $lostItems->items(),
                'pagination' => [
                    'current_page' => $lostItems->currentPage(),
                    'last_page' => $lostItems->lastPage(),
                    'per_page' => $lostItems->perPage(),
                    'total' => $lostItems->total(),
                ],
                'stats' => [
                    'total' => $totalItems,
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'found' => $foundCount,
                    'returned' => $returnedCount,
                    'recovered' => $recoveredCount,
                    'rejected' => $rejectedCount,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('My lost items error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch your lost items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a lost item (Admin only)
     */
    public function approve($id)
    {
        try {
            $lostItem = LostItem::findOrFail($id);
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

            return response()->json([
                'success' => true,
                'message' => 'Lost item approved successfully! ' . count($matches) . ' potential matches found.',
                'data' => $lostItem,
                'matches_found' => count($matches) > 0,
                'matches_count' => count($matches)
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Lost item approve error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve lost item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a lost item (Admin only)
     */
    public function reject(Request $request, $id)
    {
        try {
            $lostItem = LostItem::findOrFail($id);
            $this->authorize('reject', $lostItem);
            
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

            $validated = $validator->validated();

            $lostItem->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'rejection_reason' => $validated['rejection_reason'] ?? null,
                'approved_at' => null,
                'approved_by' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lost item rejected successfully.',
                'data' => $lostItem
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Lost item reject error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject lost item',
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
            $this->authorize('bulkApprove', LostItem::class);
            
            $validator = Validator::make($request->all(), [
                'item_ids' => 'required|array',
                'item_ids.*' => 'exists:lost_items,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

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

            return response()->json([
                'success' => true,
                'message' => "{$count} items approved successfully.",
                'count' => $count
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
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
     * Get pending items count for admin (AJAX)
     */
    public function getPendingCount()
    {
        try {
            if (!Auth::user()->isAdmin()) {
                return response()->json(['count' => 0]);
            }

            $count = LostItem::where('status', 'pending')->count();
            return response()->json(['count' => $count]);

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
     * Mark a lost item as found (User action) - DEDICATED ENDPOINT
     */
    public function markAsFound($id)
    {
        try {
            $lostItem = LostItem::findOrFail($id);
            
            if (Auth::id() !== $lostItem->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only the owner can mark this item as found.'
                ], 403);
            }
            
            if ($lostItem->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved items can be marked as found.'
                ], 400);
            }
            
            $lostItem->update([
                'status' => 'found',
                'found_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Item marked as found successfully!',
                'data' => $lostItem
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Mark as found error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark item as found',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a lost item as recovered (User action)
     */
    public function markAsRecovered($id)
    {
        try {
            $lostItem = LostItem::findOrFail($id);
            $this->authorize('update', $lostItem);
            
            if ($lostItem->status !== 'found') {
                return response()->json([
                    'success' => false,
                    'message' => 'Item must be marked as found before it can be recovered.'
                ], 400);
            }
            
            $lostItem->update([
                'status' => 'recovered',
                'recovered_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Item marked as recovered! Congratulations!',
                'data' => $lostItem
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Mark as recovered error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark item as recovered',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a lost item as returned (User action)
     */
    public function markAsReturned($id)
    {
        try {
            $lostItem = LostItem::findOrFail($id);
            $this->authorize('markAsReturned', $lostItem);
            
            if ($lostItem->status !== 'found') {
                return response()->json([
                    'success' => false,
                    'message' => 'Item must be marked as found before it can be returned.'
                ], 400);
            }
            
            $lostItem->update([
                'status' => 'returned',
                'returned_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Item marked as returned! Thank you for completing the process.',
                'data' => $lostItem
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lost item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Mark as returned error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark item as returned',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard (Admin only)
     */
    public function getStats()
    {
        try {
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

        } catch (\Exception $e) {
            Log::error('Get stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}