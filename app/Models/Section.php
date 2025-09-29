<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'name',
        'key',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class)->where('active', true)->orderBy('order');
    }

    /**
     * Auto-generate unique key if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->key)) {
                $base = \Illuminate\Support\Str::slug($model->name ?? 'section') ?: 'section';
                $candidate = $base;
                $i = 1;
                while (self::where('key', $candidate)->exists()) {
                    $candidate = $base.'-'.(++$i);
                }
                $model->key = $candidate;
            }
        });
    }
}
