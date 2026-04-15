<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ItemMatch;
use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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

        // Clear the new-users badge — admin has now seen the users page
        Cache::put('admin_' . Auth::id() . '_users_last_seen', now(), now()->addDays(30));

        return view('admin.users.index', compact(
            'users', 
            'totalUsers', 
            'adminCount', 
            'userCount', 
            'newThisWeek'
        ));
    }

    /**
     * Show form to create a new user.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:user,admin'],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $photoPath;
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'location' => $validated['location'] ?? null,
            'profile_photo' => $validated['profile_photo'] ?? null,
            'email_verified_at' => now(), // Auto-verify email
            'is_active' => true, // Default to active
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show form to edit a user.
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
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
            'matches' => ItemMatch::where(function($query) use ($lostItemIds, $foundItemIds) {
                $query->whereIn('lost_item_id', $lostItemIds)
                      ->orWhereIn('found_item_id', $foundItemIds);
            })->count(),
        ];
        
        // Get recent activities for THIS user only
        $recentActivities = $this->getUserRecentActivities($user);
        
        return view('admin.users.show', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Get user recent activities (only for this specific user).
     */
    private function getUserRecentActivities(User $user)
    {
        // Get recent lost items for THIS user only
        $lostItems = $user->lostItems()
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent found items for THIS user only
        $foundItems = $user->foundItems()
            ->latest()
            ->take(5)
            ->get();
        
        // Get user's item IDs
        $lostItemIds = $user->lostItems()->pluck('id');
        $foundItemIds = $user->foundItems()->pluck('id');
        
        // Get recent matches related to THIS user's items only
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
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $photoPath;
        }

        // Handle photo removal
        if ($request->has('remove_photo') && $request->remove_photo) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = null;
        }
        
        // Handle password update if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }
        
        // Handle is_active if present in the request
        if ($request->has('is_active')) {
            $validated['is_active'] = true;
        } elseif ($request->has('is_active') === false && $request->method() === 'PUT') {
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
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
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
        
        // Delete profile photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Bulk delete users.
     */
    public function bulkDeleteUsers(Request $request)
    {
        $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        // Prevent deleting yourself
        $userIds = array_filter($request->user_ids, function($id) {
            return $id != auth()->id();
        });

        $count = User::whereIn('id', $userIds)->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "{$count} users deleted successfully");
    }

    /**
     * Display list of lost items (admin view)
     */
    public function lostItems(Request $request)
    {
        $query = LostItem::with('user');
        
        // Filter by user if specified
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $lostItems = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.items.lost', compact('lostItems'));
    }

    /**
     * Display list of found items (admin view)
     */
    public function foundItems(Request $request)
    {
        $query = FoundItem::with('user');
        
        // Filter by user if specified
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $foundItems = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.items.found', compact('foundItems'));
    }

    /**
     * Display list of matches (admin view)
     */
    public function matches(Request $request)
    {
        $query = ItemMatch::with(['lostItem', 'foundItem', 'lostItem.user', 'foundItem.user']);
        
        // Filter by user if specified
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            $query->where(function($q) use ($userId) {
                $q->whereHas('lostItem', function($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                })->orWhereHas('foundItem', function($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by match score
        if ($request->filled('min_score')) {
            $query->where('match_score', '>=', $request->min_score);
        }
        
        $matches = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.matches.index', compact('matches'));
    }

    /**
     * Display pending matches (admin view)
     */
    public function pendingMatches(Request $request)
    {
        $query = ItemMatch::with(['lostItem', 'foundItem', 'lostItem.user', 'foundItem.user'])
            ->where('status', 'pending');
        
        // Filter by user if specified
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            $query->where(function($q) use ($userId) {
                $q->whereHas('lostItem', function($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                })->orWhereHas('foundItem', function($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                });
            });
        }
        
        $pendingMatches = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.matches.pending', compact('pendingMatches'));
    }

    /**
     * Bulk update matches
     */
    public function bulkUpdateMatches(Request $request)
    {
        $request->validate([
            'match_ids' => ['required', 'array'],
            'match_ids.*' => ['exists:item_matches,id'],
            'status' => ['required', 'in:pending,confirmed,rejected']
        ]);
        
        $count = ItemMatch::whereIn('id', $request->match_ids)
            ->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', "{$count} matches updated successfully");
    }

    /**
     * Dashboard statistics.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_lost_items' => LostItem::count(),
            'total_found_items' => FoundItem::count(),
            'total_matches' => ItemMatch::count(),
            'pending_matches' => ItemMatch::where('status', 'pending')->count(),
            'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
            'users_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'items_this_week' => LostItem::where('created_at', '>=', now()->subDays(7))->count() +
                                 FoundItem::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentMatches = ItemMatch::with(['lostItem', 'foundItem'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentMatches'));
    }
}