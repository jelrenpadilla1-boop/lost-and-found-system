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
        'status'
    ];

    protected $casts = [
        'date_lost' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
}