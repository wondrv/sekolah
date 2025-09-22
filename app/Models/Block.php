<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'type',
        'data',
        'order',
        'active',
        'style_settings',
        'css_class',
        'visible_desktop',
        'visible_tablet',
        'visible_mobile',
    ];

    protected $casts = [
        'data' => 'array',
        'active' => 'boolean',
        'style_settings' => 'array',
        'visible_desktop' => 'boolean',
        'visible_tablet' => 'boolean',
        'visible_mobile' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
