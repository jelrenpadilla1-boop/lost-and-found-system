<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $notification;

    public function __construct($userId, $notification)
    {
        $this->userId = $userId;
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('notifications.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'notification.sent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification['id'] ?? null,
            'type' => $this->notification['type'] ?? 'info',
            'title' => $this->notification['title'] ?? 'New Notification',
            'body' => $this->notification['body'] ?? '',
            'url' => $this->notification['url'] ?? null,
            'time' => now()->diffForHumans(),
            'icon' => $this->notification['icon'] ?? 'bell',
            'color' => $this->notification['color'] ?? '#00f0c8'
        ];
    }
}