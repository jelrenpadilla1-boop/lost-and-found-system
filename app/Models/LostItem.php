<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'category',
        'photo',
        'date_lost',
        'latitude',
        'longitude',
        'lost_location',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason'
    ];

    protected $casts = [
        'date_lost' => 'datetime',
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

    public function foundItems()
    {
        return $this->belongsToMany(FoundItem::class, 'item_matches')
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

    public function isFound(): bool
    {
        return $this->status === 'found';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }
}