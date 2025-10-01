<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Models\UserTemplate;
use App\Models\Template;
use App\Models\Section;
use App\Models\Block;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TemplateBuilderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $templates = UserTemplate::byUser()
            ->with(['galleryTemplate'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = TemplateCategory::active()
            ->ordered()
            ->get();

        return view('admin.templates.builder.index', compact('templates', 'categories'));
    }

    public function create()
    {
        $categories = TemplateCategory::active()
            ->ordered()
            ->get();

        $blockTypes = $this->getAvailableBlockTypes();

        return view('admin.templates.builder.create', compact('categories', 'blockTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:template_categories,id',
        ]);

        try {
            $userTemplate = UserTemplate::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . time(),
                'description' => $request->description,
                'template_data' => $this->getDefaultTemplateStructure(),
                'source' => 'custom',
            ]);

            return redirect()->route('admin.templates.my-templates.index')
                ->with('success', 'Template berhasil dibuat dan disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }

    public function edit(UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        try {
            $userTemplate->load(['templates.sections.blocks']);

            $blockTypes = $this->getAvailableBlockTypes();
            $categories = TemplateCategory::active()->ordered()->get();

            // Get template structure for builder
            $templateStructure = $this->buildTemplateStructure($userTemplate);

            return view('admin.templates.builder.edit', compact(
                'userTemplate',
                'blockTypes',
                'categories',
                'templateStructure'
            ));
        } catch (\Exception $e) {
            return redirect()->route('admin.templates.my-templates')
                ->with('error', 'Error loading template builder: ' . $e->getMessage());
        }
    }

    public function update(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_data' => 'required|array',
            'save_as_draft' => 'sometimes|boolean',
        ]);

        try {
            $saveAsDraft = $request->boolean('save_as_draft');
            $forceApply = $request->boolean('force_apply');

            // Normalize block structure (ensure each block has content key so DB renderer sees it)
            $normalized = $request->template_data;
            if (isset($normalized['templates']) && is_array($normalized['templates'])) {
                foreach ($normalized['templates'] as $ti => $tpl) {
                    if (isset($tpl['sections']) && is_array($tpl['sections'])) {
                        foreach ($tpl['sections'] as $si => $section) {
                            if (isset($section['blocks']) && is_array($section['blocks'])) {
                                foreach ($section['blocks'] as $bi => $block) {
                                    if (is_array($block)) {
                                        // Provide default human readable name if absent to avoid DB insert issues
                                        if (empty($block['name'])) {
                                            $normalized['templates'][$ti]['sections'][$si]['blocks'][$bi]['name'] = ucfirst(str_replace(['-', '_'], ' ', $block['type'] ?? 'Block'));
                                        }
                                        // If data exists but content missing, move/duplicate
                                        if (!isset($block['content']) && isset($block['data'])) {
                                            // If data already has 'content' nested, use that, else wrap whole data
                                            if (isset($block['data']['content'])) {
                                                $normalized['templates'][$ti]['sections'][$si]['blocks'][$bi]['content'] = $block['data']['content'];
                                            } else {
                                                $normalized['templates'][$ti]['sections'][$si]['blocks'][$bi]['content'] = $block['data'];
                                            }
                                        }
                                        // Guarantee order integer
                                        if (!isset($block['order'])) {
                                            $normalized['templates'][$ti]['sections'][$si]['blocks'][$bi]['order'] = $bi;
                                        }
                                        if (!isset($block['active'])) {
                                            $normalized['templates'][$ti]['sections'][$si]['blocks'][$bi]['active'] = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($saveAsDraft) {
                $userTemplate->ensureDraftInitialized();
                $userTemplate->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'draft_template_data' => $normalized,
                ]);
            } else {
                $userTemplate->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'template_data' => $normalized,
                ]);
                if ($userTemplate->is_active && ($forceApply || !$saveAsDraft)) {
                    $userTemplate->applyToSite();
                }
            }

            return response()->json([
                'success' => true,
                'applied' => $userTemplate->is_active && !$saveAsDraft,
                'message' => $saveAsDraft ? 'Draft berhasil disimpan!' : ($forceApply ? 'Template diterapkan!' : 'Template berhasil disimpan!'),
                'draft' => $saveAsDraft,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan template: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function publish(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);
        try {
            // Use current draft if exists else template_data
            if ($userTemplate->hasDraft()) {
                $userTemplate->publishDraft();
            } else {
                // Force apply existing template_data
                if ($userTemplate->is_active) {
                    $userTemplate->applyToSite();
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Template berhasil dipublish & diterapkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal publish: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function preview(UserTemplate $userTemplate)
    {
        $this->authorize('view', $userTemplate);

        // Create temporary template for preview
        $previewData = $this->generatePreviewData($userTemplate);

        return view('admin.templates.builder.preview', compact('userTemplate', 'previewData'));
    }

    public function saveSection(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        $request->validate([
            'sections' => 'required|array',
            'sections.*.name' => 'required|string',
            'sections.*.blocks' => 'array',
        ]);

        try {
            $templateData = $userTemplate->template_data;

            if (!isset($templateData['templates'])) {
                $templateData['templates'] = [];
            }

            // Update or create main template
            $mainTemplateIndex = 0;
            if (!isset($templateData['templates'][$mainTemplateIndex])) {
                $templateData['templates'][$mainTemplateIndex] = $this->getDefaultTemplateStructure()['templates'][0];
            }

            $templateData['templates'][$mainTemplateIndex]['sections'] = $request->sections;

            $userTemplate->update(['template_data' => $templateData]);

            // Apply changes if active
            if ($userTemplate->is_active) {
                $userTemplate->applyToSite();
            }

            return response()->json([
                'success' => true,
                'message' => 'Sections berhasil disimpan!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan sections: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function addBlock(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        $request->validate([
            'section_index' => 'required|integer',
            'block_type' => 'required|string',
            'block_data' => 'required|array',
        ]);

        try {
            $templateData = $userTemplate->template_data;
            $sectionIndex = $request->section_index;
            $blockData = $request->block_data;

            if (!isset($templateData['templates'][0]['sections'][$sectionIndex]['blocks'])) {
                $templateData['templates'][0]['sections'][$sectionIndex]['blocks'] = [];
            }

            $blockData['type'] = $request->block_type;
            $blockData['order'] = count($templateData['templates'][0]['sections'][$sectionIndex]['blocks']);
            $blockData['active'] = true;

            $templateData['templates'][0]['sections'][$sectionIndex]['blocks'][] = $blockData;

            $userTemplate->update(['template_data' => $templateData]);

            if ($userTemplate->is_active) {
                $userTemplate->applyToSite();
            }

            return response()->json([
                'success' => true,
                'message' => 'Block berhasil ditambahkan!',
                'block' => $blockData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan block: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateBlock(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        $request->validate([
            'section_index' => 'required|integer',
            'block_index' => 'required|integer',
            'block_data' => 'required|array',
        ]);

        try {
            $templateData = $userTemplate->template_data;
            $sectionIndex = $request->section_index;
            $blockIndex = $request->block_index;

            if (!isset($templateData['templates'][0]['sections'][$sectionIndex]['blocks'][$blockIndex])) {
                throw new \Exception('Block not found');
            }

            $templateData['templates'][0]['sections'][$sectionIndex]['blocks'][$blockIndex] = array_merge(
                $templateData['templates'][0]['sections'][$sectionIndex]['blocks'][$blockIndex],
                $request->block_data
            );

            $userTemplate->update(['template_data' => $templateData]);

            if ($userTemplate->is_active) {
                $userTemplate->applyToSite();
            }

            return response()->json([
                'success' => true,
                'message' => 'Block berhasil diupdate!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate block: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteBlock(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        $request->validate([
            'section_index' => 'required|integer',
            'block_index' => 'required|integer',
        ]);

        try {
            $templateData = $userTemplate->template_data;
            $sectionIndex = $request->section_index;
            $blockIndex = $request->block_index;

            if (!isset($templateData['templates'][0]['sections'][$sectionIndex]['blocks'][$blockIndex])) {
                throw new \Exception('Block not found');
            }

            // Remove block
            unset($templateData['templates'][0]['sections'][$sectionIndex]['blocks'][$blockIndex]);

            // Reindex array
            $templateData['templates'][0]['sections'][$sectionIndex]['blocks'] = array_values(
                $templateData['templates'][0]['sections'][$sectionIndex]['blocks']
            );

            // Update order
            foreach ($templateData['templates'][0]['sections'][$sectionIndex]['blocks'] as $index => $block) {
                $templateData['templates'][0]['sections'][$sectionIndex]['blocks'][$index]['order'] = $index;
            }

            $userTemplate->update(['template_data' => $templateData]);

            if ($userTemplate->is_active) {
                $userTemplate->applyToSite();
            }

            return response()->json([
                'success' => true,
                'message' => 'Block berhasil dihapus!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus block: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function getAvailableBlockTypes()
    {
        $blockTypes = [
            'navigation' => [
                'name' => 'Navigation',
                'description' => 'Header navigasi dengan menu',
                'icon' => 'nav-icon',
                'category' => 'header',
                'fields' => [
                    'site_title' => ['type' => 'text', 'label' => 'Judul Situs'],
                    'logo_text' => ['type' => 'text', 'label' => 'Teks Logo'],
                    'menu_items' => ['type' => 'repeater', 'label' => 'Menu Items', 'fields' => [
                        'title' => ['type' => 'text', 'label' => 'Judul Menu'],
                        'url' => ['type' => 'url', 'label' => 'URL'],
                        'target' => ['type' => 'select', 'label' => 'Target', 'options' => ['_self' => 'Same Window', '_blank' => 'New Window']],
                    ]],
                    'background_color' => ['type' => 'color', 'label' => 'Warna Background'],
                    'text_color' => ['type' => 'color', 'label' => 'Warna Teks'],
                    'sticky' => ['type' => 'checkbox', 'label' => 'Sticky Navigation'],
                ],
            ],
            'hero' => [
                'name' => 'Hero Section',
                'description' => 'Header besar dengan judul, subjudul, dan tombol',
                'icon' => 'hero-icon',
                'category' => 'header',
                'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Judul'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subjudul'],
                    'button_text' => ['type' => 'text', 'label' => 'Teks Tombol'],
                    'button_url' => ['type' => 'url', 'label' => 'URL Tombol'],
                    'background_image' => ['type' => 'image', 'label' => 'Gambar Latar'],
                ],
            ],
            'card-grid' => [
                'name' => 'Card Grid',
                'description' => 'Grid dengan kartu-kartu informasi',
                'icon' => 'grid-icon',
                'category' => 'content',
                'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Judul Section'],
                    'cards' => ['type' => 'repeater', 'label' => 'Kartu', 'fields' => [
                        'title' => ['type' => 'text', 'label' => 'Judul Kartu'],
                        'description' => ['type' => 'textarea', 'label' => 'Deskripsi'],
                        'image' => ['type' => 'image', 'label' => 'Gambar'],
                        'url' => ['type' => 'url', 'label' => 'Link'],
                    ]],
                ],
            ],
            'rich-text' => [
                'name' => 'Rich Text',
                'description' => 'Konten teks dengan format kaya',
                'icon' => 'text-icon',
                'category' => 'content',
                'fields' => [
                    'content' => ['type' => 'wysiwyg', 'label' => 'Konten'],
                ],
            ],
            'stats' => [
                'name' => 'Statistics',
                'description' => 'Tampilan statistik dengan angka',
                'icon' => 'stats-icon',
                'category' => 'info',
                'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Judul Section'],
                    'stats' => ['type' => 'repeater', 'label' => 'Statistik', 'fields' => [
                        'number' => ['type' => 'number', 'label' => 'Angka'],
                        'label' => ['type' => 'text', 'label' => 'Label'],
                        'description' => ['type' => 'text', 'label' => 'Deskripsi'],
                    ]],
                ],
            ],
            'cta-banner' => [
                'name' => 'Call to Action',
                'description' => 'Banner ajakan bertindak',
                'icon' => 'cta-icon',
                'category' => 'marketing',
                'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Judul'],
                    'description' => ['type' => 'textarea', 'label' => 'Deskripsi'],
                    'button_text' => ['type' => 'text', 'label' => 'Teks Tombol'],
                    'button_url' => ['type' => 'url', 'label' => 'URL Tombol'],
                    'background_color' => ['type' => 'color', 'label' => 'Warna Latar'],
                ],
            ],
            'gallery-teaser' => [
                'name' => 'Gallery Teaser',
                'description' => 'Preview gallery dengan gambar terbaru',
                'icon' => 'gallery-icon',
                'category' => 'media',
                'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Judul'],
                    'gallery_id' => ['type' => 'select', 'label' => 'Galeri', 'options' => 'galleries'],
                    'limit' => ['type' => 'number', 'label' => 'Jumlah Gambar'],
                ],
            ],
            'events-teaser' => [
                'name' => 'Events Teaser',
                'description' => 'Daftar event terbaru',
                'icon' => 'events-icon',
                'category' => 'content',
                'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Judul'],
                    'limit' => ['type' => 'number', 'label' => 'Jumlah Event'],
                    'show_date' => ['type' => 'boolean', 'label' => 'Tampilkan Tanggal'],
                    'show_excerpt' => ['type' => 'boolean', 'label' => 'Tampilkan Excerpt'],
                ],
            ],
            'footer' => [
                'name' => 'Footer',
                'description' => 'Footer dengan kontak dan link cepat',
                'icon' => 'footer-icon',
                'category' => 'footer',
                'fields' => [
                    'content' => ['type' => 'wysiwyg', 'label' => 'Konten Footer'],
                    'contact_info' => ['type' => 'group', 'label' => 'Info Kontak', 'fields' => [
                        'email' => ['type' => 'email', 'label' => 'Email'],
                        'phone' => ['type' => 'text', 'label' => 'Telepon'],
                        'address' => ['type' => 'textarea', 'label' => 'Alamat'],
                    ]],
                    'footer_links' => ['type' => 'repeater', 'label' => 'Link Footer', 'fields' => [
                        'title' => ['type' => 'text', 'label' => 'Judul Link'],
                        'url' => ['type' => 'url', 'label' => 'URL'],
                    ]],
                    'background_color' => ['type' => 'color', 'label' => 'Warna Background'],
                    'text_color' => ['type' => 'color', 'label' => 'Warna Teks'],
                    'copyright' => ['type' => 'text', 'label' => 'Copyright Text'],
                ],
            ],
        ];

        // Group blocks by category
        $grouped = [];
        foreach ($blockTypes as $type => $config) {
            $category = $config['category'] ?? 'other';
            $grouped[$category][$type] = $config;
        }

        return $grouped;
    }

    protected function getDefaultTemplateStructure()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage',
                    'slug' => 'homepage',
                    'description' => 'Template untuk halaman utama',
                    'type' => 'page',
                    'active' => true,
                    'is_global' => false,
                    'sort_order' => 0,
                    'layout_settings' => [
                        'container_width' => 'full',
                        'sidebar' => false,
                    ],
                    'sections' => [
                        [
                            'name' => 'Header Section',
                            'order' => 0,
                            'settings' => ['background' => 'light'],
                            'blocks' => [],
                        ],
                    ],
                    'assignments' => [
                        [
                            'route_pattern' => 'home',
                            'priority' => 10,
                            'active' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function buildTemplateStructure(UserTemplate $userTemplate)
    {
        $templateData = $userTemplate->template_data;

        // Handle case where template_data might be a string (JSON)
        if (is_string($templateData)) {
            $templateData = json_decode($templateData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->getDefaultTemplateStructure();
            }
        }

        if (!is_array($templateData) || !isset($templateData['templates'])) {
            return $this->getDefaultTemplateStructure();
        }

        return $templateData;
    }

    protected function generatePreviewData(UserTemplate $userTemplate)
    {
        // Generate sample data for preview
        return [
            'title' => $userTemplate->name,
            'description' => $userTemplate->description,
            'sections' => $userTemplate->template_data['templates'][0]['sections'] ?? [],
        ];
    }
}
