<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemMatch;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Notifications\MatchFoundNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ItemMatchController extends Controller
{
    /**
     * Display a listing of matches (with filters)
     */
    public function index(Request $request)
    {
        try {
            $isAdmin = Auth::user()->isAdmin();
            $userId = Auth::id();
            
            $query = ItemMatch::with(['lostItem.user', 'foundItem.user']);
            
            // Non-admins can see:
            // 1. Matches where their items are involved (regardless of status)
            // 2. Matches where both items are approved (public matches)
            if (!$isAdmin) {
                $query->where(function($q) use ($userId) {
                    // User's own items (any status)
                    $q->whereHas('lostItem', function($subQ) use ($userId) {
                        $subQ->where('user_id', $userId);
                    })->orWhereHas('foundItem', function($subQ) use ($userId) {
                        $subQ->where('user_id', $userId);
                    })
                    // OR public matches (both items approved)
                    ->orWhere(function($publicQ) {
                        $publicQ->whereHas('lostItem', function($itemQ) {
                            $itemQ->where('status', 'approved');
                        })->whereHas('foundItem', function($itemQ) {
                            $itemQ->where('status', 'approved');
                        });
                    });
                });
            }
            
            // Apply status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Apply score range filters
            if ($request->filled('min_score')) {
                $query->where('match_score', '>=', $request->min_score);
            }
            
            if ($request->filled('max_score')) {
                $query->where('match_score', '<=', $request->max_score);
            }
            
            // Apply search filter (search by item names)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('lostItem', function($subQ) use ($search) {
                        $subQ->where('item_name', 'like', "%{$search}%");
                    })->orWhereHas('foundItem', function($subQ) use ($search) {
                        $subQ->where('item_name', 'like', "%{$search}%");
                    });
                });
            }
            
            // Get paginated results
            $perPage = $request->get('per_page', 15);
            $matches = $query->orderBy('match_score', 'desc')
                            ->paginate($perPage);
            
            // Calculate stats from the database (unfiltered, like web controller)
            $stats = [
                'total' => ItemMatch::count(),
                'pending' => ItemMatch::where('status', 'pending')->count(),
                'confirmed' => ItemMatch::where('status', 'confirmed')->count(),
                'rejected' => ItemMatch::where('status', 'rejected')->count(),
                'recovered' => ItemMatch::where('status', 'confirmed')->count(), // Recovered = confirmed matches
            ];
            
            return response()->json([
                'success' => true,
                'data' => $matches->items(),
                'pagination' => [
                    'current_page' => $matches->currentPage(),
                    'last_page' => $matches->lastPage(),
                    'per_page' => $matches->perPage(),
                    'total' => $matches->total(),
                ],
                'stats' => $stats,
                'is_admin' => $isAdmin
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching matches: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch matches',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified match
     */
    public function show(ItemMatch $match)
    {
        try {
            $isAdmin = Auth::user()->isAdmin();
            $userId = Auth::id();
            
            // Check if user can view this match
            $canView = $isAdmin || 
                       ($match->lostItem && $match->lostItem->user_id === $userId) || 
                       ($match->foundItem && $match->foundItem->user_id === $userId) ||
                       ($match->lostItem && $match->lostItem->status === 'approved' && 
                        $match->foundItem && $match->foundItem->status === 'approved');
            
            if (!$canView) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this match'
                ], 403);
            }
            
            $match->load(['lostItem.user', 'foundItem.user']);
            
            return response()->json([
                'success' => true,
                'data' => $match,
                'is_admin' => $isAdmin,
                'is_owner_lost' => $match->lostItem && $match->lostItem->user_id === $userId,
                'is_owner_found' => $match->foundItem && $match->foundItem->user_id === $userId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch match',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Confirm a match (mark as confirmed)
     */
    public function confirmMatch(Request $request, ItemMatch $match)
    {
        try {
            $userId = Auth::id();
            
            // Check if user is involved in this match
            $isInvolved = ($match->lostItem && $match->lostItem->user_id === $userId) ||
                          ($match->foundItem && $match->foundItem->user_id === $userId);
            
            if (!$isInvolved && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to confirm this match'
                ], 403);
            }
            
            if ($match->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This match can no longer be confirmed'
                ], 400);
            }
            
            $match->update(['status' => 'confirmed']);
            
            // Update related items (same as web controller)
            if ($match->lostItem) {
                $match->lostItem->update(['status' => 'found']);
            }
            
            if ($match->foundItem) {
                $match->foundItem->update(['status' => 'claimed']);
            }
            
            // Notify both users about the confirmation
            if ($match->lostItem && $match->lostItem->user) {
                Log::info('API: Sending match confirmation notification to lost item owner: ' . $match->lostItem->user->id);
                $match->lostItem->user->notify(new MatchFoundNotification($match, true));
            }
            
            if ($match->foundItem && $match->foundItem->user) {
                Log::info('API: Sending match confirmation notification to found item owner: ' . $match->foundItem->user->id);
                $match->foundItem->user->notify(new MatchFoundNotification($match, false));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Match confirmed successfully!',
                'data' => $match->load(['lostItem.user', 'foundItem.user'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Confirm match error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm match: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject a match
     */
    public function rejectMatch(Request $request, ItemMatch $match)
    {
        try {
            $userId = Auth::id();
            
            // Check if user is involved in this match
            $isInvolved = ($match->lostItem && $match->lostItem->user_id === $userId) ||
                          ($match->foundItem && $match->foundItem->user_id === $userId);
            
            if (!$isInvolved && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to reject this match'
                ], 403);
            }
            
            if ($match->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This match can no longer be rejected'
                ], 400);
            }
            
            $match->update(['status' => 'rejected']);
            
            return response()->json([
                'success' => true,
                'message' => 'Match rejected successfully!',
                'data' => $match->load(['lostItem.user', 'foundItem.user'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Reject match error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject match: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create a new match (called by your matching algorithm)
     */
    public function createMatch(Request $request)
    {
        try {
            $request->validate([
                'lost_item_id' => 'required|exists:lost_items,id',
                'found_item_id' => 'required|exists:found_items,id',
                'match_score' => 'required|numeric|min:0|max:100'
            ]);
            
            // Check if match already exists
            $existingMatch = ItemMatch::where('lost_item_id', $request->lost_item_id)
                ->where('found_item_id', $request->found_item_id)
                ->first();
                
            if ($existingMatch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Match already exists',
                    'data' => $existingMatch
                ], 409);
            }
            
            // Create the match
            $match = ItemMatch::create([
                'lost_item_id' => $request->lost_item_id,
                'found_item_id' => $request->found_item_id,
                'match_score' => $request->match_score,
                'status' => 'pending'
            ]);
            
            // Load relationships
            $match->load(['lostItem.user', 'foundItem.user']);
            
            // Notify both users about the new match
            if ($match->lostItem && $match->lostItem->user) {
                Log::info('API: Sending new match notification to lost item owner: ' . $match->lostItem->user->id);
                $match->lostItem->user->notify(new MatchFoundNotification($match, true));
            }
            
            if ($match->foundItem && $match->foundItem->user) {
                Log::info('API: Sending new match notification to found item owner: ' . $match->foundItem->user->id);
                $match->foundItem->user->notify(new MatchFoundNotification($match, false));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Match created and users notified',
                'data' => $match
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Create match error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create match: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get matches for the authenticated user (matches where user's items are involved)
     * This matches the web controller's myMatches() method
     */
    public function myMatches(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // Base query for user's matches (same as web controller)
            $query = ItemMatch::where(function ($query) use ($userId) {
                    $query->whereHas('lostItem', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        })
                        ->orWhereHas('foundItem', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        });
                })
                ->with(['lostItem.user', 'foundItem.user']);
            
            // Apply status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Apply score range filters
            if ($request->filled('min_score')) {
                $query->where('match_score', '>=', $request->min_score);
            }
            
            if ($request->filled('max_score')) {
                $query->where('match_score', '<=', $request->max_score);
            }
            
            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('lostItem', function($subQ) use ($search) {
                        $subQ->where('item_name', 'like', "%{$search}%");
                    })->orWhereHas('foundItem', function($subQ) use ($search) {
                        $subQ->where('item_name', 'like', "%{$search}%");
                    });
                });
            }
            
            // Apply recovered filter (for the "Recovered" stats card)
            if ($request->filled('recovered') && $request->recovered == 'true') {
                $query->where('status', 'confirmed')
                    ->where(function ($q) use ($userId) {
                        $q->whereHas('lostItem', function ($subQ) use ($userId) {
                            $subQ->where('user_id', $userId)
                                 ->whereIn('status', ['found', 'returned']);
                        })->orWhereHas('foundItem', function ($subQ) use ($userId) {
                            $subQ->where('user_id', $userId)
                                 ->whereIn('status', ['claimed', 'returned']);
                        });
                    });
            }
            
            // Apply item type filter (my lost items only or my found items only)
            if ($request->filled('type')) {
                if ($request->type == 'lost') {
                    $query->whereHas('lostItem', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
                } elseif ($request->type == 'found') {
                    $query->whereHas('foundItem', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
                }
            }
            
            // Get paginated results
            $perPage = $request->get('per_page', 15);
            $matches = $query->orderBy('match_score', 'desc')
                            ->paginate($perPage);
            
            // Stats for user (using the same buildStatsForUser method as web)
            $stats = $this->buildStatsForUser($userId);
            
            return response()->json([
                'success' => true,
                'data' => $matches->items(),
                'pagination' => [
                    'current_page' => $matches->currentPage(),
                    'last_page' => $matches->lastPage(),
                    'per_page' => $matches->perPage(),
                    'total' => $matches->total(),
                ],
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching my matches: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch your matches',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get statistics for the current user's matches (for API or dashboard)
     * Matches the web controller's getMyMatchStats() method
     */
    public function getMyMatchStats()
    {
        try {
            $userId = Auth::id();
            $stats = $this->buildStatsForUser($userId);
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching match stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch match statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Build match stats for a given user.
     * Extracted to avoid duplicating the same queries (matches web controller)
     */
    private function buildStatsForUser(int $userId): array
    {
        // Base scope: matches that belong to this user (lost OR found side)
        $base = function () use ($userId) {
            return ItemMatch::where(function ($q) use ($userId) {
                $q->whereHas('lostItem', fn ($q) => $q->where('user_id', $userId))
                  ->orWhereHas('foundItem', fn ($q) => $q->where('user_id', $userId));
            });
        };
        
        // Recovered items count (same logic as web controller)
        $recovered = ItemMatch::where('status', 'confirmed')
            ->where(function ($q) use ($userId) {
                $q->whereHas('lostItem', function ($subQ) use ($userId) {
                    $subQ->where('user_id', $userId)
                         ->whereIn('status', ['found', 'returned']);
                })->orWhereHas('foundItem', function ($subQ) use ($userId) {
                    $subQ->where('user_id', $userId)
                         ->whereIn('status', ['claimed', 'returned']);
                });
            })->count();
        
        return [
            'total' => $base()->count(),
            'pending' => $base()->where('status', 'pending')->count(),
            'confirmed' => $base()->where('status', 'confirmed')->count(),
            'rejected' => $base()->where('status', 'rejected')->count(),
            'recovered' => $recovered,
        ];
    }
    
    /**
     * Get pending matches count for admin
     */
    public function getPendingCount()
    {
        try {
            if (!Auth::user()->isAdmin()) {
                return response()->json(['success' => true, 'count' => 0]);
            }
            
            $count = ItemMatch::where('status', 'pending')->count();
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk update matches (Admin only)
     * Matches the web controller's bulkUpdate() method
     */
    public function bulkUpdate(Request $request)
    {
        try {
            if (!Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }
            
            $request->validate([
                'match_ids' => 'required|array',
                'match_ids.*' => 'exists:item_matches,id',
                'status' => 'required|in:confirmed,rejected',
            ]);
            
            $count = ItemMatch::whereIn('id', $request->match_ids)
                ->where('status', 'pending')
                ->update(['status' => $request->status]);
            
            // Update related items and send notifications for confirmed matches
            if ($request->status === 'confirmed') {
                $matches = ItemMatch::whereIn('id', $request->match_ids)
                    ->with(['lostItem.user', 'foundItem.user'])
                    ->get();
                    
                foreach ($matches as $match) {
                    if ($match->lostItem) {
                        $match->lostItem->update(['status' => 'found']);
                    }
                    if ($match->foundItem) {
                        $match->foundItem->update(['status' => 'claimed']);
                    }
                    
                    // Notify both users about the confirmation
                    if ($match->lostItem && $match->lostItem->user) {
                        Log::info('API Bulk update: Sending match confirmation notification to lost item owner: ' . $match->lostItem->user->id);
                        $match->lostItem->user->notify(new MatchFoundNotification($match, true));
                    }
                    
                    if ($match->foundItem && $match->foundItem->user) {
                        Log::info('API Bulk update: Sending match confirmation notification to found item owner: ' . $match->foundItem->user->id);
                        $match->foundItem->user->notify(new MatchFoundNotification($match, false));
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$count} matches updated successfully",
                'count' => $count
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Bulk update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update matches: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get potential matches for a specific lost item
     */
    public function getMatchesForLostItem($lostItemId)
    {
        try {
            $lostItem = LostItem::findOrFail($lostItemId);
            
            // Check if user can view this item's matches
            if ($lostItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view matches for this item'
                ], 403);
            }
            
            $matches = ItemMatch::where('lost_item_id', $lostItemId)
                ->with(['foundItem.user'])
                ->orderBy('match_score', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $matches,
                'count' => $matches->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch matches for this item',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get potential matches for a specific found item
     */
    public function getMatchesForFoundItem($foundItemId)
    {
        try {
            $foundItem = FoundItem::findOrFail($foundItemId);
            
            // Check if user can view this item's matches
            if ($foundItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view matches for this item'
                ], 403);
            }
            
            $matches = ItemMatch::where('found_item_id', $foundItemId)
                ->with(['lostItem.user'])
                ->orderBy('match_score', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $matches,
                'count' => $matches->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch matches for this item',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a match (Admin only or involved users)
     */
    public function destroy(ItemMatch $match)
    {
        try {
            $userId = Auth::id();
            $isInvolved = ($match->lostItem && $match->lostItem->user_id === $userId) ||
                          ($match->foundItem && $match->foundItem->user_id === $userId);
            
            if (!$isInvolved && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this match'
                ], 403);
            }
            
            $match->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Match deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete match',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}