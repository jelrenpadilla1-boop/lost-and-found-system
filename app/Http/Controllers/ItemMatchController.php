<?php

namespace App\Http\Controllers;

use App\Models\ItemMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MatchFoundNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this

class ItemMatchController extends Controller
{
    public function index()
    {
        $matches = ItemMatch::with(['lostItem.user', 'foundItem.user'])
            ->orderBy('match_score', 'desc')
            ->paginate(15);
        
        return view('matches.index', compact('matches'));
    }

    public function show(ItemMatch $match)
    {
        return view('matches.show', compact('match'));
    }

    public function confirmMatch(Request $request, ItemMatch $match)
    {
        // Now authorize() method will be available
        
        $match->update(['status' => 'confirmed']);
        
        $match->lostItem->update(['status' => 'found']);
        $match->foundItem->update(['status' => 'claimed']);
        
        $match->lostItem->user->notify(new MatchFoundNotification($match, true));
        $match->foundItem->user->notify(new MatchFoundNotification($match, false));
        
        return back()->with('success', 'Match confirmed successfully!');
    }

    public function rejectMatch(Request $request, ItemMatch $match)
    {
        
        $match->update(['status' => 'rejected']);
              

        return back()->with('success', 'Match rejected successfully!');
    }

    public function myMatches()
    {
        $userId = Auth::id();
        
        $matches = ItemMatch::whereHas('lostItem', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orWhereHas('foundItem', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['lostItem.user', 'foundItem.user'])
            ->orderBy('match_score', 'desc')
            ->paginate(15);
        
        return view('matches.my-matches', compact('matches'));
    }
}