<?php

namespace App\Events;

use App\Models\User;
use App\Models\ItemMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMatchNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $user;
    public $match;

    public function __construct(User $user, ItemMatch $match)
    {
        $this->user = $user;
        $this->match = $match;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'new-match';
    }

    public function broadcastWith()
    {
        $isLost = $this->match->lostItem->user_id === $this->user->id;
        
        return [
            'user_id' => $this->user->id,
            'match_id' => $this->match->id,
            'match_score' => $this->match->match_score,
            'item_name' => $isLost ? $this->match->foundItem->item_name : $this->match->lostItem->item_name,
            'message' => $isLost 
                ? "Potential match found for your lost item: {$this->match->foundItem->item_name}"
                : "Your found item matches: {$this->match->lostItem->item_name}",
            'time' => now()->diffForHumans(),
            'type' => 'match',
            'url' => route('matches.show', $this->match->id)
        ];
    }
}