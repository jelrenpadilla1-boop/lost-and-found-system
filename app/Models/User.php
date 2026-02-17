<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;                 // ← ADD THIS
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract; // ← ADD THIS

class User extends Authenticatable implements CanResetPasswordContract // ← ADD implements
{
    use HasFactory, Notifiable, CanResetPassword; // ← ADD CanResetPassword trait

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'latitude',
        'longitude',
        'profile_photo',
        'phone',
        'location'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function lostItems()
    {
        return $this->hasMany(LostItem::class);
    }

    public function foundItems()
    {
        return $this->hasMany(FoundItem::class);
    }

    public function matches()
    {
        $lostItemIds  = $this->lostItems()->pluck('id');
        $foundItemIds = $this->foundItems()->pluck('id');

        return ItemMatch::whereIn('lost_item_id', $lostItemIds)
            ->orWhereIn('found_item_id', $foundItemIds);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return null;
    }
}