<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $conversation;
    protected $message;
    protected $sender;

    public function __construct(Conversation $conversation, Message $message, User $sender)
    {
        $this->conversation = $conversation;
        $this->message = $message;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return [\App\Channels\DatabaseChannel::class];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $preview = substr($this->message->content, 0, 50)
            . (strlen($this->message->content) > 50 ? '...' : '');

        return [
            'title' => '💬 New Message',
            'body' => $this->sender->name . ' sent you a message',
            'url' => route('messages.show', $this->conversation->id),
            'data' => [
                'conversation_id' => $this->conversation->id,
                'sender_id' => $this->sender->id,
                'sender_name' => $this->sender->name,
                'message_preview' => $preview,
            ],
        ];
    }
}