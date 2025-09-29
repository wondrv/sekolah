<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_template_id',
        'type',
        'snapshot',
        'note',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function userTemplate()
    {
        return $this->belongsTo(UserTemplate::class);
    }
}
