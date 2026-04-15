<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Check if user is admin (middleware already handles this)
     */
    private function ensureAdmin()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    /**
     * Get admin dashboard statistics
     */
    public function dashboard()
    {
        $this->ensureAdmin();
        
        // User stats
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();
        $newUsersThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();
        $usersThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();
        
        // Lost Items stats
        $totalLostItems = LostItem::count();
        $pendingLostItems = LostItem::where('status', 'pending')->count();
        $approvedLostItems = LostItem::where('status', 'approved')->count();
        $foundLostItems = LostItem::where('status', 'found')->count();
        $returnedLostItems = LostItem::where('status', 'returned')->count();
        $rejectedLostItems = LostItem::where('status', 'rejected')->count();
        
        // Found Items stats
        $totalFoundItems = FoundItem::count();
        $pendingFoundItems = FoundItem::where('status', 'pending')->count();
        $approvedFoundItems = FoundItem::where('status', 'approved')->count();
        $claimedFoundItems = FoundItem::where('status', 'claimed')->count();
        $returnedFoundItems = FoundItem::where('status', 'returned')->count();
        $rejectedFoundItems = FoundItem::where('status', 'rejected')->count();
        
        // Match stats
        $totalMatches = ItemMatch::count();
        $pendingMatches = ItemMatch::where('status', 'pending')->count();
        $confirmedMatches = ItemMatch::where('status', 'confirmed')->count();
        $rejectedMatches = ItemMatch::where('status', 'rejected')->count();
        
        // Weekly stats
        $itemsThisWeek = LostItem::where('created_at', '>=', now()->subDays(7))->count() +
                         FoundItem::where('created_at', '>=', now()->subDays(7))->count();
        $matchesThisWeek = ItemMatch::where('created_at', '>=', now()->subDays(7))->count();
        
        $stats = [
            // User stats
            'total_users' => $totalUsers,
            'admin_users' => $adminUsers,
            'regular_users' => $regularUsers,
            'active_users' => $activeUsers,
            'new_users_this_week' => $newUsersThisWeek,
            'users_this_week' => $usersThisWeek,
            
            // Lost Items stats
            'total_lost_items' => $totalLostItems,
            'pending_lost_items' => $pendingLostItems,
            'approved_lost_items' => $approvedLostItems,
            'found_lost_items' => $foundLostItems,
            'returned_lost_items' => $returnedLostItems,
            'rejected_lost_items' => $rejectedLostItems,
            
            // Found Items stats
            'total_found_items' => $totalFoundItems,
            'pending_found_items' => $pendingFoundItems,
            'approved_found_items' => $approvedFoundItems,
            'claimed_found_items' => $claimedFoundItems,
            'returned_found_items' => $returnedFoundItems,
            'rejected_found_items' => $rejectedFoundItems,
            
            // Match stats
            'total_matches' => $totalMatches,
            'pending_matches' => $pendingMatches,
            'confirmed_matches' => $confirmedMatches,
            'rejected_matches' => $rejectedMatches,
            
            // Weekly stats
            'items_this_week' => $itemsThisWeek,
            'matches_this_week' => $matchesThisWeek,
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
    
    /**
     * Get dashboard stats (alias for dashboard method)
     */
    public function dashboardStats()
    {
        return $this->dashboard();
    }
    
    /**
     * Get all users with pagination
     */
    public function users(Request $request)
    {
        $this->ensureAdmin();
        
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');
        
        $query = User::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($role && in_array($role, ['user', 'admin'])) {
            $query->where('role', $role);
        }
        
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Add counts for each user
        foreach ($users as $user) {
            $user->lost_items_count = $user->lostItems()->count();
            $user->found_items_count = $user->foundItems()->count();
        }
        
        return response()->json([
            'success' => true,
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
    
    /**
     * Get recent users for dashboard
     */
    public function recentUsers()
    {
        $this->ensureAdmin();
        
        $users = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'email', 'profile_photo', 'role', 'created_at', 'is_active']);
        
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
    
    /**
     * Get single user details
     */
    public function showUser($id)
    {
        $this->ensureAdmin();
        
        $user = User::with(['lostItems', 'foundItems'])->findOrFail($id);
        
        // Get user's item IDs
        $lostItemIds = $user->lostItems()->pluck('id');
        $foundItemIds = $user->foundItems()->pluck('id');
        
        // Get match statistics
        $matchStats = [
            'total' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->count(),
            'pending' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->where('status', 'pending')->count(),
            'confirmed' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->where('status', 'confirmed')->count(),
            'rejected' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->where('status', 'rejected')->count(),
        ];
        
        // Get recent items
        $recentLost = $user->lostItems()->orderBy('created_at', 'desc')->limit(5)->get();
        $recentFound = $user->foundItems()->orderBy('created_at', 'desc')->limit(5)->get();
        
        return response()->json([
            'success' => true,
            'user' => $user,
            'stats' => [
                'lost_items_count' => $user->lostItems()->count(),
                'found_items_count' => $user->foundItems()->count(),
                'match_stats' => $matchStats,
            ],
            'recent_items' => [
                'lost' => $recentLost,
                'found' => $recentFound,
            ]
        ]);
    }
    
    /**
     * Create new user (admin only)
     */
    public function storeUser(Request $request)
    {
        $this->ensureAdmin();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|in:user,admin',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $photoPath;
        }
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
            'phone' => $validated['phone'] ?? null,
            'location' => $validated['location'] ?? null,
            'profile_photo' => $validated['profile_photo'] ?? null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }
    
    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $this->ensureAdmin();
        
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'sometimes|in:user,admin',
            'is_active' => 'sometimes|boolean',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $photoPath;
        }
        
        $user->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $this->ensureAdmin();
        
        // Don't allow deleting yourself
        if ($id == Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account'
            ], 400);
        }
        
        $user = User::findOrFail($id);
        
        // Delete profile photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        
        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
    
    /**
     * Bulk delete users
     */
    public function bulkDeleteUsers(Request $request)
    {
        $this->ensureAdmin();
        
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        // Remove current user from list
        $userIds = array_filter($validated['user_ids'], function($id) {
            return $id != Auth::id();
        });
        
        $count = User::whereIn('id', $userIds)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "{$count} users deleted successfully",
            'count' => $count
        ]);
    }
    
    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        $this->ensureAdmin();
        
        $validated = $request->validate([
            'password' => 'required|string|min:8'
        ]);
        
        $user = User::findOrFail($id);
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }
    
    /**
     * Get all items (lost and found) with filters
     */
    public function items(Request $request)
    {
        $this->ensureAdmin();
        
        $perPage = $request->get('per_page', 20);
        $type = $request->get('type');
        $status = $request->get('status');
        $search = $request->get('search');
        
        $lostItems = collect();
        $foundItems = collect();
        
        if (!$type || $type === 'lost') {
            $lostQuery = LostItem::with('user');
            
            if ($status) {
                $lostQuery->where('status', $status);
            }
            if ($search) {
                $lostQuery->where(function($q) use ($search) {
                    $q->where('item_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            $lostItems = $lostQuery->orderBy('created_at', 'desc')->paginate($perPage);
        }
        
        if (!$type || $type === 'found') {
            $foundQuery = FoundItem::with('user');
            
            if ($status) {
                $foundQuery->where('status', $status);
            }
            if ($search) {
                $foundQuery->where(function($q) use ($search) {
                    $q->where('item_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            $foundItems = $foundQuery->orderBy('created_at', 'desc')->paginate($perPage);
        }
        
        return response()->json([
            'success' => true,
            'lost_items' => $lostItems,
            'found_items' => $foundItems,
        ]);
    }
    
    /**
     * Get lost items only
     */
    public function lostItems(Request $request)
    {
        $this->ensureAdmin();
        
        $perPage = $request->get('per_page', 20);
        $status = $request->get('status');
        $search = $request->get('search');
        
        $query = LostItem::with('user');
        
        if ($status) {
            $query->where('status', $status);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $items = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'items' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ]
        ]);
    }
    
    /**
     * Get found items only
     */
    public function foundItems(Request $request)
    {
        $this->ensureAdmin();
        
        $perPage = $request->get('per_page', 20);
        $status = $request->get('status');
        $search = $request->get('search');
        
        $query = FoundItem::with('user');
        
        if ($status) {
            $query->where('status', $status);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $items = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'items' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ]
        ]);
    }
    
    /**
     * Get pending items
     */
    public function pendingItems(Request $request)
    {
        $this->ensureAdmin();
        
        $perPage = $request->get('per_page', 20);
        $type = $request->get('type');
        
        $pendingLost = collect();
        $pendingFound = collect();
        
        if (!$type || $type === 'lost') {
            $pendingLost = LostItem::with('user')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        }
        
        if (!$type || $type === 'found') {
            $pendingFound = FoundItem::with('user')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        }
        
        return response()->json([
            'success' => true,
            'pending_lost' => $pendingLost,
            'pending_found' => $pendingFound,
        ]);
    }
    
    /**
     * Bulk delete items
     */
    public function bulkDeleteItems(Request $request)
    {
        $this->ensureAdmin();
        
        $validated = $request->validate([
            'item_ids' => 'required|array',
            'type' => 'required|in:lost,found',
        ]);
        
        if ($validated['type'] === 'lost') {
            $count = LostItem::whereIn('id', $validated['item_ids'])->delete();
        } else {
            $count = FoundItem::whereIn('id', $validated['item_ids'])->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$count} items deleted successfully",
            'count' => $count
        ]);
    }
    
    /**
     * Get all matches with filters
     */
    public function matches(Request $request)
    {
        $this->ensureAdmin();
        
        $perPage = $request->get('per_page', 20);
        $status = $request->get('status');
        
        $query = ItemMatch::with(['lostItem.user', 'foundItem.user']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $matches = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'matches' => $matches->items(),
            'pagination' => [
                'current_page' => $matches->currentPage(),
                'last_page' => $matches->lastPage(),
                'per_page' => $matches->perPage(),
                'total' => $matches->total(),
            ]
        ]);
    }
    
    /**
     * Get pending matches
     */
    public function pendingMatches(Request $request)
    {
        $this->ensureAdmin();
        
        $perPage = $request->get('per_page', 20);
        
        $matches = ItemMatch::with(['lostItem.user', 'foundItem.user'])
            ->where('status', 'pending')
            ->orderBy('match_score', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'matches' => $matches->items(),
            'pagination' => [
                'current_page' => $matches->currentPage(),
                'last_page' => $matches->lastPage(),
                'per_page' => $matches->perPage(),
                'total' => $matches->total(),
            ]
        ]);
    }
    
    /**
     * Bulk update matches
     */
    public function bulkUpdateMatches(Request $request)
    {
        $this->ensureAdmin();
        
        $validated = $request->validate([
            'match_ids' => 'required|array',
            'match_ids.*' => 'exists:item_matches,id',
            'status' => 'required|in:confirmed,rejected',
        ]);
        
        $count = ItemMatch::whereIn('id', $validated['match_ids'])
            ->update(['status' => $validated['status']]);
        
        // Update related items for confirmed matches
        if ($validated['status'] === 'confirmed') {
            $matches = ItemMatch::whereIn('id', $validated['match_ids'])->get();
            
            foreach ($matches as $match) {
                if ($match->lostItem) {
                    $match->lostItem->update(['status' => 'found']);
                }
                if ($match->foundItem) {
                    $match->foundItem->update(['status' => 'claimed']);
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$count} matches updated successfully",
            'count' => $count
        ]);
    }
    
    /**
     * Get reports data
     */
    public function reports(Request $request)
    {
        $this->ensureAdmin();
        
        $period = $request->get('period', 'week');
        $startDate = now();
        
        switch ($period) {
            case 'week':
                $startDate = now()->subDays(7);
                break;
            case 'month':
                $startDate = now()->subDays(30);
                break;
            case 'year':
                $startDate = now()->subDays(365);
                break;
        }
        
        $reports = [
            'users' => [
                'total' => User::count(),
                'new' => User::where('created_at', '>=', $startDate)->count(),
                'active' => User::where('last_login_at', '>=', $startDate)->count(),
            ],
            'items' => [
                'total_lost' => LostItem::count(),
                'total_found' => FoundItem::count(),
                'new_lost' => LostItem::where('created_at', '>=', $startDate)->count(),
                'new_found' => FoundItem::where('created_at', '>=', $startDate)->count(),
            ],
            'matches' => [
                'total' => ItemMatch::count(),
                'confirmed' => ItemMatch::where('status', 'confirmed')->count(),
                'pending' => ItemMatch::where('status', 'pending')->count(),
                'rejected' => ItemMatch::where('status', 'rejected')->count(),
                'new' => ItemMatch::where('created_at', '>=', $startDate)->count(),
            ],
        ];
        
        return response()->json([
            'success' => true,
            'reports' => $reports,
            'period' => $period,
        ]);
    }
    
    /**
     * Get analytics data
     */
    public function analytics()
    {
        $this->ensureAdmin();
        
        // Get daily stats for last 30 days
        $dailyStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyStats[] = [
                'date' => $date,
                'new_users' => User::whereDate('created_at', $date)->count(),
                'new_lost_items' => LostItem::whereDate('created_at', $date)->count(),
                'new_found_items' => FoundItem::whereDate('created_at', $date)->count(),
                'new_matches' => ItemMatch::whereDate('created_at', $date)->count(),
            ];
        }
        
        // Category distribution
        $lostCategories = LostItem::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
        $foundCategories = FoundItem::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
        
        return response()->json([
            'success' => true,
            'daily_stats' => $dailyStats,
            'lost_categories' => $lostCategories,
            'found_categories' => $foundCategories,
        ]);
    }
    
    /**
     * Search across all content
     */
    public function search(Request $request)
    {
        $this->ensureAdmin();
        
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([
                'success' => true,
                'results' => []
            ]);
        }
        
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email', 'profile_photo']);
        
        $lostItems = LostItem::where('item_name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'item_name', 'category', 'status']);
        
        $foundItems = FoundItem::where('item_name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'item_name', 'category', 'status']);
        
        return response()->json([
            'success' => true,
            'results' => [
                'users' => $users,
                'lost_items' => $lostItems,
                'found_items' => $foundItems,
            ]
        ]);
    }
}