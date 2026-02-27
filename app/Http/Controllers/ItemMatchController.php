<?php

namespace App\Http\Controllers;

use App\Models\ItemMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MatchFoundNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ItemMatchController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        $userId = Auth::id();
        
        $query = ItemMatch::with(['lostItem.user', 'foundItem.user']);
        
        // FIX: Non-admins can see:
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
        
        // Get paginated results with preserved query string
        $matches = $query->orderBy('match_score', 'desc')
                        ->paginate(15)
                        ->withQueryString();
        
        // Calculate stats from the database (unfiltered)
        $stats = [
            'total' => ItemMatch::count(),
            'pending' => ItemMatch::where('status', 'pending')->count(),
            'confirmed' => ItemMatch::where('status', 'confirmed')->count(),
            'rejected' => ItemMatch::where('status', 'rejected')->count(),
        ];
        
        return view('matches.index', compact('matches', 'stats', 'isAdmin'));
    }

    public function show(ItemMatch $match)
    {
        $isAdmin = Auth::user()->isAdmin();
        $userId = Auth::id();
        
        // Check if user can view this match
        $canView = $isAdmin || 
                   ($match->lostItem && $match->lostItem->user_id === $userId) || 
                   ($match->foundItem && $match->foundItem->user_id === $userId) ||
                   ($match->lostItem && $match->lostItem->status === 'approved' && 
                    $match->foundItem && $match->foundItem->status === 'approved');
        
        if (!$canView) {
            abort(403, 'You are not authorized to view this match.');
        }
        
        $match->load(['lostItem.user', 'foundItem.user']);
        return view('matches.show', compact('match', 'isAdmin'));
    }

    public function confirmMatch(Request $request, ItemMatch $match)
    {
        $this->authorize('confirm', $match);
        
        $match->update(['status' => 'confirmed']);
        
        // Update related items
        if ($match->lostItem) {
            $match->lostItem->update(['status' => 'found']);
        }
        
        if ($match->foundItem) {
            $match->foundItem->update(['status' => 'claimed']);
        }
        
        // TEMPORARILY DISABLE NOTIFICATIONS
        // Notify users
        // if ($match->lostItem && $match->lostItem->user) {
        //     $match->lostItem->user->notify(new MatchFoundNotification($match, true));
        // }
        // 
        // if ($match->foundItem && $match->foundItem->user) {
        //     $match->foundItem->user->notify(new MatchFoundNotification($match, false));
        // }
        
        return back()->with('success', 'Match confirmed successfully!');
    }

    public function rejectMatch(Request $request, ItemMatch $match)
    {
        $this->authorize('reject', $match);
        
        $match->update(['status' => 'rejected']);
        
        return back()->with('success', 'Match rejected successfully!');
    }

    public function myMatches(Request $request)
    {
        $userId = Auth::id();

        // Base query for user's matches
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
        $matches = $query->orderBy('match_score', 'desc')
                        ->paginate(15)
                        ->withQueryString();
        
        // Stats are calculated independently (unaffected by active filters)
        $stats = $this->buildStatsForUser($userId);
        
        return view('matches.my-matches', compact('matches', 'stats'));
    }
    
    /**
     * Get statistics for the current user's matches (for API or dashboard)
     */
    public function getMyMatchStats()
    {
        $userId = Auth::id();
        
        return response()->json($this->buildStatsForUser($userId));
    }

    /**
     * Build match stats for a given user.
     * Extracted to avoid duplicating the same 4 queries in two methods.
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

        // Recovered items count
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
        if (!Auth::user()->isAdmin()) {
            return response()->json(['count' => 0]);
        }

        $count = ItemMatch::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Bulk update matches (Admin only)
     */
    public function bulkUpdate(Request $request)
    {
        $this->authorize('bulkUpdate', ItemMatch::class);
        
        $validated = $request->validate([
            'match_ids' => 'required|array',
            'match_ids.*' => 'exists:item_matches,id',
            'status' => 'required|in:confirmed,rejected',
        ]);

        $count = ItemMatch::whereIn('id', $validated['match_ids'])
            ->where('status', 'pending')
            ->update(['status' => $validated['status']]);

        // Update related items for confirmed matches
        if ($validated['status'] === 'confirmed') {
            $matches = ItemMatch::whereIn('id', $validated['match_ids'])
                ->with(['lostItem', 'foundItem'])
                ->get();

            foreach ($matches as $match) {
                if ($match->lostItem) {
                    $match->lostItem->update(['status' => 'found']);
                }
                if ($match->foundItem) {
                    $match->foundItem->update(['status' => 'claimed']);
                }
            }
        }

        return redirect()->back()
            ->with('success', "{$count} matches updated successfully.");
    }
}