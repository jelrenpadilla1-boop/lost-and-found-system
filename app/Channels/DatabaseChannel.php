<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class DatabaseChannel
{
    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        return $notifiable->routeNotificationFor('database')->create([
            'user_id' => $notifiable->id,
            'type' => get_class($notification),
            'title' => $data['title'] ?? null,
            'body' => $data['body'] ?? null,
            'url' => $data['url'] ?? null,
            'data' => json_encode($data['data'] ?? []),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}