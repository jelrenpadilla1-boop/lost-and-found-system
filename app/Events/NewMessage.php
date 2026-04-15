<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $conversation;

    public function __construct(Message $message, Conversation $conversation)
    {
        $this->message = $message;
        $this->conversation = $conversation;
    }

    public function broadcastOn()
    {
        return new Channel('conversation.' . $this->conversation->id);
    }

    public function broadcastAs()
    {
        return 'new-message';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'photo' => $this->message->photo ? asset('storage/' . $this->message->photo) : null,
            'type' => $this->message->type ?? 'text',
            'user_id' => $this->message->user_id,
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'profile_photo' => $this->message->user->profile_photo ? asset('storage/' . $this->message->user->profile_photo) : null
            ],
            'created_at' => $this->message->created_at->toDateTimeString(),
            'time' => $this->message->created_at->format('g:i A'),
            'conversation_id' => $this->conversation->id,
            'is_read' => $this->message->is_read
        ];
    }
}