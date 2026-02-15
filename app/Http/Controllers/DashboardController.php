<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Detailed stats for better insights
        $stats = [
            'lost_items' => [
                'total' => LostItem::count(),
                'pending' => LostItem::where('status', 'pending')->count(),
                'found' => LostItem::where('status', 'found')->count(),
                'returned' => LostItem::where('status', 'returned')->count(),
            ],
            'found_items' => [
                'total' => FoundItem::count(),
                'pending' => FoundItem::where('status', 'pending')->count(),
                'claimed' => FoundItem::where('status', 'claimed')->count(),
                'disposed' => FoundItem::where('status', 'disposed')->count(),
            ],
            'matches' => [
                'total' => ItemMatch::count(),
                'pending' => ItemMatch::where('status', 'pending')->count(),
                'confirmed' => ItemMatch::where('status', 'confirmed')->count(),
                'rejected' => ItemMatch::where('status', 'rejected')->count(),
            ],
        ];
        
        // Simple stats for clickable cards (matching your view)
        $simpleStats = [
            'lost_items' => LostItem::count(),
            'found_items' => FoundItem::count(),
            'total_matches' => ItemMatch::count(),
            'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
        ];
        
        // Get recent items based on user role
        if ($user->isAdmin()) {
            $recentLost = LostItem::with('user')
                ->latest()
                ->take(5)
                ->get();
                
            $recentFound = FoundItem::with('user')
                ->latest()
                ->take(5)
                ->get();
                
            $highMatches = ItemMatch::with(['lostItem.user', 'foundItem.user'])
                ->where('match_score', '>=', 80)
                ->orderBy('match_score', 'desc')
                ->take(5)
                ->get();
        } else {
            $recentLost = $user->lostItems()
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
                
            $recentFound = $user->foundItems()
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
                
            $highMatches = ItemMatch::with(['lostItem.user', 'foundItem.user'])
                ->where(function($query) use ($user) {
                    $query->whereHas('lostItem', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->orWhereHas('foundItem', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                })
                ->where('match_score', '>=', 80)
                ->orderBy('match_score', 'desc')
                ->take(5)
                ->get();
        }
        
        // Get total users for system status
        $totalUsers = User::count();
        
        return view('dashboard', [
            'stats' => $simpleStats, // Using simple stats for the view
            'detailedStats' => $stats, // Available if needed
            'recentLost' => $recentLost,
            'recentFound' => $recentFound,
            'highMatches' => $highMatches,
            'totalUsers' => $totalUsers
        ]);
    }
}