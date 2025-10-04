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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class PureCMSController extends Controller
{
    /**
     * Handle all CMS page requests dynamically
     */
    public function handleRequest(Request $request, $path = '/')
    {
        // First, check for active UserTemplate with blade_views type (imported templates)
        if ($path === '/' || $path === 'home') {
            $activeBladeTemplate = UserTemplate::where('is_active', true)
                ->where('template_type', 'blade_views')
                ->first();

            if ($activeBladeTemplate) {
                return $this->renderBladeTemplate($activeBladeTemplate);
            } else {
                // No active template, clean up any temporary template files
                $this->cleanUpTemporaryTemplateFiles();
            }
        }

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
     * Render blade template from UserTemplate (imported templates)
     */
    protected function renderBladeTemplate(UserTemplate $template)
    {
        $templateFiles = $template->template_files;

        try {
            // Install controllers and routes if this is a full Laravel template
            $this->installFullLaravelComponents($template);

            // Create temporary view files for all template files
            $tempFiles = [];
            foreach ($templateFiles as $filename => $fileData) {
                $content = is_array($fileData) && isset($fileData['content'])
                    ? $fileData['content']
                    : $fileData;

                // Decode base64 content if it's encoded
                if (base64_decode($content, true) !== false) {
                    $content = base64_decode($content);
                }

                // Create subdirectory if needed (for layouts/app.blade.php)
                $viewPath = resource_path('views/' . $filename);
                $directory = dirname($viewPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Write temporary file
                file_put_contents($viewPath, $content);
                $tempFiles[] = $viewPath;
            }

            // Clear view cache to ensure fresh compilation
            Artisan::call('view:clear');

            // Find and render main view
            $mainView = 'home'; // home.blade.php -> 'home'
            if (isset($templateFiles['home.blade.php'])) {
                $result = view($mainView, [
                    'template' => $template,
                    'settings' => \App\Models\Setting::all()->pluck('value', 'key')->toArray()
                ]);

                return $result;
            }

            // If no home.blade.php, try to find any main view file
            foreach ($templateFiles as $filename => $fileData) {
                if (str_contains($filename, 'home') || str_contains($filename, 'index')) {
                    $viewName = str_replace('.blade.php', '', $filename);
                    return view($viewName, [
                        'template' => $template,
                        'settings' => \App\Models\Setting::all()->pluck('value', 'key')->toArray()
                    ]);
                }
            }

        } catch (\Exception $e) {
            // Log error and fall back
            Log::error('Error rendering blade template', [
                'template_id' => $template->id,
                'error' => $e->getMessage()
            ]);
        } finally {
            // Clean up temporary files (optional - could keep them for caching)
            // foreach ($tempFiles ?? [] as $tempFile) {
            //     if (file_exists($tempFile)) {
            //         unlink($tempFile);
            //     }
            // }
        }

        // Fallback to default template if rendering failed
        return $this->renderDefaultTemplate(request(), '/');
    }    /**
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

    /**
     * Clean up temporary template files when no template is active
     */
    protected function cleanUpTemporaryTemplateFiles()
    {
        try {
            // List of common template files that might be left behind
            $filesToClean = [
                'home.blade.php',
                'layouts/app.blade.php',
                'profil.blade.php',
                'fasilitas.blade.php',
                'guru.blade.php',
                'prestasi.blade.php',
                'galeri.blade.php',
                'kontak.blade.php'
            ];

            foreach ($filesToClean as $filename) {
                $viewPath = resource_path('views/' . $filename);
                if (file_exists($viewPath)) {
                    // Check if this is a temporary template file (not original CMS file)
                    // We can identify them by checking if they contain template-specific content
                    $content = file_get_contents($viewPath);

                    // If it contains template-specific markers, it's safe to delete
                    if (str_contains($content, 'SD Negeri Contoh') ||
                        str_contains($content, 'Selamat Datang di SD') ||
                        str_contains($content, '@extends(\'layouts.app\')')) {
                        unlink($viewPath);
                        Log::info("Cleaned up temporary template file: {$filename}");
                    }
                }
            }

            // Clean up empty layouts directory if it exists
            $layoutsDir = resource_path('views/layouts');
            if (is_dir($layoutsDir) && count(scandir($layoutsDir)) == 2) { // Only . and ..
                rmdir($layoutsDir);
                Log::info("Cleaned up empty layouts directory");
            }

            // Clear view cache to ensure no compiled views remain
            Artisan::call('view:clear');

        } catch (\Exception $e) {
            Log::error('Error cleaning up temporary template files', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Install Full Laravel Components (controllers and routes) from template
     */
    protected function installFullLaravelComponents(UserTemplate $template)
    {
        $templateData = $template->template_data;
        $templateFiles = $template->template_files;

        // Check if this is a full Laravel template with controllers and routes
        if (!isset($templateData['structure'])) {
            return;
        }

        $structure = $templateData['structure'];

        try {
            // Install Controllers
            if (!empty($structure['controller_files'])) {
                foreach ($structure['controller_files'] as $controllerFile) {
                    $this->installController($controllerFile, $templateFiles[$controllerFile]);
                }
                Log::info('Installed controllers from template', [
                    'template_id' => $template->id,
                    'controllers' => count($structure['controller_files'])
                ]);
            }

            // Install Routes
            if (!empty($structure['route_files'])) {
                foreach ($structure['route_files'] as $routeFile) {
                    $this->installRoutes($routeFile, $templateFiles[$routeFile]);
                }
                Log::info('Installed routes from template', [
                    'template_id' => $template->id,
                    'routes' => count($structure['route_files'])
                ]);
            }

            // Install Assets
            $assetFiles = array_merge(
                $structure['css_files'] ?? [],
                $structure['js_files'] ?? [],
                $structure['image_files'] ?? []
            );

            if (!empty($assetFiles)) {
                foreach ($assetFiles as $assetFile) {
                    if (isset($templateFiles[$assetFile])) {
                        $this->installAsset($assetFile, $templateFiles[$assetFile]);
                    }
                }
                Log::info('Installed assets from template', [
                    'template_id' => $template->id,
                    'assets' => count($assetFiles)
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error installing Laravel components', [
                'template_id' => $template->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Install controller file
     */
    protected function installController(string $filename, array $fileData)
    {
        try {
            $content = base64_decode($fileData['content']);

            // Determine target path
            $relativePath = str_replace(['app/Http/Controllers/', 'app\\Http\\Controllers\\'], '', $filename);
            $targetPath = app_path('Http/Controllers/' . $relativePath);

            // Create directory if needed
            $directory = dirname($targetPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Write controller file
            file_put_contents($targetPath, $content);

            Log::info('Controller installed', ['path' => $targetPath]);

        } catch (\Exception $e) {
            Log::error('Failed to install controller', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Install routes from template
     */
    protected function installRoutes(string $filename, array $fileData)
    {
        try {
            $content = base64_decode($fileData['content']);

            // Create template routes file
            $templateRoutesFile = base_path('routes/template.php');

            // Add route content with proper formatting
            $routeContent = "\n\n// Routes from template: {$filename}\n";
            $routeContent .= "// Installed on: " . now()->format('Y-m-d H:i:s') . "\n";
            $routeContent .= $content . "\n";

            file_put_contents($templateRoutesFile, $routeContent, FILE_APPEND | LOCK_EX);

            // Include the routes file in web.php if not already included
            $this->includeTemplateRoutes();

            Log::info('Routes installed', ['path' => $templateRoutesFile]);

        } catch (\Exception $e) {
            Log::error('Failed to install routes', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Include template routes in main web.php
     */
    protected function includeTemplateRoutes()
    {
        $webRoutesPath = base_path('routes/web.php');
        $webContent = file_get_contents($webRoutesPath);

        $includeStatement = "\n// Include template routes\nif (file_exists(__DIR__ . '/template.php')) {\n    require __DIR__ . '/template.php';\n}\n";

        // Check if already included
        if (!str_contains($webContent, 'template.php')) {
            file_put_contents($webRoutesPath, $webContent . $includeStatement);
            Log::info('Template routes included in web.php');
        }
    }

    /**
     * Install asset file
     */
    protected function installAsset(string $filename, array $fileData)
    {
        try {
            $content = base64_decode($fileData['content']);

            // Determine target path in public/template-assets/
            $relativePath = str_replace(['public/', 'assets/'], '', $filename);
            $targetPath = public_path('template-assets/' . $relativePath);

            // Create directory if needed
            $directory = dirname($targetPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($targetPath, $content);

        } catch (\Exception $e) {
            Log::error('Failed to install asset', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
        }
    }
}
