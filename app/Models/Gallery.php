<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope for active galleries
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Photos in this gallery
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
