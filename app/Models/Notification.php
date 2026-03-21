<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'url',
        'data',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Get the icon data for the notification
     */
    public function getIconDataAttribute()
    {
        $icons = [
            'match' => ['icon' => 'exchange-alt', 'color' => '#00f0c8'],
            'message' => ['icon' => 'comment', 'color' => '#7efff5'],
            'found' => ['icon' => 'check-circle', 'color' => '#22d37a'],
            'lost' => ['icon' => 'search', 'color' => '#f0b400'],
            'info' => ['icon' => 'info-circle', 'color' => '#5a6a8a'],
        ];

        // If icon is stored in data, use that
        if (isset($this->data['icon'])) {
            return $this->data['icon'];
        }

        // Otherwise use type-based icon
        return $icons[$this->type] ?? $icons['info'];
    }

    /**
     * Get the icon attribute (for backward compatibility)
     */
    public function getIconAttribute()
    {
        return $this->icon_data;
    }
}