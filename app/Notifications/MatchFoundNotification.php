<?php

namespace App\Notifications;

use App\Models\ItemMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MatchFoundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $match;
    public $isLostItemOwner;

    public function __construct(ItemMatch $match, bool $isLostItemOwner)
    {
        $this->match = $match;
        $this->isLostItemOwner = $isLostItemOwner;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $itemType = $this->isLostItemOwner ? 'lost' : 'found';
        
        return (new MailMessage)
            ->subject('🎉 Potential Match Found for Your ' . ucfirst($itemType) . ' Item!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We found a potential match for your ' . $itemType . ' item!')
            ->line('**Match Score:** ' . $this->match->match_score . '%')
            ->line('**Lost Item:** ' . $this->match->lostItem->item_name)
            ->line('**Found Item:** ' . $this->match->foundItem->item_name)
            ->action('View Match Details', route('matches.show', $this->match))
            ->line('Thank you for using our Lost & Found System!');
    }

    public function toArray($notifiable): array
    {
        return [
            'match_id' => $this->match->id,
            'match_score' => $this->match->match_score,
            'lost_item_id' => $this->match->lostItem->id,
            'found_item_id' => $this->match->foundItem->id,
            'lost_item_name' => $this->match->lostItem->item_name,
            'found_item_name' => $this->match->foundItem->item_name,
            'is_lost_item_owner' => $this->isLostItemOwner,
            'message' => 'Potential match found with ' . $this->match->match_score . '% similarity',
        ];
    }
}