<?php

namespace App\Notifications;

use App\Channels\DatabaseChannel;
use App\Models\ItemMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MatchFoundNotification extends Notification
{
    use Queueable;

    protected $match;
    protected $isLostItem;

    public function __construct(ItemMatch $match, bool $isLostItem)
    {
        $this->match = $match;
        $this->isLostItem = $isLostItem;
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
        $itemName = $this->isLostItem
            ? $this->match->foundItem->item_name
            : $this->match->lostItem->item_name;

        $body = $this->isLostItem
            ? "Potential match found for your lost item: {$itemName}"
            : "Your found item matches: {$itemName}";

        return [
            'type' => 'match',
            'title' => 'New Match Found!',
            'body' => $body,
            'url' => route('matches.show', $this->match->id),
            'data' => [
                'icon' => ['icon' => 'exchange-alt', 'color' => '#00f0c8'],
                'match_id' => $this->match->id,
                'match_score' => $this->match->match_score,
                'lost_item_id' => $this->match->lost_item_id,
                'found_item_id' => $this->match->found_item_id,
                'lost_item_name' => $this->match->lostItem->item_name,
                'found_item_name' => $this->match->foundItem->item_name,
            ],
        ];
    }
}
