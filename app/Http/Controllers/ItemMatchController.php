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
        $query = ItemMatch::with(['lostItem.user', 'foundItem.user']);
        
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
        
        return view('matches.index', compact('matches'));
    }

    public function show(ItemMatch $match)
    {
        $match->load(['lostItem.user', 'foundItem.user']);
        return view('matches.show', compact('match'));
    }

    public function confirmMatch(Request $request, ItemMatch $match)
    {
        $this->authorize('confirm', $match);
        
        $match->update(['status' => 'confirmed']);
        
        $match->lostItem->update(['status' => 'found']);
        $match->foundItem->update(['status' => 'claimed']);
        
        $match->lostItem->user->notify(new MatchFoundNotification($match, true));
        $match->foundItem->user->notify(new MatchFoundNotification($match, false));
        
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

        // FIX: Wrap the orWhereHas in a grouped where() so subsequent
        // filter clauses are AND'd against the user scope, not OR'd globally.
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
                             ->where('status', 'claimed');
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
        $base = fn () => ItemMatch::where(function ($q) use ($userId) {
            $q->whereHas('lostItem', fn ($q) => $q->where('user_id', $userId))
              ->orWhereHas('foundItem', fn ($q) => $q->where('user_id', $userId));
        });

        return [
            'total' => $base()->count(),

            'pending' => $base()->where('status', 'pending')->count(),

            'confirmed' => $base()->where('status', 'confirmed')->count(),

            'recovered' => ItemMatch::where('status', 'confirmed')
                ->where(function ($q) use ($userId) {
                    $q->whereHas('lostItem', function ($subQ) use ($userId) {
                        $subQ->where('user_id', $userId)
                             ->whereIn('status', ['found', 'returned']);
                    })->orWhereHas('foundItem', function ($subQ) use ($userId) {
                        $subQ->where('user_id', $userId)
                             ->where('status', 'claimed');
                    });
                })->count(),
        ];
    }
}