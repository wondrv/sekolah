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
     * Handle all CMS page requests dynamically - UNIVERSAL DATABASE-DRIVEN SYSTEM
     */
    public function handleRequest(Request $request, $path = '/')
    {
        // Log for debugging
        Log::info('PureCMSController called', ['path' => $path]);

        // Check for active UserTemplate with blade_views type (imported templates)
        $activeBladeTemplate = UserTemplate::where('is_active', true)
            ->where('template_type', 'blade_views')
            ->first();

        Log::info('Active template check', ['found' => $activeBladeTemplate ? 'YES' : 'NO']);

        if ($activeBladeTemplate) {
            // Determine which view to render based on path
            $viewName = $this->getViewNameFromPath($path);
            Log::info('Rendering view', ['view_name' => $viewName, 'path' => $path]);
            return $this->renderBladeTemplateForPath($activeBladeTemplate, $viewName, $path);
        } else {
            // No active template, use fallback
            Log::info('Using fallback template');
            return $this->renderDefaultTemplate($request, $path);
        }
    }

    /**
     * Get view name from URL path
     */
    protected function getViewNameFromPath($path)
    {
        // Clean and normalize path
        $path = trim($path, '/');

        // Map paths to view names
        $pathMap = [
            '' => 'home',
            'home' => 'home',
            'tentang' => 'tentang',
            'program' => 'program',
            'berita' => 'berita',
            'galeri' => 'galeri',
            'kontak' => 'kontak',
            'ppdb' => 'ppdb'
        ];

        return $pathMap[$path] ?? 'home';
    }

    /**
     * Render blade template for specific path/view
     */
    protected function renderBladeTemplateForPath(UserTemplate $template, $viewName, $path)
    {
        $templateFiles = $template->template_files;

        try {
            // Get the specific view file and layout
            $viewFileName = "resources/views/{$viewName}.blade.php";
            $layoutFileName = "resources/views/layouts/main.blade.php";

            $viewFile = $templateFiles[$viewFileName] ?? null;
            $layoutFile = $templateFiles[$layoutFileName] ?? null;

            if (!$viewFile || !$layoutFile) {
                // If specific view not found, try home as fallback
                if ($viewName !== 'home') {
                    $homeFileName = "resources/views/home.blade.php";
                    $viewFile = $templateFiles[$homeFileName] ?? null;
                }

                if (!$viewFile || !$layoutFile) {
                    return response("Template view '{$viewName}' not found", 404);
                }
            }

            // Decode content
            $viewContent = is_array($viewFile) && isset($viewFile['content'])
                ? base64_decode($viewFile['content'])
                : $viewFile;

            $layoutContent = is_array($layoutFile) && isset($layoutFile['content'])
                ? base64_decode($layoutFile['content'])
                : $layoutFile;

            // Render content dynamically
            return $this->renderTemplateContentDynamically($viewContent, $layoutContent, $template);

        } catch (\Exception $e) {
            Log::error('Error rendering dynamic template for path', [
                'template_id' => $template->id,
                'path' => $path,
                'view_name' => $viewName,
                'error' => $e->getMessage()
            ]);

            // Fallback to 404 or error page
            return response("Error rendering template: " . $e->getMessage(), 500);
        }
    }

    /**
     * Render blade template from UserTemplate (imported templates) - Dynamic Database-Driven
     */
    protected function renderBladeTemplate(UserTemplate $template)
    {
        $templateFiles = $template->template_files;

        try {
            // Get home view content from database
            $homeViewFile = null;
            $layoutViewFile = null;

            foreach ($templateFiles as $filename => $fileData) {
                if ($filename === 'resources/views/home.blade.php') {
                    $homeViewFile = $fileData;
                } elseif ($filename === 'resources/views/layouts/main.blade.php') {
                    $layoutViewFile = $fileData;
                }
            }

            if (!$homeViewFile) {
                throw new \Exception('Home view not found in template');
            }

            // Decode content
            $homeContent = is_array($homeViewFile) && isset($homeViewFile['content'])
                ? base64_decode($homeViewFile['content'])
                : $homeViewFile;

            $layoutContent = null;
            if ($layoutViewFile) {
                $layoutContent = is_array($layoutViewFile) && isset($layoutViewFile['content'])
                    ? base64_decode($layoutViewFile['content'])
                    : $layoutViewFile;
            }

            // Render content dynamically without file system
            return $this->renderTemplateContentDynamically($homeContent, $layoutContent, $template);

        } catch (\Exception $e) {
            Log::error('Error rendering dynamic template', [
                'template_id' => $template->id,
                'error' => $e->getMessage()
            ]);

            // Fallback to default template
            return $this->renderDefaultTemplate(request(), '/');
        }
    }

    /**
     * Render template content dynamically from database without file system
     */
    protected function renderTemplateContentDynamically($homeContent, $layoutContent, UserTemplate $template)
    {
        try {
            // Simple approach: use layout as base and inject content
            if ($layoutContent && str_contains($homeContent, "@extends('layouts.main')")) {
                // Extract content section
                $contentPattern = "/@section\\s*\\(\\s*['\"]content['\"]\\s*\\)(.*?)@endsection/s";
                preg_match($contentPattern, $homeContent, $contentMatches);
                $contentSection = $contentMatches[1] ?? '';

                // Extract title section
                $titlePattern = "/@section\\s*\\(\\s*['\"]title['\"]\\s*,\\s*['\"]([^'\"]*)['\"]\\s*\\)/";
                preg_match($titlePattern, $homeContent, $titleMatches);
                $title = $titleMatches[1] ?? 'Home';

                // Replace in layout
                $finalContent = str_replace('@yield(\'content\')', trim($contentSection), $layoutContent);

                // Handle multiple title patterns
                $titleReplacements = [
                    '@yield(\'title\', \'SMA HARAPAN NUSANTARA\')',
                    '@yield(\'title\', \'MA Cendekia Nusantara\')',
                    '@yield(\'title\', "SMA HARAPAN NUSANTARA")',
                    '@yield(\'title\', "MA Cendekia Nusantara")',
                    '@yield(\'title\')',
                    '@yield("title")'
                ];

                foreach ($titleReplacements as $pattern) {
                    $finalContent = str_replace($pattern, $title, $finalContent);
                }
            } else {
                // Use content as-is
                $finalContent = $homeContent;
            }

            // Process template variables - COMPREHENSIVE PARSING
            $baseUrl = request()->getSchemeAndHttpHost();

            // Replace all route() helpers with actual URLs - Multiple variations
            $routeMap = [
                'home' => '/',
                'tentang' => '/tentang',
                'program' => '/program',
                'berita' => '/berita',
                'galeri' => '/galeri',
                'kontak' => '/kontak',
                'ppdb' => '/ppdb'
            ];

            // Parse {{ route('name') }} patterns with regex for better matching
            foreach ($routeMap as $routeName => $path) {
                $patterns = [
                    "/\\{\\{\\s*route\\s*\\(\\s*['\"]" . $routeName . "['\"]\\s*\\)\\s*\\}\\}/",
                    "/\\{\\{route\\s*\\(\\s*['\"]" . $routeName . "['\"]\\s*\\)\\}\\}/"
                ];

                foreach ($patterns as $pattern) {
                    $finalContent = preg_replace($pattern, $baseUrl . $path, $finalContent);
                }
            }

            // Parse href="/path" patterns and make them absolute
            foreach ($routeMap as $routeName => $path) {
                if ($path !== '/') { // Skip home route
                    $finalContent = str_replace("href=\"{$path}\"", "href=\"{$baseUrl}{$path}\"", $finalContent);
                    $finalContent = str_replace("href='{$path}'", "href='{$baseUrl}{$path}'", $finalContent);
                }
            }

            // Parse other Laravel helpers with regex
            $finalContent = preg_replace('/\\{\\{\\s*url\\s*\\(\\s*[\'"]\/[\'"]\\s*\\)\\s*\\}\\}/', $baseUrl, $finalContent);
            $finalContent = preg_replace('/\\{\\{\\s*date\\s*\\(\\s*[\'"]Y[\'"]\\s*\\)\\s*\\}\\}/', date('Y'), $finalContent);

            // Fix any remaining encoded URLs in href attributes
            $finalContent = preg_replace_callback('/href="([^"]*%7B%7B[^"]*%7D%7D[^"]*)"/', function($matches) {
                return 'href="' . urldecode($matches[1]) . '"';
            }, $finalContent);

            // Create a response with the processed content
            return response($finalContent)->header('Content-Type', 'text/html');

        } catch (\Exception $e) {
            Log::error('Error in dynamic template rendering', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return simple HTML response
            return response('<h1>Template Error</h1><p>Unable to render template: ' . $e->getMessage() . '</p>');
        }
    }    /**
     * Combine layout and content sections
     */
    protected function combineLayoutAndContent($layoutContent, $homeContent)
    {
        // Extract sections from home content
        $sections = $this->extractBladeSection($homeContent);

        // Replace placeholders in layout
        $finalContent = $layoutContent;

        foreach ($sections as $sectionName => $sectionContent) {
            $placeholder = "@yield('{$sectionName}')";
            $finalContent = str_replace($placeholder, $sectionContent, $finalContent);
        }

        return $finalContent;
    }

    /**
     * Extract Blade @section content
     */
    protected function extractBladeSection($content)
    {
        $sections = [];

        // Extract @section('title', 'value') pattern
        preg_match_all("/@section\\(['\"]([^'\"]+)['\"]\\s*,\\s*['\"]([^'\"]*)['\"]\\)/", $content, $titleMatches, PREG_SET_ORDER);
        foreach ($titleMatches as $match) {
            $sections[$match[1]] = $match[2];
        }

        // Extract @section('name') ... @endsection pattern
        preg_match_all("/@section\\(['\"]([^'\"]+)['\"]\\)(.*?)@endsection/s", $content, $blockMatches, PREG_SET_ORDER);
        foreach ($blockMatches as $match) {
            $sections[$match[1]] = trim($match[2]);
        }

        return $sections;
    }

    /**
     * Process template variables and URLs
     */
    protected function processTemplateVariables($content, UserTemplate $template)
    {
        // Replace Laravel helpers with actual values
        $baseUrl = request()->getSchemeAndHttpHost();

        // Replace asset() helper
        $content = preg_replace_callback('/asset\\([\'"]([^\'"]+)[\'"]\\)/', function($matches) use ($baseUrl) {
            return $baseUrl . '/assets/' . $matches[1];
        }, $content);

        // Replace route() helper with direct URLs
        $content = preg_replace_callback('/route\\([\'"]([^\'"]+)[\'"]\\)/', function($matches) use ($baseUrl) {
            $routeMap = [
                'home' => '/',
                'tentang' => '/tentang',
                'program' => '/program',
                'berita' => '/berita',
                'galeri' => '/galeri',
                'kontak' => '/kontak',
                'ppdb' => '/ppdb'
            ];

            $routeName = $matches[1];
            $path = $routeMap[$routeName] ?? '/' . $routeName;
            return $baseUrl . $path;
        }, $content);

        // Replace {{ url('/path') }} with actual URLs
        $content = preg_replace_callback('/\\{\\{\\s*url\\([\'"]([^\'"]*)[\'"]\\)\\s*\\}\\}/', function($matches) use ($baseUrl) {
            return $baseUrl . $matches[1];
        }, $content);

        return $content;
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
            // Create simple fallback response
            return response()->view('templates.fallback-home', [
                'site_title' => setting('site_title', 'School CMS'),
                'site_description' => setting('site_description', 'Modern School Content Management System'),
            ]);
        }

        // For other routes, show a helpful message instead of 404
        $message = "
        <h1>No Active Template Found</h1>
        <p>Path: <strong>/{$path}</strong></p>
        <p>Please import and activate a template from the admin panel.</p>
        <p><a href='/admin/template-system/gallery'>Browse Templates</a></p>
        <p><a href='/admin/dashboard'>Admin Dashboard</a></p>
        ";

        return response($message, 404)->header('Content-Type', 'text/html');
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
                        str_contains($content, 'SMA Harapan Nusantara') ||
                        str_contains($content, '@extends(\'layouts.app\')') ||
                        str_contains($content, '@extends(\'layouts.main\')')) {
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
            $routeContent = "\n\n";
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
