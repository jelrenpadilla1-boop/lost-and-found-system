<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ItemMatch;

class ItemMatchPolicy
{
    public function confirm(User $user, ItemMatch $match): bool
    {
        return $user->id === $match->lostItem->user_id || 
               $user->id === $match->foundItem->user_id ||
               $user->isAdmin();
    }

    public function reject(User $user, ItemMatch $match): bool
    {
        return $this->confirm($user, $match);
    }
}