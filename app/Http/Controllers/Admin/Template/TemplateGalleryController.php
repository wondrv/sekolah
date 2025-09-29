<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Models\TemplateGallery;
use App\Models\TemplateCategory;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TemplateGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = TemplateGallery::with('category')->active();

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

        $categories = TemplateCategory::active()->ordered()->get();

        $stats = [
            'total' => TemplateGallery::active()->count(),
            'featured' => TemplateGallery::active()->featured()->count(),
            'categories' => $categories->count(),
            'installed' => UserTemplate::byUser()->count(),
        ];

        return view('admin.templates.gallery.index', compact(
            'templates',
            'categories',
            'stats',
            'request'
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

            return redirect()->route('admin.templates.my-templates')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menginstall template: ' . $e->getMessage());
        }
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

        $stats = [
            'total' => $category->activeTemplates()->count(),
            'featured' => $category->activeTemplates()->featured()->count(),
        ];

        return view('admin.templates.gallery.category', compact(
            'category',
            'templates',
            'stats',
            'request'
        ));
    }
}
