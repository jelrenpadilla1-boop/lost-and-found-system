<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Get all conversations for the authenticated user
     */
    public function index()
    {
        try {
            $conversations = Conversation::where('user1_id', Auth::id())
                ->orWhere('user2_id', Auth::id())
                ->with(['user1', 'user2', 'lastMessage'])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function($conversation) {
                    // Add unread count to each conversation
                    $unreadCount = Message::where('conversation_id', $conversation->id)
                        ->where('user_id', '!=', Auth::id())
                        ->where('is_read', false)
                        ->count();
                    
                    $conversation->unread_count = $unreadCount;
                    
                    // Format last message time
                    if ($conversation->lastMessage) {
                        $conversation->lastMessage->formatted_time = $conversation->lastMessage->created_at->diffForHumans();
                    }
                    
                    return $conversation;
                });

            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching conversations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch conversations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific conversation with all messages
     */
    public function show(Conversation $conversation)
    {
        try {
            // Check if user is part of the conversation
            if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this conversation'
                ], 403);
            }

            // Mark messages as read
            Message::where('conversation_id', $conversation->id)
                ->where('user_id', '!=', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $messages = $conversation->messages()
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($message) {
                    return [
                        'id' => $message->id,
                        'content' => $message->content,
                        'user_id' => $message->user_id,
                        'is_read' => $message->is_read,
                        'created_at' => $message->created_at,
                        'time' => $message->created_at->format('g:i A'),
                        'user' => [
                            'id' => $message->user->id,
                            'name' => $message->user->name,
                            'profile_photo' => $message->user->profile_photo,
                        ]
                    ];
                });

            // Get other user details
            $otherUser = $conversation->user1_id === Auth::id() 
                ? $conversation->user2 
                : $conversation->user1;

            return response()->json([
                'success' => true,
                'conversation' => [
                    'id' => $conversation->id,
                    'user1_id' => $conversation->user1_id,
                    'user2_id' => $conversation->user2_id,
                    'user1' => $conversation->user1,
                    'user2' => $conversation->user2,
                    'other_user' => $otherUser,
                    'created_at' => $conversation->created_at,
                    'updated_at' => $conversation->updated_at,
                ],
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching conversation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch conversation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message in a conversation
     */
    public function send(Request $request, Conversation $conversation)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000'
            ]);

            // Check if user is part of the conversation
            if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to send message in this conversation'
                ], 403);
            }

            DB::beginTransaction();

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'content' => $request->message,
                'is_read' => false
            ]);

            $message->load('user');
            $conversation->touch();

            // Get the recipient user
            $recipientId = $conversation->user1_id === Auth::id() 
                ? $conversation->user2_id 
                : $conversation->user1_id;
            
            $recipient = User::find($recipientId);

            // Create notification for recipient - FIXED: Properly encode data as JSON
            if ($recipient && $recipientId !== Auth::id()) {
                try {
                    Notification::create([
                        'user_id' => $recipientId,
                        'type'    => 'message',
                        'title'   => '💬 New Message',
                        'body'    => Auth::user()->name . ' sent you a message: ' . substr($request->message, 0, 50),
                        'url'     => route('messages.show', $conversation->id),
                        'data'    => json_encode([
                            'icon'            => 'comment',
                            'color'           => '#7efff5',
                            'conversation_id' => $conversation->id,
                            'sender_name'     => Auth::user()->name,
                            'sender_id'       => Auth::id(),
                            'message_id'      => $message->id,
                        ]),
                        'is_read' => false,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create notification: ' . $e->getMessage());
                    // Don't fail the message if notification fails
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'user_id' => $message->user_id,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at,
                    'time' => $message->created_at->format('g:i A'),
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'profile_photo' => $message->user->profile_photo,
                    ]
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Message send error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a new conversation with a user
     */
    public function start(User $user)
    {
        try {
            // Don't allow starting conversation with yourself
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot start a conversation with yourself'
                ], 400);
            }

            // Check if conversation already exists
            $conversation = Conversation::where(function($query) use ($user) {
                $query->where('user1_id', Auth::id())
                      ->where('user2_id', $user->id);
            })->orWhere(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->where('user2_id', Auth::id());
            })->first();

            // If no conversation exists, create one
            if (!$conversation) {
                $conversation = Conversation::create([
                    'user1_id' => Auth::id(),
                    'user2_id' => $user->id
                ]);
            }

            return response()->json([
                'success' => true,
                'id' => $conversation->id,
                'message' => 'Conversation started successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error starting conversation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to start conversation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread messages count for the authenticated user
     */
    public function getUnreadCount()
    {
        try {
            $count = Message::whereIn('conversation_id', function($query) {
                $query->select('id')
                    ->from('conversations')
                    ->where('user1_id', Auth::id())
                    ->orWhere('user2_id', Auth::id());
            })->where('user_id', '!=', Auth::id())
              ->where('is_read', false)
              ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent messages for sidebar/notification
     */
    public function getRecentMessages()
    {
        try {
            $conversations = Conversation::where('user1_id', Auth::id())
                ->orWhere('user2_id', Auth::id())
                ->with(['user1', 'user2', 'lastMessage'])
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();

            $recentMessages = [];
            foreach ($conversations as $conv) {
                $otherUser = $conv->user1_id === Auth::id() ? $conv->user2 : $conv->user1;
                $unreadCount = Message::where('conversation_id', $conv->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('is_read', false)
                    ->count();

                $recentMessages[] = [
                    'id' => $conv->id,
                    'user_id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar' => substr($otherUser->name, 0, 1),
                    'profile_photo' => $otherUser->profile_photo,
                    'last_message' => $conv->lastMessage ? $conv->lastMessage->content : 'No messages yet',
                    'time' => $conv->updated_at->diffForHumans(),
                    'unread' => $unreadCount,
                    'online' => $this->isUserOnline($otherUser)
                ];
            }

            return response()->json([
                'success' => true,
                'messages' => $recentMessages
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching recent messages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'messages' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Poll for new messages (for real-time updates)
     */
    public function pollNewMessages(Request $request)
    {
        try {
            $validated = $request->validate([
                'conversation_id' => 'required|integer',
                'last_message_id' => 'required|integer'
            ]);

            $conversation = Conversation::find($validated['conversation_id']);
            
            // Check if user is part of the conversation
            if (!$conversation || ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $messages = Message::where('conversation_id', $validated['conversation_id'])
                ->where('id', '>', $validated['last_message_id'])
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

            $formattedMessages = [];
            foreach ($messages as $message) {
                $formattedMessages[] = [
                    'id' => $message->id,
                    'content' => $message->content,
                    'user_id' => $message->user_id,
                    'is_read' => $message->is_read,
                    'is_mine' => $message->user_id === Auth::id(),
                    'created_at' => $message->created_at,
                    'time' => $message->created_at->format('g:i A'),
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'profile_photo' => $message->user->profile_photo,
                    ]
                ];
            }

            return response()->json($formattedMessages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error polling messages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to poll messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all messages in a conversation as read
     */
    public function markAsRead(Conversation $conversation)
    {
        try {
            // Check if user is part of the conversation
            if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $updated = Message::where('conversation_id', $conversation->id)
                ->where('user_id', '!=', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'count' => $updated,
                'message' => "{$updated} messages marked as read"
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking messages as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark messages as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a message (soft delete or hard delete based on permissions)
     */
    public function deleteMessage(Message $message)
    {
        try {
            // Check if user is the sender
            if ($message->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this message'
                ], 403);
            }

            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread messages count for a specific conversation
     */
    public function getConversationUnreadCount(Conversation $conversation)
    {
        try {
            if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $count = Message::where('conversation_id', $conversation->id)
                ->where('user_id', '!=', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching conversation unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a user is online (you can implement this based on your needs)
     * For now, we'll just return false
     */
    private function isUserOnline($user)
    {
        // Implement your online status logic here
        // For example, check if user was active within last 5 minutes
        if ($user && $user->last_activity) {
            $lastActivity = \Carbon\Carbon::parse($user->last_activity);
            return $lastActivity->diffInMinutes(now()) < 5;
        }
        return false;
    }
}