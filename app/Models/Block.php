<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Block
 *
 * @property int $id
 * @property int $section_id
 * @property string $type
 * @property array|null $data
 * @property-read array|null $content  Content portion inside data (virtual helper when accessed)
 * @property-read array|null $settings Settings portion inside data (virtual helper when accessed)
 * @property int $order
 * @property bool $active
 * @property array|null $style_settings
 * @property string|null $css_class
 * @property bool $visible_desktop
 * @property bool $visible_tablet
 * @property bool $visible_mobile
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Section $section
 */
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

    /**
     * Convenience accessor to mirror $block->content if code references it directly.
     */
    public function getContentAttribute()
    {
        if (is_array($this->data) && array_key_exists('content', $this->data)) {
            return $this->data['content'];
        }
        // Some legacy structures may store main value directly
        return $this->data;
    }

    /**
     * Convenience accessor for settings nested in data (if present)
     */
    public function getSettingsAttribute()
    {
        if (is_array($this->data) && array_key_exists('settings', $this->data)) {
            return $this->data['settings'];
        }
        return null;
    }
}
