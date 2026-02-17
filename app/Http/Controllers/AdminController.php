<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ItemMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function users(Request $request)
    {
        $query = User::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($request->filled('role') && in_array($request->role, ['user', 'admin'])) {
            $query->where('role', $request->role);
        }
        
        // Filter by period (new this week, etc.)
        if ($request->filled('period')) {
            if ($request->period === 'this_week') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($request->period === 'this_month') {
                $query->where('created_at', '>=', now()->subDays(30));
            } elseif ($request->period === 'this_year') {
                $query->where('created_at', '>=', now()->subDays(365));
            }
        }
        
        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        // Validate sort column to prevent SQL injection
        $allowedSorts = ['id', 'name', 'email', 'role', 'created_at', 'updated_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        
        $query->orderBy($sort, $direction);
        
        // Load relationship counts for each user
        $users = $query->withCount(['lostItems', 'foundItems'])->paginate(20)->withQueryString();
        
        // Get overall statistics for the cards (these ignore the filters to show true totals)
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $userCount = User::where('role', 'user')->count();
        $newThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();
        
        return view('admin.users.index', compact(
            'users', 
            'totalUsers', 
            'adminCount', 
            'userCount', 
            'newThisWeek'
        ));
    }

    /**
     * Display the specified user.
     */
    public function showUser(User $user)
    {
        // Eager load relationships for better performance
        $user->load(['lostItems', 'foundItems']);
        
        // Get user's item IDs
        $lostItemIds = $user->lostItems()->pluck('id');
        $foundItemIds = $user->foundItems()->pluck('id');
        
        // Get user statistics
        $stats = [
            'lost_items' => $user->lost_items_count ?? $user->lostItems()->count(),
            'found_items' => $user->found_items_count ?? $user->foundItems()->count(),
            'confirmed_matches' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->where('status', 'confirmed')->count(),
            'pending_matches' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->where('status', 'pending')->count(),
            'rejected_matches' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->where('status', 'rejected')->count(),
        ];
        
        $recentActivities = $this->getUserRecentActivities($user);
        
        return view('admin.users.show', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:user,admin'],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);
        
        // Handle is_active if present in the request
        if ($request->has('is_active')) {
            $validated['is_active'] = true;
        } else {
            $validated['is_active'] = false;
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Password reset successfully');
    }

    /**
     * Delete a user.
     */
    public function deleteUser(Request $request, User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Check if user has any items before deletion
        $hasItems = $user->lostItems()->exists() || $user->foundItems()->exists();
        
        if ($hasItems && !$request->has('force_delete')) {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'User has items. Use force delete option or delete the items first.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Get user recent activities.
     */
    private function getUserRecentActivities(User $user)
    {
        // Get recent lost items
        $lostItems = $user->lostItems()
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent found items
        $foundItems = $user->foundItems()
            ->latest()
            ->take(5)
            ->get();
        
        // Get user's item IDs
        $lostItemIds = $user->lostItems()->pluck('id');
        $foundItemIds = $user->foundItems()->pluck('id');
        
        // Get recent matches
        $matches = ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
            $query->whereIn('lost_item_id', $lostItemIds)
                  ->orWhereIn('found_item_id', $foundItemIds);
        })
        ->with(['lostItem', 'foundItem'])
        ->latest()
        ->take(5)
        ->get();
        
        return compact('lostItems', 'foundItems', 'matches');
    }
}