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

        $detailedStats = [
            "lost_items" => [
                "total"    => LostItem::count(),
                "pending"  => LostItem::where("status", "pending")->count(),
                "found"    => LostItem::where("status", "found")->count(),
                "returned" => LostItem::where("status", "returned")->count(),
            ],
            "found_items" => [
                "total"    => FoundItem::count(),
                "pending"  => FoundItem::where("status", "pending")->count(),
                "claimed"  => FoundItem::where("status", "claimed")->count(),
                "disposed" => FoundItem::where("status", "disposed")->count(),
            ],
            "matches" => [
                "total"     => ItemMatch::count(),
                "pending"   => ItemMatch::where("status", "pending")->count(),
                "confirmed" => ItemMatch::where("status", "confirmed")->count(),
                "rejected"  => ItemMatch::where("status", "rejected")->count(),
            ],
        ];

        $simpleStats = [
            "lost_items"        => LostItem::count(),
            "found_items"       => FoundItem::count(),
            "total_matches"     => ItemMatch::count(),
            "confirmed_matches" => ItemMatch::where("status", "confirmed")->count(),
        ];

        $pendingLost    = [];
        $pendingFound   = [];
        $pendingMatches = [];
        $recentUsers    = [];
        $newUsersCount  = 0;

        if ($user->isAdmin()) {
            $pendingLost = LostItem::with("user")
                ->where("status", "pending")
                ->latest()
                ->take(5)
                ->get();

            $pendingFound = FoundItem::with("user")
                ->where("status", "pending")
                ->latest()
                ->take(5)
                ->get();

            $pendingMatches = ItemMatch::with(["lostItem.user", "foundItem.user"])
                ->where("status", "pending")
                ->orderBy("match_score", "desc")
                ->take(5)
                ->get();
                
            // NEW: Fetch recent users
            $recentUsers = User::latest()
                ->take(5)
                ->get();
                
            // NEW: Count new users this week
            $newUsersCount = User::where('created_at', '>=', now()->subDays(7))->count();
        }

        if ($user->isAdmin()) {
            $recentLost = LostItem::with("user")->latest()->take(5)->get();
            $recentFound = FoundItem::with("user")->latest()->take(5)->get();
            $highMatches = ItemMatch::with(["lostItem.user", "foundItem.user"])
                ->where("match_score", ">=", 80)
                ->orderBy("match_score", "desc")
                ->take(5)
                ->get();
        } else {
            $recentLost = $user->lostItems()->with("user")->latest()->take(5)->get();
            $recentFound = $user->foundItems()->with("user")->latest()->take(5)->get();
            $highMatches = ItemMatch::with(["lostItem.user", "foundItem.user"])
                ->where(function ($query) use ($user) {
                    $query->whereHas("lostItem", fn($q) => $q->where("user_id", $user->id))
                          ->orWhereHas("foundItem", fn($q) => $q->where("user_id", $user->id));
                })
                ->where("match_score", ">=", 80)
                ->orderBy("match_score", "desc")
                ->take(5)
                ->get();
        }

        $totalUsers = User::count();

        return view("dashboard", [
            "stats"          => $simpleStats,
            "detailedStats"  => $detailedStats,
            "pendingLost"    => $pendingLost,
            "pendingFound"   => $pendingFound,
            "pendingMatches" => $pendingMatches,
            "recentLost"     => $recentLost,
            "recentFound"    => $recentFound,
            "highMatches"    => $highMatches,
            "totalUsers"     => $totalUsers,
            // NEW: Pass recent users data to view
            "recentUsers"    => $recentUsers,
            "newUsersCount"  => $newUsersCount,
        ]);
    }

    /**
     * API: Combined dashboard data for mobile app
     */
    public function dashboardData()
    {
        $user = Auth::user();

        $globalStats = [
            'total_lost_items'  => LostItem::count(),
            'total_found_items' => FoundItem::count(),
            'total_matches'     => ItemMatch::count(),
            'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
            'pending_matches'   => ItemMatch::where('status', 'pending')->count(),
            'users_this_week'   => User::where('created_at', '>=', now()->subDays(7))->count(),
            'items_this_week'   => LostItem::where('created_at', '>=', now()->subDays(7))->count()
                                 + FoundItem::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $recentLost = LostItem::with('user')->latest()->take(10)->get()->map(fn($item) => [
            'id'         => $item->id,
            'item_name'  => $item->item_name,
            'category'   => $item->category,
            'status'     => $item->status,
            'type'       => 'lost',
            'created_at' => $item->created_at,
            'location'   => $item->lost_location ?? $item->location ?? null,
            'user_name'  => $item->user?->name,
            'user_id'    => $item->user?->id,
        ]);

        $recentFound = FoundItem::with('user')->latest()->take(10)->get()->map(fn($item) => [
            'id'         => $item->id,
            'item_name'  => $item->item_name,
            'category'   => $item->category,
            'status'     => $item->status,
            'type'       => 'found',
            'created_at' => $item->created_at,
            'location'   => $item->found_location ?? $item->location ?? null,
            'user_name'  => $item->user?->name,
            'user_id'    => $item->user?->id,
        ]);

        $recentItems = $recentLost->concat($recentFound)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        $response = [
            'global_stats' => $globalStats,
            'recent_items' => $recentItems,
        ];

        if ($user->isAdmin()) {
            // NEW: Add recent users to admin_data
            $recentUsers = User::latest()
                ->take(10)
                ->get()
                ->map(fn($u) => [
                    'id'         => $u->id,
                    'name'       => $u->name,
                    'email'      => $u->email,
                    'role'       => $u->isAdmin() ? 'admin' : 'user',
                    'created_at' => $u->created_at,
                    'avatar'     => $u->avatar ?? null,
                ]);
                
            $response['admin_data'] = [
                'pending_lost' => LostItem::with('user')
                    ->where('status', 'pending')
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(fn($item) => [
                        'id'         => $item->id,
                        'item_name'  => $item->item_name,
                        'category'   => $item->category,
                        'status'     => $item->status,
                        'location'   => $item->lost_location ?? $item->location ?? null,
                        'created_at' => $item->created_at,
                        'user'       => $item->user ? [
                            'id'    => $item->user->id,
                            'name'  => $item->user->name,
                            'email' => $item->user->email,
                        ] : null,
                    ]),
                'pending_found' => FoundItem::with('user')
                    ->where('status', 'pending')
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(fn($item) => [
                        'id'         => $item->id,
                        'item_name'  => $item->item_name,
                        'category'   => $item->category,
                        'status'     => $item->status,
                        'location'   => $item->found_location ?? $item->location ?? null,
                        'created_at' => $item->created_at,
                        'user'       => $item->user ? [
                            'id'    => $item->user->id,
                            'name'  => $item->user->name,
                            'email' => $item->user->email,
                        ] : null,
                    ]),
                'pending_matches' => ItemMatch::with(['lostItem.user', 'foundItem.user'])
                    ->where('status', 'pending')
                    ->orderBy('match_score', 'desc')
                    ->take(10)
                    ->get()
                    ->map(fn($match) => [
                        'id'           => $match->id,
                        'match_score'  => $match->match_score,
                        'status'       => $match->status,
                        'lost_item'    => $match->lostItem ? [
                            'id'        => $match->lostItem->id,
                            'item_name' => $match->lostItem->item_name,
                            'user'      => $match->lostItem->user ? [
                                'id'   => $match->lostItem->user->id,
                                'name' => $match->lostItem->user->name,
                            ] : null,
                        ] : null,
                        'found_item'   => $match->foundItem ? [
                            'id'        => $match->foundItem->id,
                            'item_name' => $match->foundItem->item_name,
                            'user'      => $match->foundItem->user ? [
                                'id'   => $match->foundItem->user->id,
                                'name' => $match->foundItem->user->name,
                            ] : null,
                        ] : null,
                    ]),
                'total_users' => User::count(),
                'new_users_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
                // NEW: Add recent users to API response
                'recent_users' => $recentUsers,
            ];
        } else {
            $response['user_stats'] = [
                'my_lost_items'     => LostItem::where('user_id', $user->id)->count(),
                'my_found_items'    => FoundItem::where('user_id', $user->id)->count(),
                'my_matches'        => ItemMatch::where(function ($q) use ($user) {
                    $q->whereHas('lostItem',  fn($q) => $q->where('user_id', $user->id))
                      ->orWhereHas('foundItem', fn($q) => $q->where('user_id', $user->id));
                })->count(),
                'my_lost_recovered' => LostItem::where('user_id', $user->id)->where('status', 'returned')->count(),
                'my_found_claimed'  => FoundItem::where('user_id', $user->id)->where('status', 'claimed')->count(),
            ];

            $response['high_matches'] = ItemMatch::with(['lostItem.user', 'foundItem.user'])
                ->where(function ($q) use ($user) {
                    $q->whereHas('lostItem',  fn($q) => $q->where('user_id', $user->id))
                      ->orWhereHas('foundItem', fn($q) => $q->where('user_id', $user->id));
                })
                ->where('match_score', '>=', 80)
                ->orderBy('match_score', 'desc')
                ->take(10)
                ->get()
                ->map(fn($match) => [
                    'id'          => $match->id,
                    'match_score' => $match->match_score,
                    'status'      => $match->status,
                    'lost_item'   => $match->lostItem ? [
                        'id'        => $match->lostItem->id,
                        'item_name' => $match->lostItem->item_name,
                        'user_id'   => $match->lostItem->user_id,
                        'user'      => $match->lostItem->user ? [
                            'id'   => $match->lostItem->user->id,
                            'name' => $match->lostItem->user->name,
                        ] : null,
                    ] : null,
                    'found_item'  => $match->foundItem ? [
                        'id'        => $match->foundItem->id,
                        'item_name' => $match->foundItem->item_name,
                        'user_id'   => $match->foundItem->user_id,
                        'user'      => $match->foundItem->user ? [
                            'id'   => $match->foundItem->user->id,
                            'name' => $match->foundItem->user->name,
                        ] : null,
                    ] : null,
                ]);

            $response['user_recent_lost'] = $user->lostItems()->latest()->take(5)->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'item_name'  => $item->item_name,
                    'category'   => $item->category,
                    'status'     => $item->status,
                    'location'   => $item->lost_location ?? $item->location ?? null,
                    'created_at' => $item->created_at,
                ]);

            $response['user_recent_found'] = $user->foundItems()->latest()->take(5)->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'item_name'  => $item->item_name,
                    'category'   => $item->category,
                    'status'     => $item->status,
                    'location'   => $item->found_location ?? $item->location ?? null,
                    'created_at' => $item->created_at,
                ]);
        }

        return response()->json($response);
    }

    /**
     * API: Get dashboard statistics
     * FIX: now returns user-specific stats when logged in as a regular user,
     *      and total_users when logged in as admin.
     */
    public function stats()
    {
        $user = Auth::user();

        // Global stats always included
        $response = [
            "lost_items"        => LostItem::count(),
            "found_items"       => FoundItem::count(),
            "total_matches"     => ItemMatch::count(),
            "confirmed_matches" => ItemMatch::where("status", "confirmed")->count(),
            "pending_matches"   => ItemMatch::where("status", "pending")->count(),
            "users_this_week"   => User::where("created_at", ">=", now()->subDays(7))->count(),
            "items_this_week"   => LostItem::where("created_at", ">=", now()->subDays(7))->count()
                                 + FoundItem::where("created_at", ">=", now()->subDays(7))->count(),
            "unread_messages"   => 0,
        ];

        if ($user && $user->isAdmin()) {
            // Admin gets total user count for the stat card
            $response["total_users"] = User::count();
            
            // NEW: Add recent users count for admin
            $response["new_users_this_week"] = User::where('created_at', '>=', now()->subDays(7))->count();
        } elseif ($user) {
            // Regular user gets their own item/match counts
            $response["my_lost_items"]     = LostItem::where("user_id", $user->id)->count();
            $response["my_found_items"]    = FoundItem::where("user_id", $user->id)->count();
            $response["my_lost_recovered"] = LostItem::where("user_id", $user->id)
                                                ->where("status", "returned")->count();
            $response["my_found_claimed"]  = FoundItem::where("user_id", $user->id)
                                                ->where("status", "claimed")->count();
            $response["my_matches"]        = ItemMatch::where(function ($q) use ($user) {
                $q->whereHas("lostItem",  fn($q) => $q->where("user_id", $user->id))
                  ->orWhereHas("foundItem", fn($q) => $q->where("user_id", $user->id));
            })->count();
        }

        return response()->json($response);
    }

    /**
     * API: Get recent items
     */
    public function recentItems()
    {
        $recentLost = LostItem::with("user")->latest()->take(5)->get()->map(fn($item) => [
            "id"         => $item->id,
            "item_name"  => $item->item_name,
            "category"   => $item->category,
            "status"     => $item->status,
            "type"       => "lost",
            "created_at" => $item->created_at,
            "location"   => $item->lost_location ?? $item->location ?? null,
            "user_name"  => $item->user?->name,
        ]);

        $recentFound = FoundItem::with("user")->latest()->take(5)->get()->map(fn($item) => [
            "id"         => $item->id,
            "item_name"  => $item->item_name,
            "category"   => $item->category,
            "status"     => $item->status,
            "type"       => "found",
            "created_at" => $item->created_at,
            "location"   => $item->found_location ?? $item->location ?? null,
            "user_name"  => $item->user?->name,
        ]);

        $allItems = $recentLost->concat($recentFound)
            ->sortByDesc("created_at")
            ->take(10)
            ->values();

        return response()->json($allItems);
    }
    
    /**
     * API: Get recent users (Admin only)
     */
    public function recentUsers()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $recentUsers = User::latest()
            ->take(10)
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'role'       => $u->isAdmin() ? 'admin' : 'user',
                'created_at' => $u->created_at,
                'joined'     => $u->created_at->diffForHumans(),
                'avatar'     => $u->avatar ?? null,
                'stats'      => [
                    'lost_items'  => $u->lostItems()->count(),
                    'found_items' => $u->foundItems()->count(),
                    'matches'     => ItemMatch::where(function ($q) use ($u) {
                        $q->whereHas('lostItem', fn($q) => $q->where('user_id', $u->id))
                          ->orWhereHas('foundItem', fn($q) => $q->where('user_id', $u->id));
                    })->count(),
                ],
            ]);
            
        return response()->json([
            'recent_users' => $recentUsers,
            'total_users' => User::count(),
            'new_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ]);
    }
}