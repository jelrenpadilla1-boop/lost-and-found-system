<?php

namespace App\Notifications;

use App\Models\ItemMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MatchFoundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $match;
    protected $isLostItem;

    public function __construct(ItemMatch $match, bool $isLostItem)
    {
        $this->match = $match;
        $this->isLostItem = $isLostItem;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $itemName = $this->isLostItem
            ? $this->match->foundItem->item_name
            : $this->match->lostItem->item_name;

        $body = $this->isLostItem
            ? "Potential match found for your lost item: {$itemName}"
            : "Your found item matches: {$itemName}";

        return [
            'user_id' => $notifiable->id,
            'type' => 'match',
            'title' => '🔗 New Match Found!',
            'body' => $body,
            'url' => route('matches.show', $this->match->id),
            'icon' => json_encode(['icon' => 'exchange-alt', 'color' => '#00f0c8']),
            'data' => json_encode([
                'match_id' => $this->match->id,
                'match_score' => $this->match->match_score,
                'lost_item_id' => $this->match->lost_item_id,
                'found_item_id' => $this->match->found_item_id,
                'lost_item_name' => $this->match->lostItem->item_name,
                'found_item_name' => $this->match->foundItem->item_name,
            ]),
            'is_read' => false,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $itemName = $this->isLostItem
            ? $this->match->foundItem->item_name
            : $this->match->lostItem->item_name;

        $body = $this->isLostItem
            ? "Potential match found for your lost item: {$itemName}"
            : "Your found item matches: {$itemName}";

        return new BroadcastMessage([
            'id' => $this->match->id,
            'type' => 'match',
            'title' => '🔗 New Match Found!',
            'body' => $body,
            'url' => route('matches.show', $this->match->id),
            'time' => now()->diffForHumans(),
            'icon' => 'exchange-alt',
            'color' => '#00f0c8'
        ]);
    }
}