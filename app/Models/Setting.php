<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    // Remove array casting since we store string values
    // protected $casts = [
    //     'value' => 'array',
    // ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        // Ensure value is never null - convert to empty string
        $value = $value ?? '';

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
