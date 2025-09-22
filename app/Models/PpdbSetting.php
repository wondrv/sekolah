<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'json' => json_decode($setting->value, true),
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            default => $setting->value
        };
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'text', ?string $description = null): self
    {
        $processedValue = match($type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value
        };

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $processedValue,
                'type' => $type,
                'description' => $description,
                'is_active' => true,
            ]
        );
    }

    /**
     * Get all active settings as key-value pairs
     */
    public static function getAllSettings(): array
    {
        return static::where('is_active', true)
            ->get()
            ->mapWithKeys(function ($setting) {
                $value = match($setting->type) {
                    'json' => json_decode($setting->value, true),
                    'boolean' => (bool) $setting->value,
                    'integer' => (int) $setting->value,
                    'float' => (float) $setting->value,
                    default => $setting->value
                };
                
                return [$setting->key => $value];
            })
            ->toArray();
    }

    /**
     * Get brochure files
     */
    public static function getBrochures(): array
    {
        return static::get('brochures', []);
    }

    /**
     * Set brochure files
     */
    public static function setBrochures(array $brochures): self
    {
        return static::set('brochures', $brochures, 'json', 'PPDB Brochure files');
    }
}
