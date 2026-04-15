<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get all users (except current user)
     */
    public function index(Request $request)
    {
        $users = User::where('id', '!=', Auth::id())
            ->select(['id', 'name', 'email', 'profile_photo', 'created_at'])
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
    
    /**
     * Get current user profile
     */
    public function me()
    {
        return response()->json(Auth::user());
    }
    
    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|max:2048',
        ]);
        
        $user->update($validated);
        
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
    
    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }
        
        $user->password = \Hash::make($request->new_password);
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
    
    /**
     * Remove profile photo
     */
    public function removePhoto()
    {
        $user = Auth::user();
        
        if ($user->profile_photo) {
            \Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Profile photo removed'
        ]);
    }
    
    /**
     * Get user stats
     */
    public function stats()
    {
        $user = Auth::user();
        
        return response()->json([
            'lost_count' => $user->lostItems()->count(),
            'found_count' => $user->foundItems()->count(),
            'matches_count' => $user->matches()->count(),
            'unread_messages' => $user->unreadMessagesCount(),
        ]);
    }
    
    /**
     * Get recent activity
     */
    public function recentActivity()
    {
        $user = Auth::user();
        
        $lostItems = $user->lostItems()->latest()->limit(5)->get();
        $foundItems = $user->foundItems()->latest()->limit(5)->get();
        $matches = $user->matches()->latest()->limit(5)->get();
        
        return response()->json([
            'lost_items' => $lostItems,
            'found_items' => $foundItems,
            'matches' => $matches,
        ]);
    }
    
    /**
     * Get user items
     */
    public function items()
    {
        $user = Auth::user();
        
        $lostItems = $user->lostItems()->latest()->paginate(10);
        $foundItems = $user->foundItems()->latest()->paginate(10);
        
        return response()->json([
            'lost_items' => $lostItems,
            'found_items' => $foundItems,
        ]);
    }
    
    /**
     * Get user matches
     */
    public function matches()
    {
        $user = Auth::user();
        
        $matches = $user->matches()
            ->with(['lostItem', 'foundItem'])
            ->latest()
            ->paginate(10);
        
        return response()->json($matches);
    }
    
    /**
     * Search users
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $users = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query . '%')
                  ->orWhere('email', 'like', '%' . $request->query . '%');
            })
            ->limit(20)
            ->get(['id', 'name', 'email', 'profile_photo']);
        
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
}