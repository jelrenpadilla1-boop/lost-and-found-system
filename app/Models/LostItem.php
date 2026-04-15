<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'rejection_reason',
        'found_at',
        'returned_at'
    ];

    protected $casts = [
        'date_lost' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'found_at' => 'datetime',
        'returned_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
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
        return $this->hasMany(ItemMatch::class, 'lost_item_id');
    }

    public function foundItems()
    {
        return $this->belongsToMany(FoundItem::class, 'item_matches', 'lost_item_id', 'found_item_id')
                    ->withPivot('match_score', 'status', 'id')
                    ->withTimestamps();
    }

    // Scopes for easier querying
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeFound($query)
    {
        return $query->where('status', 'found');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['approved', 'found']);
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

    public function isActive(): bool
    {
        return in_array($this->status, ['approved', 'found']);
    }

    // Helper methods for photo management
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return Storage::disk('public')->url($this->photo);
        }
        return null;
    }

    public function hasPhoto(): bool
    {
        return !empty($this->photo);
    }

    public function deletePhoto()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            Storage::disk('public')->delete($this->photo);
            $this->update(['photo' => null]);
            return true;
        }
        return false;
    }

    // Get formatted date lost
    public function getFormattedDateLostAttribute()
    {
        return $this->date_lost ? $this->date_lost->format('F d, Y') : 'Unknown date';
    }

    // Get time ago for date lost
    public function getDateLostAgoAttribute()
    {
        return $this->date_lost ? $this->date_lost->diffForHumans() : 'Unknown';
    }

    // Get human-readable status with badge class
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'badge-warning', 'icon' => 'clock', 'text' => 'Pending'],
            'approved' => ['class' => 'badge-success', 'icon' => 'check-circle', 'text' => 'Active'],
            'rejected' => ['class' => 'badge-danger', 'icon' => 'times-circle', 'text' => 'Rejected'],
            'found' => ['class' => 'badge-info', 'icon' => 'search', 'text' => 'Found'],
            'returned' => ['class' => 'badge-primary', 'icon' => 'home', 'text' => 'Returned'],
        ];

        $status = $this->status;
        return (object)($badges[$status] ?? ['class' => 'badge-secondary', 'icon' => 'circle', 'text' => ucfirst($status)]);
    }

    // Check if user can edit this item
    public function canEdit($user)
    {
        if (!$user) return false;
        return $user->isAdmin() || $user->id === $this->user_id;
    }

    // Check if user can view this item
    public function canView($user)
    {
        if (!$user) return $this->status === 'approved';
        
        if ($user->isAdmin()) return true;
        
        if ($user->id === $this->user_id) return true;
        
        return $this->status === 'approved';
    }

    // Get location coordinates as array
    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float)$this->latitude,
                'lng' => (float)$this->longitude
            ];
        }
        return null;
    }

    // Check if location is set
    public function hasLocation(): bool
    {
        return !empty($this->latitude) && !empty($this->longitude);
    }

    // Get matches count
    public function getMatchesCountAttribute()
    {
        return $this->matches()->count();
    }

    // Get high confidence matches (score >= 70%)
    public function highConfidenceMatches()
    {
        return $this->matches()->where('match_score', '>=', 70)->get();
    }

    // Get pending matches
    public function pendingMatches()
    {
        return $this->matches()->where('status', 'pending')->get();
    }

    // Get confirmed matches
    public function confirmedMatches()
    {
        return $this->matches()->where('status', 'confirmed')->get();
    }

    // Boot method to handle model events
    protected static function boot()
    {
        parent::boot();

        // Delete photo when model is deleted
        static::deleting(function ($lostItem) {
            if ($lostItem->photo) {
                Storage::disk('public')->delete($lostItem->photo);
            }
        });
    }
}