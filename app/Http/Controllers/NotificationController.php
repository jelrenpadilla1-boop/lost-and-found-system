<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get notifications with pagination
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get unread count
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            $notification = Notification::where('user_id', $user->id)
                ->where('id', $id)
                ->first();
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->update(['is_read' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'notification_id' => $notification->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            $updated = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'count' => $updated
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific notification.
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            $notification = Notification::where('user_id', $user->id)
                ->where('id', $id)
                ->first();
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all notifications for the authenticated user.
     */
    public function clearAll()
    {
        try {
            $user = Auth::user();
            
            $deleted = Notification::where('user_id', $user->id)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications cleared',
                'count' => $deleted
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notification count for the authenticated user.
     */
    public function getUnreadCount()
    {
        try {
            $user = Auth::user();
            
            $count = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count',
                'count' => 0
            ], 500);
        }
    }

    /**
     * Create a new notification (helper method for other parts of the app)
     */
    public static function create($userId, $title, $body, $url = null, $iconData = null)
    {
        try {
            $notification = Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'url' => $url,
                'is_read' => false,
                'data' => [
                    'icon' => $iconData['icon'] ?? 'bell',
                    'color' => $iconData['color'] ?? '#64ffda',
                    'created_at' => now()
                ]
            ]);
            
            return $notification;
            
        } catch (\Exception $e) {
            \Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }
}