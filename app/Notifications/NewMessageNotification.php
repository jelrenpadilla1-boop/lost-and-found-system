<?php

namespace App\Notifications;

use App\Channels\DatabaseChannel;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
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
        return [DatabaseChannel::class];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $content = $this->message->type === 'photo'
            ? 'Sent a photo'
            : $this->message->content;

        $preview = substr($content, 0, 50)
            . (strlen($content) > 50 ? '...' : '');

        return [
            'type' => 'message',
            'title' => 'New Message',
            'body' => $this->sender->name . ' sent you a message',
            'url' => route('messages.show', $this->conversation->id),
            'data' => [
                'icon' => ['icon' => 'comment', 'color' => '#7efff5'],
                'conversation_id' => $this->conversation->id,
                'sender_id' => $this->sender->id,
                'sender_name' => $this->sender->name,
                'message_preview' => $preview,
            ],
        ];
    }
}
