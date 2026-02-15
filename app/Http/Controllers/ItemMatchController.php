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
        
        $query = ItemMatch::whereHas('lostItem', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orWhereHas('foundItem', function ($query) use ($userId) {
                $query->where('user_id', $userId);
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
        
        $matches = $query->orderBy('match_score', 'desc')
                        ->paginate(15)
                        ->withQueryString();
        
        return view('matches.my-matches', compact('matches'));
    }
}