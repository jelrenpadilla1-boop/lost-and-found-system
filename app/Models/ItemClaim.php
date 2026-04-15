<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'found_item_id',
        'user_id',
        'claim_reason',
        'proof_photo',
        'status',
        'admin_notes',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function foundItem()
    {
        return $this->belongsTo(FoundItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}