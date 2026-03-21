<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;
    public $userId;

    public function __construct(Conversation $conversation, $userId)
    {
        $this->conversation = $conversation;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new Channel('conversation.' . $this->conversation->id);
    }

    public function broadcastAs()
    {
        return 'messages-read';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'conversation_id' => $this->conversation->id
        ];
    }
}