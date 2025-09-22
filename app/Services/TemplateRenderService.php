<?php

namespace App\Services;

use App\Models\Template;
use App\Models\TemplateAssignment;
use App\Models\Block;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class TemplateRenderService
{
    protected $request;
    protected $themeSettings;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->loadThemeSettings();
    }

    /**
     * Find and render the appropriate template for the current request
     */
    public function renderForRequest($defaultView = null, $data = [])
    {
        $template = $this->findTemplateForRequest();
        
        if (!$template) {
            // Fall back to default view if no template assigned
            return $defaultView ? view($defaultView, $data) : null;
        }

        return $this->renderTemplate($template, $data);
    }

    /**
     * Find the best matching template for the current request
     */
    public function findTemplateForRequest()
    {
        $currentRoute = $this->request->route();
        $routeName = $currentRoute ? $currentRoute->getName() : null;
        $routePath = $this->request->path();

        // Get all active assignments ordered by priority
        $assignments = TemplateAssignment::with('template')
            ->where('active', true)
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($assignments as $assignment) {
            if ($this->matchesAssignment($assignment, $routeName, $routePath)) {
                return $assignment->template;
            }
        }

        return null;
    }

    /**
     * Check if the current request matches a template assignment
     */
    protected function matchesAssignment(TemplateAssignment $assignment, $routeName, $routePath)
    {
        // Check route pattern match
        if ($assignment->route_pattern) {
            if ($this->matchesPattern($assignment->route_pattern, $routePath)) {
                return true;
            }
        }

        // Check page slug match  
        if ($assignment->page_slug) {
            $segments = explode('/', trim($routePath, '/'));
            $lastSegment = end($segments);
            if ($lastSegment === $assignment->page_slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a route path matches a pattern
     */
    protected function matchesPattern($pattern, $path)
    {
        // Convert pattern to regex
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = str_replace('*', '.*', $pattern);
        $pattern = preg_replace('/\{[^}]+\}/', '[^\/]+', $pattern);
        $pattern = '/^' . $pattern . '$/';

        return preg_match($pattern, $path);
    }

    /**
     * Render a page using the appropriate template
     */
    public function renderPage(string $routePattern, ?string $pageSlug = null, array $data = []): string
    {
        $template = TemplateAssignment::findTemplateFor($routePattern, $pageSlug);
        
        if (!$template) {
            return $this->renderFallback($data);
        }

        return $this->renderTemplate($template, $data);
    }

    /**
     * Render a template with its sections and blocks
     */
    public function renderTemplate(Template $template, array $data = []): string
    {
        $template->load('sections.blocks');
        
        $renderedSections = [];
        
        foreach ($template->sections as $section) {
            if (!$section->active) continue;
            
            $renderedBlocks = [];
            
            foreach ($section->blocks as $block) {
                if (!$block->active) continue;
                
                $renderedBlocks[] = $this->renderBlock($block, $data);
            }
            
            $renderedSections[] = [
                'section' => $section,
                'blocks' => $renderedBlocks,
            ];
        }
        
        return View::make('templates.render', [
            'template' => $template,
            'sections' => $renderedSections,
            'data' => $data,
        ])->render();
    }

    /**
     * Render a single block
     */
    public function renderBlock($block, array $data = []): string
    {
        $blockData = array_merge($block->data ?? [], $data);
        $viewName = "components.blocks.{$block->type}";
        
        // Check if block should be visible on current device
        // This would typically be handled by CSS, but we can also do server-side filtering
        
        if (View::exists($viewName)) {
            return View::make($viewName, [
                'block' => $block,
                'data' => $blockData,
                'settings' => $block->style_settings ?? [],
                'cssClass' => $block->css_class ?? '',
            ])->render();
        }
        
        // Fallback for unknown block types
        return View::make('components.blocks.default', [
            'block' => $block,
            'data' => $blockData,
        ])->render();
    }

    /**
     * Get theme CSS variables
     */
    public function getThemeStyles(): string
    {
        return Cache::remember('theme_styles', 3600, function () {
            return ThemeSetting::getCssVariables();
        });
    }

    /**
     * Render fallback content when no template is found
     */
    private function renderFallback(array $data = []): string
    {
        return View::make('templates.fallback', $data)->render();
    }

    /**
     * Get available block types for the page builder
     */
    public function getAvailableBlocks(): array
    {
        return [
            'layout' => [
                'container' => [
                    'name' => 'Container',
                    'icon' => 'fas fa-square',
                    'category' => 'layout',
                    'settings' => [
                        'width' => ['type' => 'select', 'label' => 'Width', 'options' => ['full', 'container', 'narrow']],
                        'padding' => ['type' => 'select', 'label' => 'Padding', 'options' => ['none', 'small', 'medium', 'large']],
                    ]
                ],
                'columns' => [
                    'name' => 'Columns',
                    'icon' => 'fas fa-columns',
                    'category' => 'layout',
                    'settings' => [
                        'columns' => ['type' => 'select', 'label' => 'Columns', 'options' => ['1', '2', '3', '4']],
                        'gap' => ['type' => 'select', 'label' => 'Gap', 'options' => ['none', 'small', 'medium', 'large']],
                    ]
                ],
            ],
            'content' => [
                'hero' => [
                    'name' => 'Hero Section',
                    'icon' => 'fas fa-image',
                    'category' => 'content',
                    'settings' => [
                        'title' => ['type' => 'text', 'label' => 'Title'],
                        'subtitle' => ['type' => 'text', 'label' => 'Subtitle'],
                        'background_image' => ['type' => 'image', 'label' => 'Background Image'],
                        'background_color' => ['type' => 'text', 'label' => 'Background Color'],
                        'text_align' => ['type' => 'select', 'label' => 'Text Alignment', 'options' => ['left', 'center', 'right']],
                    ]
                ],
                'rich_text' => [
                    'name' => 'Rich Text',
                    'icon' => 'fas fa-align-left',
                    'category' => 'content',
                    'settings' => [
                        'content' => ['type' => 'textarea', 'label' => 'Content'],
                        'text_align' => ['type' => 'select', 'label' => 'Text Alignment', 'options' => ['left', 'center', 'right']],
                    ]
                ],
                'card_grid' => [
                    'name' => 'Card Grid',
                    'icon' => 'fas fa-th',
                    'category' => 'content',
                    'settings' => [
                        'title' => ['type' => 'text', 'label' => 'Title'],
                        'columns' => ['type' => 'select', 'label' => 'Columns', 'options' => ['1', '2', '3', '4']],
                        'cards' => ['type' => 'json', 'label' => 'Cards Data'],
                    ]
                ],
            ],
            'navigation' => [
                'navigation_menu' => [
                    'name' => 'Navigation Menu',
                    'icon' => 'fas fa-bars',
                    'category' => 'navigation',
                    'settings' => [
                        'menu_id' => ['type' => 'select', 'label' => 'Menu', 'options' => []],
                        'style' => ['type' => 'select', 'label' => 'Style', 'options' => ['horizontal', 'vertical']],
                    ]
                ],
                'breadcrumbs' => [
                    'name' => 'Breadcrumbs',
                    'icon' => 'fas fa-chevron-right',
                    'category' => 'navigation',
                    'settings' => [
                        'show_home' => ['type' => 'checkbox', 'label' => 'Show Home'],
                        'separator' => ['type' => 'text', 'label' => 'Separator', 'placeholder' => '/'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Load theme settings
     */
    protected function loadThemeSettings()
    {
        $this->themeSettings = Cache::remember('theme_settings', 3600, function () {
            $settings = ThemeSetting::pluck('value', 'key')->toArray();
            
            // Parse JSON values
            foreach ($settings as $key => $value) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $settings[$key] = $decoded;
                }
            }

            return $settings;
        });
    }

    /**
     * Get theme settings
     */
    public function getThemeSettings()
    {
        return $this->themeSettings;
    }

    /**
     * Generate CSS variables from theme settings
     */
    public function generateCssVariables()
    {
        $css = ':root {';
        
        if (isset($this->themeSettings['colors'])) {
            foreach ($this->themeSettings['colors'] as $name => $value) {
                $css .= "--color-{$name}: {$value};";
            }
        }

        if (isset($this->themeSettings['typography'])) {
            foreach ($this->themeSettings['typography'] as $name => $value) {
                $css .= "--typography-{$name}: {$value};";
            }
        }

        if (isset($this->themeSettings['spacing'])) {
            foreach ($this->themeSettings['spacing'] as $name => $value) {
                $css .= "--spacing-{$name}: {$value};";
            }
        }

        $css .= '}';
        
        return $css;
    }

    /**
     * Check if a template is assigned to a specific route
     */
    public function hasTemplateForRoute($routePattern)
    {
        return TemplateAssignment::where('route_pattern', $routePattern)
            ->where('active', true)
            ->exists();
    }

    /**
     * Get template for a specific route
     */
    public function getTemplateForRoute($routePattern)
    {
        $assignment = TemplateAssignment::with('template')
            ->where('route_pattern', $routePattern)
            ->where('active', true)
            ->orderBy('priority', 'desc')
            ->first();

        return $assignment ? $assignment->template : null;
    }

    /**
     * Clear template cache
     */
    public function clearCache()
    {
        Cache::forget('theme_settings');
        Cache::tags(['templates'])->flush();
    }
}