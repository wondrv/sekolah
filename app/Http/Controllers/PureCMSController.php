<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTemplate;
use App\Models\Template;
use App\Models\TemplateAssignment;
use App\Models\Section;
use App\Models\Block;
use App\Models\Page;
use App\Models\Post;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PureCMSController extends Controller
{
    /**
     * Handle all CMS page requests dynamically
     */
    public function handleRequest(Request $request, $path = '/')
    {
        // Determine route pattern
        $routePattern = $this->determineRoutePattern($request, $path);

        // Find active template assignment for this route
        $assignment = $this->findTemplateAssignment($routePattern, $path);

        if (!$assignment) {
            // No template found, use default fallback
            return $this->renderDefaultTemplate($request, $path);
        }

        // Get template data
        $template = $assignment->template;

        // Prepare context data for template
        $context = $this->prepareTemplateContext($request, $path, $routePattern);

        // Render template
        return $this->renderTemplate($template, $context);
    }

    /**
     * Determine route pattern from request
     */
    protected function determineRoutePattern(Request $request, $path)
    {
        // Handle homepage
        if ($path === '/' || $path === 'home') {
            return 'home';
        }

        // Handle specific patterns
        if (str_starts_with($path, 'posts/')) {
            return 'posts.show';
        }

        if (str_starts_with($path, 'events/')) {
            return 'events.show';
        }

        if (str_starts_with($path, 'galleries/')) {
            return 'galleries.show';
        }

        if (str_starts_with($path, 'categories/')) {
            return 'categories.show';
        }

        // Handle page routes
        if ($this->isPageRoute($path)) {
            return 'pages.show';
        }

        // Default pattern
        return 'pages.*';
    }

    /**
     * Find template assignment for route pattern
     */
    protected function findTemplateAssignment($routePattern, $path)
    {
        // Get active user template (current active template for authenticated user or site default)
        $activeUserTemplate = $this->getActiveUserTemplate();

        if (!$activeUserTemplate) {
            return null;
        }

        // Find template assignments for the active user template
        $assignments = TemplateAssignment::where('active', true)
            ->whereHas('template', function ($query) use ($activeUserTemplate) {
                $query->where('user_template_id', $activeUserTemplate->id);
            })
            ->orderBy('priority', 'desc')
            ->get();

        // Try exact page slug match first
        foreach ($assignments as $assignment) {
            if ($assignment->page_slug && $assignment->page_slug === $path) {
                return $assignment;
            }
        }

        // Try route pattern match
        foreach ($assignments as $assignment) {
            if ($this->matchesRoutePattern($assignment->route_pattern, $routePattern)) {
                return $assignment;
            }
        }

        return null;
    }

    /**
     * Get active user template (for current user or site default)
     */
    protected function getActiveUserTemplate()
    {
        // For logged-in admin users, get their active template
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && ($user->is_admin || $user->role === 'admin')) {
                $userTemplate = UserTemplate::where('user_id', Auth::id())
                    ->where('is_active', true)
                    ->first();

                if ($userTemplate) {
                    return $userTemplate;
                }
            }
        }

        // Get site-wide active template (could be the first admin's active template)
        $siteTemplate = UserTemplate::where('is_active', true)
            ->whereHas('user', function ($query) {
                $query->where('is_admin', true);
            })
            ->first();

        return $siteTemplate;
    }

    /**
     * Check if route pattern matches
     */
    protected function matchesRoutePattern($pattern, $routePattern)
    {
        if ($pattern === $routePattern) {
            return true;
        }

        // Handle wildcard patterns
        if (str_ends_with($pattern, '.*')) {
            $prefix = str_replace('.*', '', $pattern);
            return str_starts_with($routePattern, $prefix);
        }

        return false;
    }

    /**
     * Check if path is a page route
     */
    protected function isPageRoute($path)
    {
        return Page::where('slug', $path)->exists();
    }

    /**
     * Prepare context data for template rendering
     */
    protected function prepareTemplateContext(Request $request, $path, $routePattern)
    {
        $context = [
            'site' => $this->getSiteData(),
            'request' => $request,
            'path' => $path,
            'route_pattern' => $routePattern,
        ];

        // Add specific context based on route pattern
        switch ($routePattern) {
            case 'home':
                $context = array_merge($context, $this->getHomeContext());
                break;

            case 'posts.show':
                $context = array_merge($context, $this->getPostContext($path));
                break;

            case 'events.show':
                $context = array_merge($context, $this->getEventContext($path));
                break;

            case 'galleries.show':
                $context = array_merge($context, $this->getGalleryContext($path));
                break;

            case 'categories.show':
                $context = array_merge($context, $this->getCategoryContext($path));
                break;

            case 'pages.show':
                $context = array_merge($context, $this->getPageContext($path));
                break;
        }

        return $context;
    }

    /**
     * Get site-wide data
     */
    protected function getSiteData()
    {
        return Cache::remember('cms_site_data', 3600, function () {
            return [
                'title' => setting('site_title', 'School CMS'),
                'description' => setting('site_description', 'Modern School Content Management System'),
                'logo' => setting('site_logo'),
                'favicon' => setting('site_favicon'),
                'contact_email' => setting('contact_email'),
                'contact_phone' => setting('contact_phone'),
                'address' => setting('school_address'),
                'social_links' => [
                    'facebook' => setting('facebook_url'),
                    'twitter' => setting('twitter_url'),
                    'instagram' => setting('instagram_url'),
                    'youtube' => setting('youtube_url'),
                ],
            ];
        });
    }

    /**
     * Get homepage context
     */
    protected function getHomeContext()
    {
        return [
            'page_title' => 'Home',
            'meta_description' => setting('site_description', 'Welcome to our school'),
            'featured_posts' => Post::where('featured', true)->take(6)->get(),
            'upcoming_events' => Event::where('start_date', '>=', now())->take(5)->get(),
            'latest_galleries' => Gallery::where('active', true)->latest()->take(3)->get(),
            'stats' => $this->getSchoolStats(),
        ];
    }

    /**
     * Get post context
     */
    protected function getPostContext($path)
    {
        $slug = str_replace('posts/', '', $path);
        $post = Post::where('slug', $slug)->published()->first();

        if (!$post) {
            abort(404);
        }

        return [
            'post' => $post,
            'page_title' => $post->title,
            'meta_description' => $post->excerpt ?: $post->meta_description,
            'related_posts' => Post::published()
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->take(4)
                ->get(),
        ];
    }

    /**
     * Get event context
     */
    protected function getEventContext($path)
    {
        $slug = str_replace('events/', '', $path);
        $event = Event::where('slug', $slug)->first();

        if (!$event) {
            abort(404);
        }

        return [
            'event' => $event,
            'page_title' => $event->title,
            'meta_description' => $event->description,
            'related_events' => Event::where('id', '!=', $event->id)
                ->upcoming()
                ->take(4)
                ->get(),
        ];
    }

    /**
     * Get gallery context
     */
    protected function getGalleryContext($path)
    {
        $slug = str_replace('galleries/', '', $path);
        $gallery = Gallery::where('slug', $slug)->active()->first();

        if (!$gallery) {
            abort(404);
        }

        return [
            'gallery' => $gallery->load('photos'),
            'page_title' => $gallery->name,
            'meta_description' => $gallery->description,
            'other_galleries' => Gallery::active()
                ->where('id', '!=', $gallery->id)
                ->take(6)
                ->get(),
        ];
    }

    /**
     * Get category context
     */
    protected function getCategoryContext($path)
    {
        $slug = str_replace('categories/', '', $path);
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            abort(404);
        }

        return [
            'category' => $category,
            'page_title' => $category->name,
            'meta_description' => $category->description,
            'posts' => $category->posts()->published()->paginate(12),
        ];
    }

    /**
     * Get page context
     */
    protected function getPageContext($path)
    {
        $page = Page::where('slug', $path)->published()->first();

        if (!$page) {
            abort(404);
        }

        return [
            'page' => $page,
            'page_title' => $page->title,
            'meta_description' => $page->meta_description ?: $page->excerpt,
        ];
    }

    /**
     * Get school statistics
     */
    protected function getSchoolStats()
    {
        return Cache::remember('school_stats', 3600, function () {
            return [
                'total_students' => setting('total_students', 0),
                'total_teachers' => setting('total_teachers', 0),
                'total_classes' => setting('total_classes', 0),
                'establishment_year' => setting('establishment_year', date('Y')),
                'total_graduates' => setting('total_graduates', 0),
            ];
        });
    }

    /**
     * Render template with context
     */
    protected function renderTemplate(Template $template, array $context)
    {
        // Load template with sections and blocks
        $template->load(['sections.blocks' => function ($query) {
            $query->where('active', true)->orderBy('order');
        }]);

        // Generate rendered content for each section
        $renderedSections = [];

        foreach ($template->sections as $section) {
            $renderedBlocks = [];

            foreach ($section->blocks as $block) {
                $renderedBlocks[] = $this->renderBlock($block, $context);
            }

            $renderedSections[] = [
                'section' => $section,
                'blocks' => $renderedBlocks,
            ];
        }

        // Return view with rendered template
        return view('templates.cms-template', [
            'template' => $template,
            'sections' => $renderedSections,
            'context' => $context,
        ]);
    }

    /**
     * Render individual block
     */
    protected function renderBlock(Block $block, array $context)
    {
        // Convert underscore to hyphen for view path (database uses underscores, files use hyphens)
        $viewType = str_replace('_', '-', $block->type);
        $viewPath = "components.blocks.{$viewType}";

        // Check if block component exists
        if (!view()->exists($viewPath)) {
            return "<div class='block-error'>Block type '{$block->type}' not found</div>";
        }

        // Prepare block data
        $blockData = array_merge($context, [
            'block' => $block,
            'content' => $block->content ?: [],
            'settings' => $block->settings ?: [],
            'style_settings' => $block->style_settings ?: [],
        ]);

        try {
            return view($viewPath, $blockData)->render();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return "<div class='block-error'>Error rendering block: {$e->getMessage()}</div>";
            }
            return '';
        }
    }

    /**
     * Render default template when no CMS template found
     */
    protected function renderDefaultTemplate(Request $request, $path)
    {
        // Fallback to basic page rendering
        if ($path === '/' || $path === 'home') {
            // Use existing home view or create simple fallback
            if (view()->exists('home')) {
                return view('home');
            }

            // Create simple fallback response
            return response()->view('templates.fallback-home', [
                'site_title' => setting('site_title', 'School CMS'),
                'site_description' => setting('site_description', 'Modern School Content Management System'),
            ]);
        }

        // Try to find page
        $page = Page::where('slug', $path)->published()->first();
        if ($page) {
            return view('pages.show', compact('page'));
        }

        // 404
        abort(404);
    }
}
