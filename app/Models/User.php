<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPasswordContract
{
    // Removed Notifiable trait to avoid conflict with custom notifications() method
    use HasFactory, CanResetPassword, Notifiable {
        Notifiable::notifications as laravelNotifications;
    }

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

    // ── Relationships ──────────────────────────────────────────────

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

    // ── Custom Notifications (uses your own notifications table) ───

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    // ── Helpers ────────────────────────────────────────────────────

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