<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        try {
            $notifications = Notification::where('user_id', Auth::id())
                ->latest()
                ->paginate(20);

            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            // Debug: Log the notifications to see what's being fetched
            Log::info('Notifications fetched for user ' . Auth::id() . ': ' . $notifications->count());

            return view('notifications.index', compact('notifications', 'unreadCount'));
        } catch (\Exception $e) {
            Log::error('Notification index error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load notifications');
        }
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if ($notification) {
                $notification->update(['is_read' => true]);

                return response()->json([
                    'success' => true,
                    'message' => 'Notification marked as read'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        try {
            $updated = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'count' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('Mark all as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount()
    {
        try {
            $count = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Get unread count error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * Get recent notifications.
     */
    public function getRecent()
    {
        try {
            $notifications = Notification::where('user_id', Auth::id())
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'body' => $notification->body,
                        'url' => $notification->url,
                        'is_read' => $notification->is_read,
                        'icon' => $notification->icon_data,
                        'created_at' => $notification->created_at->diffForHumans(),
                    ];
                });

            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            Log::error('Get recent error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'notifications' => [],
                'unread_count' => 0
            ], 500);
        }
    }

    /**
     * Delete a specific notification.
     */
    public function delete($id)
    {
        try {
            $deleted = Notification::where('user_id', Auth::id())
                ->where('id', $id)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Delete notification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    /**
     * Clear all notifications.
     */
    public function clearAll()
    {
        try {
            $deleted = Notification::where('user_id', Auth::id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'All notifications cleared successfully',
                'count' => $deleted
            ]);

        } catch (\Exception $e) {
            Log::error('Clear all error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }
}