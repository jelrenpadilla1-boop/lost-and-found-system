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
        'found_location', // Add this
        'status'
    ];

    protected $casts = [
        'date_found' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
}