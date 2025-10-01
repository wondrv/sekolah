<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Models\TemplateGallery;
use App\Models\TemplateCategory;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\ExternalTemplateService;

class TemplateGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = TemplateGallery::with('category')->active();
        $externalTemplates = [];
        $showExternal = $request->boolean('include_external', true);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Filter by type
        if ($request->has('type')) {
            if ($request->type === 'featured') {
                $query->featured();
            } elseif ($request->type === 'free') {
                $query->free();
            } elseif ($request->type === 'premium') {
                $query->premium();
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereJsonContains('features', $search);
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'downloads':
                $query->orderBy('downloads', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('featured', 'desc')
                      ->orderBy('rating', 'desc')
                      ->orderBy('downloads', 'desc');
                break;
        }

        $templates = $query->paginate(12)->appends($request->all());

        // Fetch external templates if requested and not searching specific category
        if ($showExternal && !$request->has('category')) {
            try {
                $externalService = new ExternalTemplateService();
                $externalTemplates = $externalService->discoverTemplates('all', 8);
            } catch (\Exception $e) {
                Log::warning('External template discovery failed: ' . $e->getMessage());
                $externalTemplates = [];
            }
        }

        $categories = TemplateCategory::active()->ordered()->get();

        $stats = [
            'total' => TemplateGallery::active()->count(),
            'featured' => TemplateGallery::active()->featured()->count(),
            'categories' => $categories->count(),
            'installed' => UserTemplate::byUser()->count(),
            'external_discovered' => count($externalTemplates),
        ];

        return view('admin.templates.gallery.index', compact(
            'templates',
            'categories',
            'stats',
            'request',
            'externalTemplates'
        ));
    }

    public function show(TemplateGallery $template)
    {
        $template->load('category');

        $isInstalled = $template->isInstalled();
        $userTemplate = null;

        if ($isInstalled) {
            $userTemplate = UserTemplate::byUser()
                ->where('gallery_template_id', $template->id)
                ->first();
        }

        $relatedTemplates = TemplateGallery::active()
            ->where('category_id', $template->category_id)
            ->where('id', '!=', $template->id)
            ->take(6)
            ->get();

        return view('admin.templates.gallery.show', compact(
            'template',
            'isInstalled',
            'userTemplate',
            'relatedTemplates'
        ));
    }

    public function preview(TemplateGallery $template)
    {
        // Return preview data for modal or separate window
        $previewImages = $template->preview_images_urls;

        // Add placeholder images if no preview images exist
        if (empty($previewImages) || count($previewImages) === 0) {
            $placeholderImages = [
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80'
            ];
            $imageIndex = $template->id % count($placeholderImages);
            $previewImages = [$placeholderImages[$imageIndex]];
        }

        return response()->json([
            'template' => $template->load('category'),
            'preview_images' => $previewImages,
            'demo_content' => $template->demo_content,
            'features' => $template->features,
            'description' => $template->description,
        ]);
    }

    /**
     * Render a live preview page (full page) for a gallery template without installing.
     */
    public function livePreview(TemplateGallery $template, Request $request)
    {
        $template->load('category');

        $structure = $template->template_data['templates'][0] ?? null;
    $forceSample = (bool)$request->boolean('sample');
    $compare = (bool)$request->boolean('compare');
    $cacheKey = 'gallery.livePreview.' . $template->id . '.' . ($forceSample ? 'sample' : 'real') . '.' . ($compare ? 'cmp' : 'solo');

        // We'll cache only the processed sections (not the full rendered HTML) for 60s
    $payload = Cache::remember($cacheKey, 60, function () use ($structure, $forceSample) {
            $sections = [];
            if ($structure && isset($structure['sections'])) {
                // Preload real content for fallback queries (only once per preview build)
                $latestPosts = \App\Models\Post::latest()->take(6)->get(['title','created_at','excerpt']);
                $latestEvents = \App\Models\Event::orderBy('start_date','asc')->where('start_date','>=', now()->subDays(7))->take(6)->get(['title','start_date','description']);
                // Minimal gallery info (if a gallery model present)
                $galleries = \App\Models\Gallery::withCount('photos')->latest()->take(6)->get(['title']);

                foreach ($structure['sections'] as $section) {
                    $blocks = $section['blocks'] ?? [];
                    foreach ($blocks as &$b) {
                        $type = $b['type'] ?? null;
                        $dataRef =& $b['data'];
                        if (!is_array($dataRef)) {
                            $dataRef = [];
                        }
                        $hasOverride = !empty(($dataRef['override'] ?? null));

                        // Real content fallback logic (only if not forcing sample)
                        if (!$forceSample) {
                            if (($type === 'posts-teaser' || $type === 'announcements-teaser') && !$hasOverride) {
                                if ($latestPosts->count() > 0) {
                                    $dataRef['_real_posts'] = $latestPosts->map(function($p){
                                        return [
                                            'title' => $p->title,
                                            'date' => $p->created_at?->format('d M Y'),
                                            'excerpt' => str( (string)$p->excerpt)->limit(120)->value(),
                                        ];
                                    })->toArray();
                                    $dataRef['title'] = $dataRef['title'] ?? ($type === 'posts-teaser' ? 'Berita Terbaru' : 'Pengumuman');
                                }
                            }
                            if ($type === 'events-teaser' && !$hasOverride) {
                                if ($latestEvents->count() > 0) {
                                    $dataRef['_real_events'] = $latestEvents->map(function($e){
                                        return [
                                            'title' => $e->title,
                                            'date' => $e->start_date?->format('d M Y'),
                                            'description' => str((string)$e->description)->limit(100)->value(),
                                        ];
                                    })->toArray();
                                    $dataRef['title'] = $dataRef['title'] ?? 'Agenda Mendatang';
                                }
                            }
                            if ($type === 'gallery-teaser' && !$hasOverride) {
                                if ($galleries->count() > 0) {
                                    $dataRef['_real_galleries'] = $galleries->map(function($g){
                                        return [
                                            'title' => $g->title,
                                            'count' => $g->photos_count ?? 0,
                                        ];
                                    })->toArray();
                                    $dataRef['title'] = $dataRef['title'] ?? 'Galeri Kegiatan';
                                }
                            }
                        }

                        // Sample injection (either forced OR no real fallback available)
                        $needSamplePosts = ($type === 'posts-teaser' || $type === 'announcements-teaser') && empty($dataRef['_real_posts']);
                        $needSampleEvents = ($type === 'events-teaser') && empty($dataRef['_real_events']);
                        $needSampleGalleries = ($type === 'gallery-teaser') && empty($dataRef['_real_galleries']);

                        if (($forceSample || $needSamplePosts) && ($type === 'posts-teaser' || $type === 'announcements-teaser') && !$hasOverride) {
                            $dataRef['title'] = $dataRef['title'] ?? ($type === 'posts-teaser' ? 'Berita Terbaru' : 'Pengumuman');
                            $dataRef['_sample_posts'] = [
                                ['title' => 'Judul Contoh 1', 'date' => now()->subDays(1)->format('d M Y'), 'excerpt' => 'Deskripsi singkat contoh pertama.'],
                                ['title' => 'Judul Contoh 2', 'date' => now()->subDays(2)->format('d M Y'), 'excerpt' => 'Deskripsi singkat contoh kedua.'],
                                ['title' => 'Judul Contoh 3', 'date' => now()->subDays(3)->format('d M Y'), 'excerpt' => 'Deskripsi singkat contoh ketiga.'],
                            ];
                        }
                        if (($forceSample || $needSampleEvents) && $type === 'events-teaser' && !$hasOverride) {
                            $dataRef['title'] = $dataRef['title'] ?? 'Agenda Mendatang';
                            $dataRef['_sample_events'] = [
                                ['title' => 'Workshop Sains', 'date' => now()->addDays(5)->format('d M Y'), 'description' => 'Eksperimen laboratorium terbuka.'],
                                ['title' => 'Lomba Debat', 'date' => now()->addDays(9)->format('d M Y'), 'description' => 'Kompetisi antar kelas.'],
                                ['title' => 'Pentas Seni', 'date' => now()->addDays(14)->format('d M Y'), 'description' => 'Pertunjukan musik & drama.'],
                            ];
                        }
                        if (($forceSample || $needSampleGalleries) && $type === 'gallery-teaser' && !$hasOverride) {
                            $dataRef['title'] = $dataRef['title'] ?? 'Galeri Kegiatan';
                            $dataRef['_sample_galleries'] = [
                                ['title' => 'Upacara Bendera', 'count' => 12],
                                ['title' => 'Praktikum Lab', 'count' => 8],
                                ['title' => 'Lomba Seni', 'count' => 15],
                            ];
                        }
                        unset($dataRef); // cleanup reference
                    }
                    unset($b);
                    $sections[] = [
                        'name' => $section['name'] ?? 'Section',
                        'blocks' => $blocks,
                    ];
                }
            }
            return [
                'sections' => $sections,
            ];
        });

        $sections = $payload['sections'];

        // Theme variable simulation (CSS custom properties)
        $themeCss = \App\Models\ThemeSetting::getCssVariables();

        // If compare mode, load active template sections (non-cached for now; can add cache if expensive)
        $activeTemplate = null;
        $activeSections = [];
        $activeMenus = [];
        $activeWidgets = [];
        if ($compare) {
            $activeTemplate = \App\Support\Theme::getHomeTemplate();
            if ($activeTemplate) {
                foreach ($activeTemplate->sections as $s) {
                    $blockArr = [];
                    foreach ($s->blocks as $blk) {
                        $blockArr[] = [
                            'type' => $blk->type,
                            'data' => $blk->content ?? [],
                        ];
                    }
                    $activeSections[] = [
                        'name' => $s->name ?? 'Section',
                        'blocks' => $blockArr,
                    ];
                }
            }
            // Attempt to get real menus/widgets for active site chrome
            try {
                $primaryMenu = \App\Support\Theme::getMenu('primary');
                if ($primaryMenu && $primaryMenu->count()) {
                    $activeMenus['primary'] = $primaryMenu->map(function($mi){
                        return [
                            'title' => $mi->title,
                            'url' => $mi->url ?? '#',
                        ];
                    })->toArray();
                }
                $footerWidgets = \App\Support\Theme::getWidgets('footer');
                if ($footerWidgets && $footerWidgets->count()) {
                    $activeWidgets['footer'] = $footerWidgets->map(function($w){
                        return [
                            'title' => $w->title ?? 'Widget',
                            'content' => str((string)$w->content)->limit(140)->value(),
                        ];
                    })->toArray();
                }
            } catch (\Exception $e) {
                // Silent fail for preview context
            }
        }

        // Simulated menus & widgets (used for gallery template side always; solo mode uses these as well)
        $simulatedMenus = [
            'primary' => [
                ['title' => 'Beranda', 'url' => '#'],
                ['title' => 'Profil', 'url' => '#'],
                ['title' => 'Program Keahlian', 'url' => '#'],
                ['title' => 'Berita', 'url' => '#'],
                ['title' => 'Agenda', 'url' => '#'],
                ['title' => 'Galeri', 'url' => '#'],
                ['title' => 'Kontak', 'url' => '#'],
            ],
            'footer' => [
                ['title' => 'PPDB', 'url' => '#'],
                ['title' => 'Kurikulum', 'url' => '#'],
                ['title' => 'Prestasi', 'url' => '#'],
                ['title' => 'Alumni', 'url' => '#'],
            ],
        ];
        $simulatedWidgets = [
            'footer' => [
                [
                    'title' => 'Tentang Sekolah',
                    'content' => 'Sekolah vokasi berfokus teknologi & inovasi pembelajaran berbasis proyek.'
                ],
                [
                    'title' => 'Kontak',
                    'content' => 'Jl. Pendidikan No.123\nJakarta Pusat\nTelp: (021) 123-4567'
                ],
            ],
        ];

        return view('admin.templates.gallery.live-preview', [
            'galleryTemplate' => $template,
            'structure' => $structure,
            'sections' => $sections,
            'useSample' => $forceSample,
            'compare' => $compare,
            'activeTemplate' => $activeTemplate,
            'activeSections' => $activeSections,
            'themeCss' => $themeCss,
            'simulatedMenus' => $simulatedMenus,
            'simulatedWidgets' => $simulatedWidgets,
            'activeMenus' => $activeMenus,
            'activeWidgets' => $activeWidgets,
        ]);
    }

    public function install(Request $request, TemplateGallery $template)
    {
        $user = Auth::user();

        // Check if already installed
        if ($template->isInstalled($user->id)) {
            return redirect()->back()->with('error', 'Template sudah diinstall.');
        }

        try {
            // Create user template
            $userTemplate = $template->createUserTemplate($user->id, [
                'installed_at' => now(),
                'install_options' => $request->get('options', []),
            ]);

            // Increment download counter
            $template->incrementDownloads();

            // Activate if requested or if it's the first template
            $userTemplateCount = UserTemplate::where('user_id', $user->id)->count();
            if ($request->get('activate', false) || $userTemplateCount === 1) {
                $userTemplate->activate();
                $message = 'Template berhasil diinstall dan diaktifkan!';
            } else {
                $message = 'Template berhasil diinstall!';
            }

            // Quick path: if user clicked Duplicate & Edit, redirect to builder
            if ($request->has('duplicate_edit')) {
                return redirect()->route('admin.templates.builder.edit', $userTemplate->id)
                    ->with('success', 'Template diduplikat. Silakan lakukan edit.');
            }

            return redirect()->route('admin.templates.my-templates')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menginstall template: ' . $e->getMessage());
        }
    }

    /**
     * Bulk install all active gallery templates that user has not yet installed.
     */
    public function bulkInstall(Request $request)
    {
        $user = Auth::user();
        $activateFirst = $request->boolean('activate_first', true);
        $installedAny = false;
        $activated = false;
        $errors = [];

        $alreadyHasActive = UserTemplate::byUser()->active()->exists();

        $templates = TemplateGallery::active()->get();
        foreach ($templates as $galleryTemplate) {
            if ($galleryTemplate->isInstalled($user->id)) {
                continue;
            }
            try {
                $userTemplate = $galleryTemplate->createUserTemplate($user->id, [
                    'installed_at' => now(),
                    'install_options' => [],
                ]);
                $galleryTemplate->incrementDownloads();
                $installedAny = true;
                if (!$alreadyHasActive && $activateFirst && !$activated) {
                    $userTemplate->activate();
                    $activated = true;
                }
            } catch (\Exception $e) {
                $errors[] = $galleryTemplate->name . ': ' . $e->getMessage();
            }
        }

        if (!$installedAny) {
            return redirect()->back()->with('info', 'Semua template gallery sudah terinstall.');
        }

        $message = 'Bulk install selesai.';
        if ($activated) {
            $message .= ' Satu template diaktifkan.';
        }
        if ($errors) {
            $message .= ' Beberapa gagal: ' . implode('; ', array_slice($errors, 0, 3));
        }

        return redirect()->route('admin.templates.gallery.index')->with('success', $message);
    }

    public function categories()
    {
        $categories = TemplateCategory::active()
            ->withCount('activeTemplates')
            ->ordered()
            ->get();

        return view('admin.templates.gallery.categories', compact('categories'));
    }

    public function byCategory(TemplateCategory $category, Request $request)
    {
        $query = $category->activeTemplates()->with('category');

        // Apply filters and search similar to index method
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'downloads':
                $query->orderBy('downloads', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('featured', 'desc')
                      ->orderBy('rating', 'desc');
                break;
        }

        $templates = $query->paginate(12)->appends($request->all());

        // Reuse index view; need full category list & consistent stats keys
        $categories = TemplateCategory::active()->ordered()->get();

        $stats = [
            'total' => $category->activeTemplates()->count(),
            'featured' => $category->activeTemplates()->featured()->count(),
            'categories' => $categories->count(),
            'installed' => UserTemplate::byUser()->count(),
        ];

        // Inject current category slug into request helper via query string param simulation (for active badge in view)
        $request->merge(['category' => $category->slug]);

        return view('admin.templates.gallery.index', compact(
            'category',
            'templates',
            'stats',
            'request',
            'categories'
        ));
    }

    /**
     * Install external template from discovered templates
     */
    public function installExternal(Request $request)
    {
        $request->validate([
            'external_id' => 'required|string',
            'source_type' => 'required|string|in:github,free_css',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'source_url' => 'nullable|url',
            'preview_image' => 'nullable|url',
            'features' => 'nullable|array',
            'activate' => 'boolean'
        ]);

        $user = Auth::user();

        try {
            // Reconstruct template data from request
            $templateData = [
                'external_id' => $request->external_id,
                'name' => $request->name,
                'description' => $request->description,
                'source_url' => $request->source_url,
                'preview_image' => $request->preview_image,
                'author' => $request->get('author', 'External'),
                'features' => $request->get('features', []),
                'rating' => $request->get('rating', 4.0),
                'source_type' => $request->source_type,
                'converted_template_data' => $this->buildExternalTemplateData($request)
            ];

            $externalService = new ExternalTemplateService();
            $galleryTemplate = $externalService->installExternalTemplate($templateData, $user->id);

            if (!$galleryTemplate) {
                return redirect()->back()->with('error', 'Gagal menginstall template eksternal.');
            }

            // Get the created user template
            $userTemplate = $galleryTemplate->userTemplates()->where('user_id', $user->id)->first();

            // Activate if requested
            if ($request->boolean('activate') && $userTemplate) {
                $userTemplate->activate();
                $message = 'Template eksternal berhasil diinstall dan diaktifkan!';
            } else {
                $message = 'Template eksternal berhasil diinstall!';
            }

            return redirect()->route('admin.templates.my-templates.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('External template installation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menginstall template: ' . $e->getMessage());
        }
    }

    /**
     * Preview external template before installation
     */
    public function previewExternal(Request $request)
    {
        $templateData = [
            'name' => $request->get('name', 'External Template'),
            'description' => $request->get('description', 'Preview of external template'),
            'preview_image' => $request->get('preview_image'),
            'features' => $request->get('features', []),
            'source_type' => $request->get('source_type', 'external')
        ];        // Build preview structure
        $structure = [
            'templates' => [
                [
                    'name' => $templateData['name'],
                    'slug' => 'external-preview',
                    'description' => $templateData['description'],
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'data' => [
                                        'title' => $templateData['name'],
                                        'subtitle' => $templateData['description'],
                                        'background_color' => 'bg-gradient-to-r from-purple-600 to-blue-600',
                                        'buttons' => [
                                            ['text' => 'External Template', 'url' => '#', 'style' => 'primary']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Features',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'data' => [
                                        'title' => 'Template Features',
                                        'cards' => array_map(function($feature) {
                                            return [
                                                'title' => $feature,
                                                'description' => 'Feature from external template',
                                                'icon' => '✨'
                                            ];
                                        }, $templateData['features'] ?? ['Modern Design', 'Responsive', 'Easy to Use'])
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Mock gallery template for preview
        $mockTemplate = (object) [
            'id' => 'external',
            'name' => $templateData['name'],
            'category' => (object) ['name' => 'External'],
            'template_data' => $structure
        ];

        return view('admin.templates.gallery.live-preview', [
            'galleryTemplate' => $mockTemplate,
            'structure' => $structure['templates'][0],
            'sections' => $structure['templates'][0]['sections'],
            'useSample' => true,
            'compare' => false,
            'activeTemplate' => null,
            'activeSections' => [],
            'themeCss' => \App\Models\ThemeSetting::getCssVariables(),
            'simulatedMenus' => [
                'primary' => [
                    ['title' => 'Home', 'url' => '#'],
                    ['title' => 'About', 'url' => '#'],
                    ['title' => 'Contact', 'url' => '#']
                ]
            ],
            'simulatedWidgets' => [
                'footer' => [
                    ['title' => 'External Template', 'content' => 'This is a preview of an external template']
                ]
            ],
            'activeMenus' => [],
            'activeWidgets' => [],
            'isExternalPreview' => true
        ]);
    }

    protected function buildExternalTemplateData(Request $request): array
    {
        return [
            'templates' => [
                [
                    'name' => $request->name,
                    'slug' => 'external-' . time(),
                    'description' => $request->description,
                    'active' => true,
                    'type' => 'page',
                    'sections' => [
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Main Hero',
                                    'order' => 1,
                                    'content' => [
                                        'title' => $request->name,
                                        'subtitle' => $request->description,
                                        'background_color' => 'bg-gradient-to-r from-indigo-600 to-purple-600'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Features Section',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Template Features',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'External Template Features',
                                        'cards' => array_map(function($feature) {
                                            return [
                                                'title' => $feature,
                                                'description' => 'Imported from external source'
                                            ];
                                        }, $request->get('features', []))
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
