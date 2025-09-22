<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\Section;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Support\Theme;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates
     */
    public function index(): View
    {
        $templates = Template::with('sections.blocks')->get();

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create(): View
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'active' => 'boolean',
            'slug' => 'nullable|string|max:100',
        ]);

        Template::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug,
            'active' => $request->boolean('active'),
        ]);

        // Clear theme cache when template is created
        Theme::clearCache();

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template berhasil dibuat.');
    }

    /**
     * Display the specified template
     */
    public function show(Template $template): View
    {
        $template->load('sections.blocks');

        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(Template $template): View
    {
        $template->load('sections.blocks');
        $availableBlocks = [
            'hero' => 'Hero',
            'card_grid' => 'Card Grid',
            'rich_text' => 'Rich Text',
            'stats' => 'Statistics',
            'cta_banner' => 'Call to Action',
            'gallery_teaser' => 'Gallery Teaser',
            'events_teaser' => 'Events Teaser',
            'posts_teaser' => 'Posts Teaser',
            'announcements_teaser' => 'Announcements Teaser',
        ];

        return view('admin.templates.edit', compact('template', 'availableBlocks'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, Template $template): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'active' => 'boolean',
            'slug' => 'nullable|string|max:100',
            'sections' => 'array',
            'sections.*.id' => 'nullable|integer|exists:sections,id',
            'sections.*.name' => 'required|string|max:255',
            'sections.*.order' => 'required|integer|min:1',
            'sections.*.is_active' => 'nullable|boolean',
        ]);

        $template->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'slug' => $validated['slug'] ?? null,
            'active' => $request->boolean('active'),
        ]);

        // Optional: Upsert sections provided by the form
        $sections = $validated['sections'] ?? [];
        if (!empty($sections)) {
            // Fetch existing section IDs for this template
            $existing = $template->sections()->pluck('id')->all();

            foreach ($sections as $payload) {
                $id = $payload['id'] ?? null;
                $data = [
                    'name' => $payload['name'],
                    'order' => (int) $payload['order'],
                    'active' => isset($payload['is_active']) ? (bool)$payload['is_active'] : true,
                ];

                if ($id) {
                    // Update only if the section belongs to this template
                    $section = \App\Models\Section::where('id', $id)
                        ->where('template_id', $template->id)
                        ->first();
                    if ($section) {
                        $section->update($data);
                    }
                } else {
                    // Create new section with a unique key
                    $base = \Illuminate\Support\Str::slug($payload['name']) ?: 'section';
                    $key = $base;
                    $suffix = 1;
                    while (\App\Models\Section::where('key', $key)->exists()) {
                        $key = $base.'-'.(++$suffix);
                    }

                    $template->sections()->create($data + ['key' => $key]);
                }
            }
        }

        // Clear theme cache when template is updated
        Theme::clearCache();

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template berhasil diupdate.');
    }

    /**
     * Remove the specified template
     */
    public function destroy(Template $template): RedirectResponse
    {
        $template->delete();

        // Clear theme cache when template is deleted
        Theme::clearCache();

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Export a template (sections + blocks) as JSON
     */
    public function export(Template $template): StreamedResponse
    {
        $template->load('sections.blocks');

        $payload = [
            'template' => [
                'name' => $template->name,
                'slug' => $template->slug,
                'description' => $template->description,
                'active' => (bool) $template->active,
            ],
            'sections' => $template->sections->map(function ($s) {
                return [
                    'key' => $s->key,
                    'name' => $s->name,
                    'order' => (int) $s->order,
                    'active' => (bool) ($s->is_active ?? $s->active),
                    'blocks' => $s->blocks->map(function ($b) {
                        return [
                            'type' => $b->type,
                            'order' => (int) $b->order,
                            'data' => $b->data ?? [],
                            'active' => (bool) ($b->is_active ?? $b->active ?? true),
                        ];
                    })->values()->all(),
                ];
            })->values()->all(),
        ];

        $filename = 'template-'.$template->slug.'-'.date('Ymd_His').'.json';

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Import a template JSON as a NEW template
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimetypes:application/json,text/plain',
        ]);

        $data = json_decode(file_get_contents($request->file('file')->getRealPath()), true);
        if (!is_array($data)) {
            return back()->with('error', 'File JSON tidak valid.');
        }

        $tpl = $data['template'] ?? [];
        $sections = $data['sections'] ?? [];
        if (empty($tpl) || !is_array($sections)) {
            return back()->with('error', 'Struktur JSON tidak sesuai.');
        }

        // Create template with unique slug if needed
        $baseSlug = Str::slug($tpl['slug'] ?? $tpl['name'] ?? 'template');
        $slug = $baseSlug;
        $i = 1;
        while (\App\Models\Template::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.(++$i);
        }

        $template = Template::create([
            'name' => $tpl['name'] ?? 'Imported Template',
            'description' => $tpl['description'] ?? null,
            'slug' => $slug,
            'active' => (bool) ($tpl['active'] ?? true),
        ]);

        $this->hydrateSectionsAndBlocks($template, $sections);

        Theme::clearCache();

        return redirect()->route('admin.templates.edit', $template)->with('success', 'Template berhasil diimpor.');
    }

    /**
     * Import JSON sections/blocks into an existing template (merge)
     */
    public function importInto(Request $request, Template $template): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimetypes:application/json,text/plain',
        ]);

        $data = json_decode(file_get_contents($request->file('file')->getRealPath()), true);
        if (!is_array($data)) {
            return back()->with('error', 'File JSON tidak valid.');
        }

        $sections = $data['sections'] ?? null;
        if (!is_array($sections)) {
            return back()->with('error', 'Struktur JSON tidak sesuai: sections tidak ditemukan.');
        }

        $this->hydrateSectionsAndBlocks($template, $sections, merge: true);
        Theme::clearCache();

        return redirect()->route('admin.templates.edit', $template)->with('success', 'Template berhasil di‑merge dari JSON.');
    }

    /**
     * Helper to create/merge sections & blocks from JSON array
     */
    protected function hydrateSectionsAndBlocks(Template $template, array $sections, bool $merge = false): void
    {
        foreach ($sections as $s) {
            $name = $s['name'] ?? 'Section';
            $order = (int) ($s['order'] ?? 1);
            $active = isset($s['active']) ? (bool) $s['active'] : true;

            // Generate unique key per template
            $baseKey = Str::slug($s['key'] ?? $name) ?: 'section';
            $key = $baseKey;
            $suffix = 1;

            if ($merge) {
                // Try to update existing section with same key; if exists, ensure unique by suffixing
                $existing = Section::where('template_id', $template->id)->where('key', $key)->first();
                while ($existing) {
                    $key = $baseKey.'-'.(++$suffix);
                    $existing = Section::where('template_id', $template->id)->where('key', $key)->first();
                }
            } else {
                while (Section::where('key', $key)->exists()) {
                    $key = $baseKey.'-'.(++$suffix);
                }
            }

            $section = $template->sections()->create([
                'key' => $key,
                'name' => $name,
                'order' => $order,
                'active' => $active,
            ]);

            foreach (($s['blocks'] ?? []) as $b) {
                $section->blocks()->create([
                    'type' => $b['type'] ?? 'rich_text',
                    'order' => (int) ($b['order'] ?? 1),
                    'data' => isset($b['data']) && is_array($b['data']) ? $b['data'] : [],
                    'active' => isset($b['active']) ? (bool) $b['active'] : true,
                ]);
            }
        }
    }

    /**
     * Add section to template
     */
    public function addSection(Request $request, Template $template): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
        ]);

        $template->sections()->create([
            'name' => $request->name,
            'order' => $request->order,
        ]);

        // Clear theme cache when section is added
        Theme::clearCache();

        return redirect()->route('admin.templates.edit', $template)
                        ->with('success', 'Section berhasil ditambahkan.');
    }

    /**
     * Add block to section
     */
    public function addBlock(Request $request, Template $template, Section $section): RedirectResponse
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'data' => 'nullable|json',
            'order' => 'required|integer|min:1',
        ]);

        $section->blocks()->create([
            'type' => $request->type,
            'data' => $request->data ? json_decode($request->data, true) : [],
            'order' => $request->order,
        ]);

        // Clear theme cache when block is added
        Theme::clearCache();

        return redirect()->route('admin.templates.edit', $template)
                        ->with('success', 'Block berhasil ditambahkan.');
    }

    /**
     * Bootstrap default Homepage template with sections & blocks (admin-driven alternative to seeder)
     */
    public function bootstrapHomepage(): RedirectResponse
    {
        // Create or fetch homepage template
        $template = Template::firstOrCreate(
            ['slug' => 'homepage'],
            ['name' => 'Default School Homepage', 'description' => 'Homepage template', 'active' => true]
        );

        // Helper to upsert section by key
        $section = function (string $key, string $name, int $order) use ($template) {
            return Section::updateOrCreate([
                'template_id' => $template->id,
                'key' => $key,
            ], [
                'name' => $name,
                'order' => $order,
                'active' => true,
            ]);
        };

        // Hero
        $hero = $section('hero', 'Hero Section', 1);
        Block::updateOrCreate([
            'section_id' => $hero->id,
            'type' => 'hero',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Selamat Datang di Sekolah Kami',
                'subtitle' => 'Membangun Generasi Cerdas, Kreatif, dan Berkarakter',
                'background_color' => 'bg-gradient-to-r from-blue-600 to-blue-800',
                'text_align' => 'text-center',
                'buttons' => [
                    ['text' => 'Tentang Kita', 'url' => '/tentang-kami', 'style' => 'primary'],
                    ['text' => 'Hubungi Kami', 'url' => '/kontak', 'style' => 'secondary'],
                ],
            ],
            'active' => true,
        ]);

        // Stats
        $stats = $section('statistics', 'Statistics', 2);
        Block::updateOrCreate([
            'section_id' => $stats->id,
            'type' => 'stats',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Prestasi Kami',
                'background_color' => 'bg-blue-900',
                'stats' => [
                    ['number' => '1200+', 'label' => 'Siswa Aktif'],
                    ['number' => '85+', 'label' => 'Tenaga Pendidik'],
                    ['number' => '50+', 'label' => 'Prestasi'],
                    ['number' => '98%', 'label' => 'Kelulusan'],
                ],
            ],
            'active' => true,
        ]);

        // Programs
        $programs = $section('programs', 'Programs', 3);
        Block::updateOrCreate([
            'section_id' => $programs->id,
            'type' => 'card_grid',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Program Unggulan',
                'subtitle' => 'Program-program unggulan...',
                'background_color' => 'bg-gray-50',
                'columns' => 3,
                'cards' => [
                    ['title' => 'Program IPA', 'description' => '...', 'image' => '/images/program-ipa.jpg', 'link' => ['text' => 'Selengkapnya', 'url' => '/tentang-kami/program-ipa']],
                    ['title' => 'Program IPS', 'description' => '...', 'image' => '/images/program-ips.jpg', 'link' => ['text' => 'Selengkapnya', 'url' => '/tentang-kami/program-ips']],
                    ['title' => 'Program Bahasa', 'description' => '...', 'image' => '/images/program-bahasa.jpg', 'link' => ['text' => 'Selengkapnya', 'url' => '/tentang-kami/program-bahasa']],
                ],
            ],
            'active' => true,
        ]);

        // Announcements teaser (before news & events)
        $ann = $section('announcements', 'Announcements', 4);
        Block::updateOrCreate([
            'section_id' => $ann->id,
            'type' => 'announcements_teaser',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Pengumuman',
                'background_color' => 'bg-white',
                'limit' => 3,
                'show_more_link' => true,
                'category' => \App\Models\Setting::get('announcements_category_slug', 'pengumuman'),
            ],
            'active' => true,
        ]);

        // Events teaser
        $events = $section('events', 'Upcoming Events', 6);
        Block::updateOrCreate([
            'section_id' => $events->id,
            'type' => 'events_teaser',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Agenda Mendatang',
                'background_color' => 'bg-white',
                'limit' => 3,
                'show_more_link' => true,
            ],
            'active' => true,
        ]);

    // Gallery teaser
    $gallery = $section('gallery', 'Gallery', 7);
        Block::updateOrCreate([
            'section_id' => $gallery->id,
            'type' => 'gallery_teaser',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Galeri Kegiatan',
                'background_color' => 'bg-white',
                'limit' => 6,
                'show_more_link' => true,
            ],
            'active' => true,
        ]);

    // CTA
    $cta = $section('cta', 'Call to Action', 8);
        Block::updateOrCreate([
            'section_id' => $cta->id,
            'type' => 'cta_banner',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Bergabunglah dengan Kami',
                'subtitle' => 'Daftarkan diri Anda dan menjadi bagian dari keluarga besar kami',
                'background_color' => 'bg-gradient-to-r from-blue-600 to-blue-800',
                'button' => ['text' => 'Kontak Kami', 'url' => '/kontak'],
            ],
            'active' => true,
        ]);

    // Optional: News teaser (between announcements and events)
    $news = $section('news', 'News', 5);
        Block::updateOrCreate([
            'section_id' => $news->id,
            'type' => 'posts_teaser',
            'order' => 1,
        ], [
            'data' => [
                'title' => 'Berita Terbaru',
                'background_color' => 'bg-white',
                'limit' => 3,
                'show_more_link' => true,
            ],
            'active' => true,
        ]);

    // Finalize ordering integrity
    $ann->order = 4; $ann->save();
    $news->order = 5; $news->save();
    $events->order = 6; $events->save();
    $gallery->order = 7; $gallery->save();
    $cta->order = 8; $cta->save();

        Theme::clearCache();

        return redirect()->route('admin.templates.index')->with('success', 'Homepage template generated/updated.');
    }

    /** Delete a section from a template */
    public function deleteSection(Template $template, Section $section): RedirectResponse
    {
        // Ensure the section belongs to the template
        if ($section->template_id !== $template->id) {
            return redirect()->route('admin.templates.edit', $template)
                ->with('error', 'Section tidak ditemukan pada template ini.');
        }

        // Deleting section will also delete its blocks if cascades are set; otherwise delete manually
        $section->delete();

        Theme::clearCache();

        return redirect()->route('admin.templates.edit', $template)
            ->with('success', 'Section berhasil dihapus.');
    }

    /** Delete a block from a template (via its section) */
    public function deleteBlock(Template $template, Block $block): RedirectResponse
    {
        // Verify block belongs to a section under this template
        $block->load('section');
        if (!$block->section || $block->section->template_id !== $template->id) {
            return redirect()->route('admin.templates.edit', $template)
                ->with('error', 'Block tidak ditemukan pada template ini.');
        }

        $block->delete();

        Theme::clearCache();

        return redirect()->route('admin.templates.edit', $template)
            ->with('success', 'Block berhasil dihapus.');
    }
}
