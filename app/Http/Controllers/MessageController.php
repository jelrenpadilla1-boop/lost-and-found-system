<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('user1_id', Auth::id())
            ->orWhere('user2_id', Auth::id())
            ->with(['user1', 'user2', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get all users except current user
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return view('messages.index', compact('conversations', 'users'));
    }

    public function show(Conversation $conversation)
    {
        // Check if user is part of the conversation
        if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
            abort(403);
        }

        // Mark messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get();
        
        // Get all conversations for the sidebar
        $conversations = Conversation::where('user1_id', Auth::id())
            ->orWhere('user2_id', Auth::id())
            ->with(['user1', 'user2', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('messages.show', compact('conversation', 'messages', 'conversations'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000'
            ]);

            if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'content' => $request->message,
                'type' => 'text',
                'is_read' => false
            ]);

            $message->load('user');
            $conversation->touch();

            // Get the recipient user
            $recipientId = $conversation->user1_id === Auth::id() 
                ? $conversation->user2_id 
                : $conversation->user1_id;
            
            $recipient = User::find($recipientId);

            // Broadcast real-time event
            broadcast(new NewMessage($message, $conversation))->toOthers();

            // Send notification to recipient
            if ($recipient) {
                $recipient->notify(new \App\Notifications\NewMessageNotification(
                    $conversation,
                    $message,
                    Auth::user()
                ));
            }

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'type' => $message->type,
                    'photo' => null,
                    'user_id' => $message->user_id,
                    'time' => $message->created_at->format('g:i A'),
                    'is_mine' => true,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'profile_photo' => $message->user->profile_photo
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Message send error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message with photo
     */
    public function sendPhoto(Request $request, Conversation $conversation)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
            ]);

            if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Store the photo
            $photoPath = $request->file('photo')->store('chat-photos', 'public');

            // Create the message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'content' => '',
                'photo' => $photoPath,
                'type' => 'photo',
                'is_read' => false
            ]);

            $message->load('user');
            $conversation->touch();

            // Get the recipient user
            $recipientId = $conversation->user1_id === Auth::id() 
                ? $conversation->user2_id 
                : $conversation->user1_id;
            
            $recipient = User::find($recipientId);

            // Broadcast real-time event
            broadcast(new NewMessage($message, $conversation))->toOthers();

            // Send notification to recipient
            if ($recipient) {
                $recipient->notify(new \App\Notifications\NewMessageNotification(
                    $conversation,
                    $message,
                    Auth::user()
                ));
            }

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'content' => '',
                    'photo' => asset('storage/' . $photoPath),
                    'type' => 'photo',
                    'user_id' => $message->user_id,
                    'time' => $message->created_at->format('g:i A'),
                    'is_mine' => true,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'profile_photo' => $message->user->profile_photo
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Photo send error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to send photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function start(Request $request, User $user)
    {
        // Don't allow starting conversation with yourself
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot start a conversation with yourself');
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

        // Redirect to the conversation
        return redirect()->route('messages.show', $conversation);
    }

    public function getUnreadCount()
    {
        $count = Message::whereIn('conversation_id', function($query) {
            $query->select('id')
                ->from('conversations')
                ->where('user1_id', Auth::id())
                ->orWhere('user2_id', Auth::id());
        })->where('user_id', '!=', Auth::id())
          ->where('is_read', false)
          ->count();

        return response()->json(['count' => $count]);
    }

    public function getRecentMessages()
    {
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

            $lastMessage = $conv->lastMessage;
            $lastMessageText = 'No messages yet';
            if ($lastMessage) {
                if ($lastMessage->type === 'photo') {
                    $lastMessageText = '📷 Sent a photo';
                } else {
                    $lastMessageText = $lastMessage->content;
                }
            }

            $recentMessages[] = [
                'id' => $conv->id,
                'user_id' => $otherUser->id,
                'name' => $otherUser->name,
                'avatar' => substr($otherUser->name, 0, 1),
                'profile_photo' => $otherUser->profile_photo,
                'last_message' => $lastMessageText,
                'time' => $conv->updated_at->diffForHumans(),
                'unread' => $unreadCount,
                'online' => $otherUser->isOnline()
            ];
        }

        return response()->json($recentMessages);
    }

    public function pollNewMessages(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'last_message_id' => 'required|integer'
        ]);

        $messages = Message::where('conversation_id', $request->conversation_id)
            ->where('id', '>', $request->last_message_id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = [
                'id' => $message->id,
                'content' => $message->content,
                'photo' => $message->photo ? asset('storage/' . $message->photo) : null,
                'type' => $message->type ?? 'text',
                'user_id' => $message->user_id,
                'is_mine' => $message->user_id === Auth::id(),
                'time' => $message->created_at->format('g:i A'),
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'profile_photo' => $message->user->profile_photo
                ]
            ];
        }

        return response()->json($formattedMessages);
    }

    public function markAsRead(Conversation $conversation)
    {
        // Check if user is part of the conversation
        if ($conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $updated = Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'count' => $updated]);
    }
}