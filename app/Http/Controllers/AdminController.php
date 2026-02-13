<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($request->has('role') && in_array($request->role, ['user', 'admin'])) {
            $query->where('is_admin', $request->role === 'admin');
        }
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $users = $query->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function showUser(User $user)
    {
        // Get user statistics
        $stats = [
            'lost_items' => $user->lostItems()->count(),
            'found_items' => $user->foundItems()->count(),
            'confirmed_matches' => $user->matches()->where('status', 'confirmed')->count(),
            'pending_matches' => $user->matches()->where('status', 'pending')->count(),
        ];
        
        $recentActivities = $this->getUserRecentActivities($user);
        
        return view('admin.users.index', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'is_admin' => ['boolean'],
            'is_active' => ['boolean'],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);
        
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
    public function deleteUser(User $user)
    {
        // Optional: Check if user has any items before deletion
        $hasItems = $user->lostItems()->exists() || $user->foundItems()->exists();
        
        if ($hasItems && !request()->has('force_delete')) {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'User has items. Use force delete option.');
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
        $activities = collect();
        
        // Get recent lost items
        $lostItems = $user->lostItems()
            ->with('matches')
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent found items
        $foundItems = $user->foundItems()
            ->with('matches')
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent matches
        $matches = $user->matches()
            ->with(['lostItem', 'foundItem'])
            ->latest()
            ->take(5)
            ->get();
        
        return compact('lostItems', 'foundItems', 'matches');
    }
}