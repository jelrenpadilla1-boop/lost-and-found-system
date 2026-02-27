<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'category',
        'photo',
        'date_found',
        'latitude',
        'longitude',
        'found_location',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason'
    ];

    protected $casts = [
        'date_found' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function matches()
    {
        return $this->hasMany(ItemMatch::class);
    }

    public function lostItems()
    {
        return $this->belongsToMany(LostItem::class, 'item_matches')
                    ->withPivot('match_score', 'status')
                    ->withTimestamps();
    }

    // Helper methods for status checking
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isClaimed(): bool
    {
        return $this->status === 'claimed';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function isDisposed(): bool
    {
        return $this->status === 'disposed';
    }
}