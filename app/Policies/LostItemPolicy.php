<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LostItem;

class LostItemPolicy
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
    public function view(User $user, LostItem $lostItem): bool
    {
        // Admin can view all items
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can view their own items
        if ($user->id === $lostItem->user_id) {
            return true;
        }
        
        // Users can view approved items
        if ($lostItem->status === 'approved') {
            return true;
        }
        
        // Users cannot view pending items from other users
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LostItem $lostItem): bool
    {
        // Admin can update any item
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can only update their own pending or approved items
        return $user->id === $lostItem->user_id && 
               in_array($lostItem->status, ['pending', 'approved']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LostItem $lostItem): bool
    {
        // Admin can delete any item
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can only delete their own pending or approved items
        return $user->id === $lostItem->user_id && 
               in_array($lostItem->status, ['pending', 'approved']);
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user): bool
    {
        // Only admins can approve items
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user): bool
    {
        // Only admins can reject items
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can bulk approve models.
     */
    public function bulkApprove(User $user): bool
    {
        // Only admins can bulk approve items
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LostItem $lostItem): bool
    {
        // Only admins can restore deleted items
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LostItem $lostItem): bool
    {
        // Only admins can force delete items
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view pending items.
     */
    public function viewPending(User $user): bool
    {
        // Only admins can view all pending items
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can mark item as found.
     */
    public function markAsFound(User $user, LostItem $lostItem): bool
    {
        // Admin can mark any item as found
        if ($user->isAdmin()) {
            return true;
        }
        
        // Owner can mark their own approved items as found
        return $user->id === $lostItem->user_id && $lostItem->status === 'approved';
    }

    /**
     * Determine whether the user can mark item as returned.
     */
    public function markAsReturned(User $user, LostItem $lostItem): bool
    {
        // Admin can mark any item as returned
        if ($user->isAdmin()) {
            return true;
        }
        
        // Owner can mark their own approved items as returned
        return $user->id === $lostItem->user_id && $lostItem->status === 'approved';
    }
}