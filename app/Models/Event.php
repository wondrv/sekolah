<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
        'location',
        'type',
        'is_featured',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Scope for featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }

    /**
     * Scope for past events
     */
    public function scopePast($query)
    {
        return $query->where('starts_at', '<', now());
    }

    /**
     * Scope for events by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming()
    {
        return $this->starts_at > now();
    }

    /**
     * Check if event is ongoing
     */
    public function isOngoing()
    {
        return $this->starts_at <= now() && (!$this->ends_at || $this->ends_at >= now());
    }

    /**
     * Check if event is past
     */
    public function isPast()
    {
        return $this->ends_at ? $this->ends_at < now() : $this->starts_at < now();
    }

    /**
     * Get duration in human readable format
     */
    public function getDurationAttribute()
    {
        if (!$this->ends_at) {
            return null;
        }

        return $this->starts_at->diffInHours($this->ends_at) . ' jam';
    }
}
