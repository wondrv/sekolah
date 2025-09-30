<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Class UserTemplate
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $gallery_template_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $preview_image
 * @property array|null $template_data
 * @property array|null $draft_template_data
 * @property string $source  // gallery|custom|imported
 * @property bool $is_active
 * @property array|null $customizations
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read TemplateGallery|null $galleryTemplate
 * @property-read \Illuminate\Database\Eloquent\Collection|Template[] $templates
 * @property-read \Illuminate\Database\Eloquent\Collection|TemplateExport[] $exports
 *
 * @method static static byUser($userId = null)
 * @method static static active()
 * @method static static custom()
 * @method static static fromGallery()
 * @method static static imported()
 */
class UserTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gallery_template_id',
        'name',
        'slug',
        'description',
        'preview_image',
        'template_data',
        'draft_template_data',
        'source',
        'is_active',
        'customizations',
    ];

    protected $casts = [
        'template_data' => 'array',
        'draft_template_data' => 'array',
        'is_active' => 'boolean',
        'customizations' => 'array',
    ];

    public function revisions()
    {
        return $this->hasMany(TemplateRevision::class)->latest();
    }

    protected function createRevision(string $type, ?string $note = null): void
    {
        try {
            TemplateRevision::create([
                'user_template_id' => $this->id,
                'type' => $type,
                'snapshot' => [
                    'template_data' => $this->template_data,
                    'draft_template_data' => $this->draft_template_data,
                    'name' => $this->name,
                    'description' => $this->description,
                    'source' => $this->source,
                    'is_active' => $this->is_active,
                ],
                'note' => $note,
            ]);
        } catch (\Exception $e) {
            // Fail silently – we don't want to block activation/publish if revision logging fails
        }
    }

    /**
     * Check if draft exists
     */
    public function hasDraft(): bool
    {
        return !empty($this->draft_template_data) && is_array($this->draft_template_data);
    }

    /**
     * Start a draft based on current template_data if none exists
     */
    public function ensureDraftInitialized(): void
    {
        if (!$this->hasDraft()) {
            $this->update(['draft_template_data' => $this->template_data]);
        }
    }

    /**
     * Publish draft (replace template_data and clear draft)
     */
    public function publishDraft(): void
    {
        if ($this->hasDraft()) {
            // Snapshot before publish
            $this->createRevision('publish_draft', 'Before publish draft');
            $this->update([
                'template_data' => $this->draft_template_data,
                'draft_template_data' => null,
            ]);

            if ($this->is_active) {
                $this->applyToSite();
            }
            $this->createRevision('after_publish_draft', 'After publish draft');
        }
    }

    /**
     * Discard draft changes
     */
    public function discardDraft(): void
    {
        $this->update(['draft_template_data' => null]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function galleryTemplate()
    {
        return $this->belongsTo(TemplateGallery::class);
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function exports()
    {
        return $this->hasMany(TemplateExport::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByUser($query, $userId = null)
    {
        if (!$userId) {
            $userId = \Illuminate\Support\Facades\Auth::id();
        }

        if (!$userId) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->where('user_id', $userId);
    }

    public function scopeCustom($query)
    {
        return $query->where('source', 'custom');
    }

    public function scopeFromGallery($query)
    {
        return $query->where('source', 'gallery');
    }

    public function scopeImported($query)
    {
        return $query->where('source', 'imported');
    }

    public function getPreviewImageUrlAttribute()
    {
        if (!$this->preview_image) {
            return asset('images/template-placeholder.jpg');
        }

        if (str_starts_with($this->preview_image, 'http')) {
            return $this->preview_image;
        }

        return Storage::url($this->preview_image);
    }

    public function activate()
    {
        // Log revision BEFORE activation (state prior to activation)
        $this->createRevision('activate', 'Before activation');
        // Deactivate all other templates for this user
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        // Activate this template
        $this->update(['is_active' => true]);

        // Apply template to site
        $this->applyToSite();
        $this->createRevision('after_activate', 'After activation');
    }

    public function restoreRevision(TemplateRevision $revision, bool $keepDraft = true): void
    {
        // Snapshot current before restore
        $this->createRevision('pre_restore', 'Before restoring revision ID '.$revision->getKey());

        $snapshot = $revision->snapshot;
        $update = [];
        if (isset($snapshot['template_data'])) {
            $update['template_data'] = $snapshot['template_data'];
        }
        if ($keepDraft && isset($snapshot['draft_template_data'])) {
            $update['draft_template_data'] = $snapshot['draft_template_data'];
        } else {
            $update['draft_template_data'] = null; // clear if not keeping
        }
        if (isset($snapshot['name'])) $update['name'] = $snapshot['name'];
        if (isset($snapshot['description'])) $update['description'] = $snapshot['description'];

        $this->update($update);

        if ($this->is_active) {
            $this->applyToSite();
        }

        $this->createRevision('post_restore', 'After restoring revision ID '.$revision->getKey());
    }

    public function applyToSite()
    {
        // Delete existing templates for this user
        Template::whereHas('userTemplate', function ($query) {
            $query->where('user_id', $this->user_id);
        })->delete();

        // Create templates from template data
        $this->createTemplatesFromData();
    }

    protected function createTemplatesFromData()
    {
        if (!$this->template_data || !isset($this->template_data['templates'])) {
            return;
        }

        foreach ($this->template_data['templates'] as $templateData) {
            $template = Template::create([
                'user_template_id' => $this->id,
                'name' => $templateData['name'],
                'slug' => $templateData['slug'],
                'description' => $templateData['description'] ?? null,
                'active' => $templateData['active'] ?? true,
                'type' => $templateData['type'] ?? 'page',
                'layout_settings' => $templateData['layout_settings'] ?? null,
                'is_global' => $templateData['is_global'] ?? false,
                'sort_order' => $templateData['sort_order'] ?? 0,
                'template_version' => $this->galleryTemplate?->version ?? '1.0.0',
                'metadata' => $templateData['metadata'] ?? null,
            ]);

            // Create sections and blocks
            if (isset($templateData['sections'])) {
                $this->createSectionsFromData($template, $templateData['sections']);
            }

            // Create template assignments
            if (isset($templateData['assignments'])) {
                $this->createAssignmentsFromData($template, $templateData['assignments']);
            } else {
                // Auto-create homepage assignment if none specified
                $this->createDefaultAssignments($template, $templateData);
            }
        }
    }

    protected function createSectionsFromData($template, $sectionsData)
    {
        foreach ($sectionsData as $sectionData) {
            // Auto-detect footer section by name if not explicitly flagged
            $settings = $sectionData['settings'] ?? [];
            if(!isset($settings['is_footer'])) {
                $nameCheck = strtolower($sectionData['name'] ?? '');
                if(str_contains($nameCheck, 'footer')) {
                    $settings['is_footer'] = true;
                }
            }
            $section = Section::create([
                'template_id' => $template->id,
                'name' => $sectionData['name'],
                'order' => $sectionData['order'],
                'settings' => $settings ?: null,
            ]);

            if (isset($sectionData['blocks'])) {
                $this->createBlocksFromData($section, $sectionData['blocks']);
            }
        }
    }

    protected function createBlocksFromData($section, $blocksData)
    {
        foreach ($blocksData as $blockData) {
            // Normalize block type (kebab-case -> snake_case) for renderer compatibility
            $rawType = $blockData['type'] ?? 'unknown';
            $normalizedType = str_replace('-', '_', $rawType);

            // If name missing, generate a readable one
            $name = $blockData['name'] ?? ucfirst(str_replace(['-', '_'], ' ', $rawType));

            // Merge content into data if only stored there
            $data = $blockData['data'] ?? [];
            if (empty($blockData['content']) && is_array($data) && isset($data['content'])) {
                $content = $data['content'];
            } else {
                $content = $blockData['content'] ?? null;
            }
            // Ensure accessor getContentAttribute() can find content inside data['content']
            if ($content !== null) {
                if (!is_array($data)) { $data = []; }
                if (!isset($data['content']) || $data['content'] !== $content) {
                    $data['content'] = $content;
                }
            }

            try {
                Block::create([
                    'section_id' => $section->id,
                    'type' => $normalizedType,
                    'name' => $name,
                    'order' => $blockData['order'] ?? 0,
                    'content' => $content,
                    'settings' => $blockData['settings'] ?? null,
                    'data' => $data ?: null,
                    'style_settings' => $blockData['style_settings'] ?? null,
                    'css_class' => $blockData['css_class'] ?? null,
                    'visible_desktop' => $blockData['visible_desktop'] ?? true,
                    'visible_tablet' => $blockData['visible_tablet'] ?? true,
                    'visible_mobile' => $blockData['visible_mobile'] ?? true,
                    'active' => $blockData['active'] ?? true,
                ]);
            } catch (\Exception $e) {
                if (config('app.debug')) {
                    Log::error('Block create failed', [
                        'error' => $e->getMessage(),
                        'section_id' => $section->id,
                        'raw_block' => $blockData,
                    ]);
                }
            }
        }
    }

    protected function createAssignmentsFromData($template, $assignmentsData)
    {
        foreach ($assignmentsData as $assignmentData) {
            TemplateAssignment::create([
                'route_pattern' => $assignmentData['route_pattern'],
                'page_slug' => $assignmentData['page_slug'] ?? null,
                'template_id' => $template->id,
                'priority' => $assignmentData['priority'] ?? 0,
                'active' => $assignmentData['active'] ?? true,
            ]);
        }
    }

    protected function createDefaultAssignments($template, $templateData)
    {
        // Create default homepage assignment for activated templates
        if ($this->is_active) {
            TemplateAssignment::updateOrCreate([
                'route_pattern' => 'home',
                'template_id' => $template->id,
            ], [
                'page_slug' => null,
                'priority' => 100, // High priority for active templates
                'active' => true,
            ]);

            // Also assign to root path
            TemplateAssignment::updateOrCreate([
                'route_pattern' => '/',
                'template_id' => $template->id,
            ], [
                'page_slug' => null,
                'priority' => 100,
                'active' => true,
            ]);
        }
    }

    public function duplicate($newName = null)
    {
        $duplicate = $this->replicate();
        $duplicate->name = $newName ?? $this->name . ' Copy';
        $duplicate->slug = $this->slug . '-copy-' . time();
        $duplicate->is_active = false;
        $duplicate->save();

        return $duplicate;
    }

    public function exportToArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->galleryTemplate?->version ?? '1.0.0',
            'source' => $this->source,
            'template_data' => $this->template_data,
            'customizations' => $this->customizations,
            'exported_at' => now()->toISOString(),
        ];
    }
}
