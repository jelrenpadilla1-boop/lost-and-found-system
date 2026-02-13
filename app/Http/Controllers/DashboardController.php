<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'lost_items' => LostItem::count(),
            'found_items' => FoundItem::count(),
            'total_matches' => ItemMatch::count(),
            'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
        ];
        
        if ($user->isAdmin()) {
            $recentLost = LostItem::with('user')->latest()->take(5)->get();
            $recentFound = FoundItem::with('user')->latest()->take(5)->get();
            $highMatches = ItemMatch::with(['lostItem', 'foundItem'])
                ->where('status', 'pending')
                ->where('match_score', '>=', 80)
                ->orderBy('match_score', 'desc')
                ->take(5)->get();
        } else {
            $recentLost = $user->lostItems()->latest()->take(5)->get();
            $recentFound = $user->foundItems()->latest()->take(5)->get();
            $highMatches = ItemMatch::where(function($query) use ($user) {
                    $query->whereHas('lostItem', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->orWhereHas('foundItem', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                })
                ->where('status', 'pending')
                ->where('match_score', '>=', 80)
                ->orderBy('match_score', 'desc')
                ->take(5)->get();
        }
        
        return view('dashboard', compact('stats', 'recentLost', 'recentFound', 'highMatches'));
    }
}