<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'content_json',
        'use_page_builder',
        'meta_title',
        'meta_description',
        'og_image',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'use_page_builder' => 'boolean',
        'content_json' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title')) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    /**
     * Scope for pinned pages
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Get route key name for URL binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get rendered content
     */
    public function getRenderedContentAttribute()
    {
        if ($this->use_page_builder && $this->content_json) {
            return app(\App\Services\PageBuilderService::class)->renderPageContent($this);
        }

        return $this->content ?? '';
    }

    /**
     * Scope for pages using page builder
     */
    public function scopeUsingPageBuilder($query)
    {
        return $query->where('use_page_builder', true);
    }
}
