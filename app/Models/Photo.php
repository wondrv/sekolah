<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $fillable = [
        'gallery_id',
        'path',
        'alt',
        'sort_order',
    ];

    /**
     * Gallery that this photo belongs to
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Get the full URL for the photo
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    /**
     * Delete the photo file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($photo) {
            if (Storage::exists($photo->path)) {
                Storage::delete($photo->path);
            }
        });
    }
}
