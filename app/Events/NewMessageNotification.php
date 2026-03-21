<?php

namespace App\Events;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $user;
    public $conversation;
    public $message;

    public function __construct(User $user, Conversation $conversation, Message $message)
    {
        $this->user = $user;
        $this->conversation = $conversation;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'new-message';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'conversation_id' => $this->conversation->id,
            'message' => substr($this->message->content, 0, 50) . (strlen($this->message->content) > 50 ? '...' : ''),
            'sender_name' => $this->message->user->name,
            'time' => now()->diffForHumans(),
            'type' => 'message',
            'url' => route('messages.show', $this->conversation->id)
        ];
    }
}