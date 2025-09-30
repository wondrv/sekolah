<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TemplateAssignment
 *
 * @property int $id
 * @property string $route_pattern
 * @property string|null $page_slug
 * @property int $template_id
 * @property int $priority
 * @property bool $active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Template $template
 */
class TemplateAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_pattern',
        'page_slug',
        'template_id',
        'priority',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Find the best template for a given route or page
     */
    public static function findTemplateFor(string $routeName, ?string $pageSlug = null): ?Template
    {
        $query = static::where('active', true)
            ->with('template')
            ->orderByDesc('priority')
            ->orderBy('id');

        // First, try exact page slug match
        if ($pageSlug) {
            $assignment = $query->clone()->where('page_slug', $pageSlug)->first();
            if ($assignment) {
                return $assignment->template;
            }
        }

        // Then try route pattern matches
        $assignment = $query->where('route_pattern', $routeName)->first();
        if ($assignment) {
            return $assignment->template;
        }

        // Try wildcard patterns
        $assignment = $query->where('route_pattern', 'like', str_replace('.*', '%', $routeName))->first();
        if ($assignment) {
            return $assignment->template;
        }

        // Fallback to default template
        return Template::where('slug', 'default')->orWhere('active', true)->first();
    }
}
