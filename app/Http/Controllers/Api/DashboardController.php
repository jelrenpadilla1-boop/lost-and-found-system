<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics for the authenticated user
     */
    public function stats()
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            
            // ALL items stats (global)
            $totalLostItems = LostItem::count();
            $totalFoundItems = FoundItem::count();
            $pendingLostItems = LostItem::where('status', 'pending')->count();
            $pendingFoundItems = FoundItem::where('status', 'pending')->count();
            $approvedLostItems = LostItem::where('status', 'approved')->count();
            $approvedFoundItems = FoundItem::where('status', 'approved')->count();
            $foundItems = LostItem::where('status', 'found')->count();
            $claimedItems = FoundItem::where('status', 'claimed')->count();
            $returnedItems = LostItem::where('status', 'returned')->count();
            
            // Match stats
            $totalMatches = ItemMatch::count();
            $confirmedMatches = ItemMatch::where('status', 'confirmed')->count();
            $pendingMatches = ItemMatch::where('status', 'pending')->count();
            
            // User's personal stats
            $myLostItems = LostItem::where('user_id', $userId)->count();
            $myFoundItems = FoundItem::where('user_id', $userId)->count();
            
            // User's matches
            $myMatches = ItemMatch::where(function($query) use ($userId) {
                $query->whereHas('lostItem', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('foundItem', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })->count();
            
            // Recovered items
            $myLostRecovered = LostItem::where('user_id', $userId)
                ->whereIn('status', ['found', 'returned'])
                ->count();
            $myFoundClaimed = FoundItem::where('user_id', $userId)
                ->whereIn('status', ['claimed', 'returned'])
                ->count();
            
            // Admin stats
            $totalUsers = User::count();
            $usersThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();
            $itemsThisWeek = LostItem::where('created_at', '>=', now()->subDays(7))->count() + 
                             FoundItem::where('created_at', '>=', now()->subDays(7))->count();
            
            return response()->json([
                // Global item stats
                'total_lost_items' => $totalLostItems,
                'total_found_items' => $totalFoundItems,
                'pending_lost_items' => $pendingLostItems,
                'pending_found_items' => $pendingFoundItems,
                'approved_lost_items' => $approvedLostItems,
                'approved_found_items' => $approvedFoundItems,
                'found_items' => $foundItems,
                'claimed_items' => $claimedItems,
                'returned_items' => $returnedItems,
                
                // Match stats
                'total_matches' => $totalMatches,
                'confirmed_matches' => $confirmedMatches,
                'pending_matches' => $pendingMatches,
                
                // User personal stats
                'my_lost_items' => $myLostItems,
                'my_found_items' => $myFoundItems,
                'my_matches' => $myMatches,
                'my_lost_recovered' => $myLostRecovered,
                'my_found_claimed' => $myFoundClaimed,
                
                // Admin stats
                'total_users' => $totalUsers,
                'users_this_week' => $usersThisWeek,
                'items_this_week' => $itemsThisWeek,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard stats error: ' . $e->getMessage());
            return response()->json([
                'total_lost_items' => 0,
                'total_found_items' => 0,
                'total_matches' => 0,
                'my_lost_items' => 0,
                'my_found_items' => 0,
                'my_matches' => 0,
                'my_lost_recovered' => 0,
                'my_found_claimed' => 0,
                'total_users' => 0,
            ]);
        }
    }

    /**
     * Get recent items for dashboard
     */
    public function recentItems()
    {
        try {
            // Get recent lost items (all, not just user's)
            $lostItems = LostItem::with('user')
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'type' => 'lost',
                        'status' => $item->status,
                        'category' => $item->category,
                        'location' => $item->lost_location,
                        'created_at' => $item->created_at,
                        'photo' => $item->photo,
                        'user_name' => $item->user ? $item->user->name : null,
                    ];
                });
            
            // Get recent found items (all, not just user's)
            $foundItems = FoundItem::with('user')
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'type' => 'found',
                        'status' => $item->status,
                        'category' => $item->category,
                        'location' => $item->found_location,
                        'created_at' => $item->created_at,
                        'photo' => $item->photo,
                        'user_name' => $item->user ? $item->user->name : null,
                    ];
                });
            
            // Combine and sort by created_at
            $recentItems = $lostItems->merge($foundItems)
                ->sortByDesc('created_at')
                ->values()
                ->take(10);
            
            return response()->json($recentItems);
            
        } catch (\Exception $e) {
            \Log::error('Recent items error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get combined dashboard data for the authenticated user
     */
    public function dashboardData()
    {
        try {
            $user = Auth::user();
            
            // Global stats
            $globalStats = [
                'total_lost_items' => LostItem::count(),
                'total_found_items' => FoundItem::count(),
                'total_matches' => ItemMatch::count(),
                'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
                'pending_matches' => ItemMatch::where('status', 'pending')->count(),
                'pending_lost_items' => LostItem::where('status', 'pending')->count(),
                'pending_found_items' => FoundItem::where('status', 'pending')->count(),
                'total_users' => User::count(),
            ];
            
            // Recent items
            $recentLost = LostItem::with('user')
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'category' => $item->category,
                        'status' => $item->status,
                        'type' => 'lost',
                        'created_at' => $item->created_at,
                        'location' => $item->lost_location,
                        'photo' => $item->photo,
                        'user_name' => $item->user ? $item->user->name : null,
                    ];
                });
            
            $recentFound = FoundItem::with('user')
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'category' => $item->category,
                        'status' => $item->status,
                        'type' => 'found',
                        'created_at' => $item->created_at,
                        'location' => $item->found_location,
                        'photo' => $item->photo,
                        'user_name' => $item->user ? $item->user->name : null,
                    ];
                });
            
            $recentItems = $recentLost->concat($recentFound)->sortByDesc('created_at')->take(10)->values();
            
            $response = [
                'success' => true,
                'global_stats' => $globalStats,
                'recent_items' => $recentItems,
            ];
            
            // Admin data
            if ($user->isAdmin()) {
                $response['admin_data'] = [
                    'pending_lost' => LostItem::with('user')
                        ->where('status', 'pending')
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'item_name' => $item->item_name,
                                'category' => $item->category,
                                'user' => $item->user,
                                'created_at' => $item->created_at,
                                'lost_location' => $item->lost_location,
                            ];
                        }),
                    'pending_found' => FoundItem::with('user')
                        ->where('status', 'pending')
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'item_name' => $item->item_name,
                                'category' => $item->category,
                                'user' => $item->user,
                                'created_at' => $item->created_at,
                                'found_location' => $item->found_location,
                            ];
                        }),
                    'pending_matches' => ItemMatch::with(['lostItem.user', 'foundItem.user'])
                        ->where('status', 'pending')
                        ->orderBy('match_score', 'desc')
                        ->take(10)
                        ->get()
                        ->map(function($match) {
                            return [
                                'id' => $match->id,
                                'lost_item' => $match->lostItem,
                                'found_item' => $match->foundItem,
                                'match_score' => $match->match_score,
                                'status' => $match->status,
                                'created_at' => $match->created_at,
                            ];
                        }),
                    'total_users' => User::count(),
                ];
            } else {
                // User stats
                $response['user_stats'] = [
                    'my_lost_items' => LostItem::where('user_id', $user->id)->count(),
                    'my_found_items' => FoundItem::where('user_id', $user->id)->count(),
                    'my_matches' => ItemMatch::whereHas('lostItem', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->orWhereHas('foundItem', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->count(),
                    'my_lost_recovered' => LostItem::where('user_id', $user->id)
                        ->whereIn('status', ['found', 'returned'])
                        ->count(),
                    'my_found_claimed' => FoundItem::where('user_id', $user->id)
                        ->whereIn('status', ['claimed', 'returned'])
                        ->count(),
                ];
                
                // High matches for user (80% and above)
                $response['high_matches'] = ItemMatch::with(['lostItem.user', 'foundItem.user'])
                    ->where(function($query) use ($user) {
                        $query->whereHas('lostItem', function($q) use ($user) {
                            $q->where('user_id', $user->id);
                        })->orWhereHas('foundItem', function($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                    })
                    ->where('match_score', '>=', 80)
                    ->orderBy('match_score', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function($match) {
                        return [
                            'id' => $match->id,
                            'lost_item' => $match->lostItem,
                            'found_item' => $match->foundItem,
                            'match_score' => $match->match_score,
                            'status' => $match->status,
                            'created_at' => $match->created_at,
                        ];
                    });
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get map items (items with coordinates)
     */
    public function mapItems()
    {
        try {
            // Get lost items with coordinates
            $lostItems = LostItem::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('status', 'approved')
                ->select('id', 'item_name', 'description', 'category', 'latitude', 'longitude', 'lost_location as location', 'photo', 'created_at')
                ->get()
                ->map(function($item) {
                    $item->type = 'lost';
                    return $item;
                });
            
            // Get found items with coordinates
            $foundItems = FoundItem::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('status', 'approved')
                ->select('id', 'item_name', 'description', 'category', 'latitude', 'longitude', 'found_location as location', 'photo', 'created_at')
                ->get()
                ->map(function($item) {
                    $item->type = 'found';
                    return $item;
                });
            
            return response()->json([
                'success' => true,
                'lost' => $lostItems,
                'found' => $foundItems,
                'total' => $lostItems->count() + $foundItems->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}