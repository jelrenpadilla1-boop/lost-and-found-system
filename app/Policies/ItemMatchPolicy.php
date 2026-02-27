<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ItemMatch;

class ItemMatchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ItemMatch $match): bool
    {
        // Admin can view all matches
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can view matches that involve their items (with null checks)
        return ($match->lostItem && $match->lostItem->user_id === $user->id) ||
               ($match->foundItem && $match->foundItem->user_id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only AI service should create matches programmatically
        // Users cannot manually create matches
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ItemMatch $match): bool
    {
        // Admin can update any match
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can only update matches involving their items (with null checks)
        return ($match->lostItem && $match->lostItem->user_id === $user->id) ||
               ($match->foundItem && $match->foundItem->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ItemMatch $match): bool
    {
        // Only admins can delete matches
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ItemMatch $match): bool
    {
        // Only admins can restore matches
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ItemMatch $match): bool
    {
        // Only admins can force delete matches
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can confirm the match.
     */
    public function confirm(User $user, ItemMatch $match): bool
    {
        // Admin can confirm any match
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can confirm matches involving their items (with null checks)
        return ($match->lostItem && $match->lostItem->user_id === $user->id) ||
               ($match->foundItem && $match->foundItem->user_id === $user->id);
    }

    /**
     * Determine whether the user can reject the match.
     */
    public function reject(User $user, ItemMatch $match): bool
    {
        // Reuse confirm logic since permissions are the same
        return $this->confirm($user, $match);
    }

    /**
     * Determine whether the user can bulk update matches.
     */
    public function bulkUpdate(User $user): bool
    {
        // Only admins can bulk update matches
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view pending matches.
     */
    public function viewPending(User $user): bool
    {
        // Only admins can view all pending matches
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view match statistics.
     */
    public function viewStats(User $user): bool
    {
        // Anyone can view stats, but they'll be filtered by permissions
        return true;
    }
}