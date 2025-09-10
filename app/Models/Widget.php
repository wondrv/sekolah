<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'data',
        'order',
        'active',
    ];

    protected $casts = [
        'data' => 'array',
        'active' => 'boolean',
    ];
}
