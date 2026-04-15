<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // ✅ Guard against unauthenticated requests
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $notifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 20));

            $unreadCount = Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            Log::error('Notification index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function getUnreadCount()
    {
        try {
            $count = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json(['success' => true, 'count' => $count]);

        } catch (\Exception $e) {
            Log::error('Notification count error: ' . $e->getMessage());
            return response()->json(['success' => false, 'count' => 0], 500);
        }
    }

    public function getRecent()
    {
        try {
            $notifications = Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'data' => $notification->data,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at,
                        'time_ago' => $notification->created_at->diffForHumans(),
                    ];
                });

            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);

        } catch (\Exception $e) {
            Log::error('Notification recent error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'notifications' => []
            ], 500);
        }
    }

    public function markAsRead(Notification $notification)
    {
        // ✅ Check ownership
        if ($notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function delete(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return response()->json(['success' => true]);
    }
}