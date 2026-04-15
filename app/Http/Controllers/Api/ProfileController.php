<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'location' => $user->location,
                    'profile_photo' => $user->profile_photo,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'phone' => ['nullable', 'string', 'max:20'],
                'location' => ['nullable', 'string', 'max:255'],
                'profile_photo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            ]);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                
                // Store the file
                $file = $request->file('profile_photo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile-photos', $filename, 'public');
                
                $validated['profile_photo'] = $path;
                
                \Log::info('Photo uploaded to: ' . $path);
            }

            // Only update fields that are present
            $updateData = array_filter($validated, function($key) {
                return in_array($key, ['name', 'email', 'phone', 'location', 'profile_photo']);
            }, ARRAY_FILTER_USE_KEY);
            
            if (!empty($updateData)) {
                $user->update($updateData);
            }

            // Refresh the user to get updated data
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'location' => $user->location,
                    'profile_photo' => $user->profile_photo,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                    'updated_at' => $user->updated_at,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload profile photo separately.
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'profile_photo' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            ]);
            
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Store the file
            $file = $request->file('profile_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');
            
            $user->update(['profile_photo' => $path]);
            
            // Refresh user
            $user->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile photo updated successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'location' => $user->location,
                    'profile_photo' => $user->profile_photo,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Photo upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
                'new_password_confirmation' => ['required', 'string', 'min:8'],
            ]);

            Auth::user()->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove profile photo.
     */
    public function removePhoto()
    {
        try {
            $user = Auth::user();
            
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
                $user->update(['profile_photo' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile photo removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove profile photo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics.
     */
    public function getStats()
    {
        try {
            $user = Auth::user();
            
            $stats = [
                'total_matches' => $user->matches()->count(),
                'pending_matches' => $user->matches()->where('status', 'pending')->count(),
                'confirmed_matches' => $user->matches()->where('status', 'confirmed')->count(),
                'rejected_matches' => $user->matches()->where('status', 'rejected')->count(),
                'lost_items' => $user->lostItems()->count(),
                'found_items' => $user->foundItems()->count(),
                'pending_lost_items' => $user->lostItems()->where('status', 'pending')->count(),
                'pending_found_items' => $user->foundItems()->where('status', 'pending')->count(),
                'recovered_items' => $user->lostItems()->whereIn('status', ['found', 'returned'])->count(),
                'claimed_items' => $user->foundItems()->whereIn('status', ['claimed', 'returned'])->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's recent activity.
     */
    public function getActivity(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 10);
            
            // Get recent lost items
            $recentLost = $user->lostItems()
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'lost',
                        'item_name' => $item->item_name,
                        'status' => $item->status,
                        'created_at' => $item->created_at,
                    ];
                });
            
            // Get recent found items
            $recentFound = $user->foundItems()
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'found',
                        'item_name' => $item->item_name,
                        'status' => $item->status,
                        'created_at' => $item->created_at,
                    ];
                });
            
            // Combine and sort all activities
            $activities = $recentLost->merge($recentFound)
                ->sortByDesc('created_at')
                ->values()
                ->take($limit);
            
            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}