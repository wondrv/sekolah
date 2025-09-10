<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'label',
        'url',
        'sort_order',
        'is_external',
        // New CMS fields
        'name',
        'slug',
        'location',
        'active',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function items()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    public function allItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // For compatibility with CMS
    public function getTitleAttribute()
    {
        return $this->label;
    }

    public function getOrderAttribute()
    {
        return $this->sort_order;
    }
}
