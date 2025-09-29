<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'category',
        'description',
    ];

    // Remove array casting since we're storing individual key-value pairs
    // protected $casts = [
    //     'value' => 'array',
    // ];

    /**
     * Get a theme setting by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a theme setting
     */
    public static function set(string $key, $value, string $category = 'general', ?string $description = null): self
    {
        // Ensure value is never null - use empty array as default
        if ($value === null) {
            $value = [];
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'category' => $category,
                'description' => $description,
            ]
        );
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory(string $category): array
    {
        return static::where('category', $category)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get compiled CSS variables from theme settings
     */
    public static function getCssVariables(): string
    {
        $colors = static::getByCategory('colors');
        $typography = static::getByCategory('typography');
        $spacing = static::getByCategory('spacing');

        $css = ":root {\n";

        // Colors - use key directly since we're storing individual settings
        foreach ($colors as $key => $value) {
            if (!empty($value)) {
                $css .= "  --color-{$key}: {$value};\n";
            }
        }

        // Typography
        foreach ($typography as $key => $value) {
            if (!empty($value)) {
                $css .= "  --font-{$key}: {$value};\n";
            }
        }

        // Spacing
        foreach ($spacing as $key => $value) {
            if (!empty($value)) {
                $css .= "  --spacing-{$key}: {$value};\n";
            }
        }

        $css .= "}\n";

        return $css;
    }
}
