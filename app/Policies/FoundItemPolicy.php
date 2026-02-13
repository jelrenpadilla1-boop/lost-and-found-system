<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FoundItem;

class FoundItemPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FoundItem $foundItem): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, FoundItem $foundItem): bool
    {
        return $user->id === $foundItem->user_id || $user->isAdmin();
    }

    public function delete(User $user, FoundItem $foundItem): bool
    {
        return $user->id === $foundItem->user_id || $user->isAdmin();
    }
}