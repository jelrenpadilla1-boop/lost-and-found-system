<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LostItem;

class LostItemPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LostItem $lostItem): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, LostItem $lostItem): bool
    {
        return $user->id === $lostItem->user_id || $user->isAdmin();
    }

    public function delete(User $user, LostItem $lostItem): bool
    {
        return $user->id === $lostItem->user_id || $user->isAdmin();
    }
}