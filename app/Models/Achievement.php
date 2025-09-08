<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Achievement extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'level',
        'achievement_date',
        'achiever_name',
        'image',
        'details',
        'is_featured'
    ];

    protected $casts = [
        'achievement_date' => 'date',
        'is_featured' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($achievement) {
            if (empty($achievement->slug)) {
                $achievement->slug = Str::slug($achievement->title);
            }
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('achievement_date', 'desc')->limit($limit);
    }
}
