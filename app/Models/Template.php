<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_template_id',
        'name',
        'slug',
        'description',
        'active',
        'type',
        'layout_settings',
        'is_global',
        'sort_order',
        'template_version',
        'metadata',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_global' => 'boolean',
        'layout_settings' => 'array',
        'metadata' => 'array',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function assignments()
    {
        return $this->hasMany(TemplateAssignment::class);
    }

    public function userTemplate()
    {
        return $this->belongsTo(UserTemplate::class);
    }

    /**
     * Scope for global templates (header/footer)
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /**
     * Scope for page templates
     */
    public function scopePages($query)
    {
        return $query->where('type', 'page')->where('is_global', false);
    }

    /**
     * Get all blocks from all sections
     */
    public function getAllBlocks()
    {
        return $this->sections->flatMap->blocks;
    }
}
