<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TemplateRenderService;
use App\Models\UserTemplate;
use App\Models\Setting;
use App\Support\Theme;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function index(Request $request)
    {
        // Debug: Check for active UserTemplate with blade_views type first
        Log::info('HomeController index() called');

        $activeTemplate = UserTemplate::where('is_active', true)
            ->where('template_type', 'blade_views')
            ->first();

        Log::info('Active template query result', [
            'template_found' => $activeTemplate ? true : false,
            'template_id' => $activeTemplate ? $activeTemplate->id : null,
            'template_name' => $activeTemplate ? $activeTemplate->name : null,
            'template_type' => $activeTemplate ? $activeTemplate->template_type : null
        ]);

        if ($activeTemplate) {
            Log::info('Calling renderBladeTemplate');
            return $this->renderBladeTemplate($activeTemplate);
        }

        Log::info('No active blade template found, proceeding with normal flow');

        // Check if we should use full template system
        $homepageType = Setting::get('homepage_template_type', 'cms');

        if ($homepageType === 'full_template') {
            return $this->renderFullTemplate();
        }

        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('home', [
            'pageTitle' => 'Home',
            'metaDescription' => 'Welcome to our school website'
        ]);

        if ($templateView) {
            return $templateView;
        }

        // Fallback to original home template system
        $template = Theme::getHomeTemplate();
        return view('home-template', compact('template'));
    }    /**
     * Render blade template from UserTemplate
     */
    protected function renderBladeTemplate(UserTemplate $template)
    {
        // Log that we're trying to render the template
        Log::info('Attempting to render blade template', [
            'template_id' => $template->id,
            'template_name' => $template->name
        ]);

        $templateFiles = $template->template_files;
        $templateData = $template->template_data;

        // Find the main view file (home.blade.php or similar)
        $mainView = 'home.blade.php';
        if (isset($templateFiles[$mainView])) {
            $viewContent = $templateFiles[$mainView];

            // Extract content if it's stored as array with content key
            if (is_array($viewContent) && isset($viewContent['content'])) {
                $content = $viewContent['content'];

                Log::info('Found home.blade.php content', [
                    'content_length' => strlen($content),
                    'content_preview' => substr($content, 0, 100)
                ]);

                // For now, let's return a simple response to test if this method is called
                return response("TEMPLATE WORKING! Template ID: {$template->id}, Name: {$template->name}<br><br>Content Preview:<br>" . htmlspecialchars(substr($content, 0, 500)));
            }
        }

        // Log if we didn't find home.blade.php
        Log::info('home.blade.php not found, checking other files', [
            'available_files' => array_keys($templateFiles)
        ]);

        // If no home.blade.php, try to find any main view file
        foreach ($templateFiles as $filename => $fileData) {
            if (str_contains($filename, 'home') || str_contains($filename, 'index')) {
                $content = is_array($fileData) && isset($fileData['content'])
                    ? $fileData['content']
                    : $fileData;
                return response("FOUND MAIN VIEW: {$filename}<br><br>Content Preview:<br>" . htmlspecialchars(substr($content, 0, 500)));
            }
        }

        // Fallback to CMS if no suitable view found
        Log::warning('No suitable main view found, falling back to CMS', [
            'available_files' => array_keys($templateFiles)
        ]);
        return $this->renderCmsTemplate();
    }    /**
     * Render dynamic view content
     */
    protected function renderDynamicView(string $content, UserTemplate $template)
    {
        // Create a temporary view file
        $tempViewName = 'temp_' . md5($template->id . time());
        $viewPath = resource_path("views/{$tempViewName}.blade.php");

        try {
            // Write content to temporary file
            file_put_contents($viewPath, $content);

            // Clear view cache to ensure fresh compilation
            Artisan::call('view:clear');

            // Render the view
            $result = view($tempViewName, [
                'template' => $template,
                'settings' => Setting::all()->pluck('value', 'key')->toArray()
            ]);

            return $result;

        } finally {
            // Clean up temporary file
            if (file_exists($viewPath)) {
                unlink($viewPath);
            }
        }
    }

    /**
     * Render full template (WordPress-like)
     */
    protected function renderFullTemplate()
    {
        $templateId = Setting::get('active_full_template_id');

        if (!$templateId) {
            // No full template active, fall back to CMS
            return $this->renderCmsTemplate();
        }

        $template = UserTemplate::find($templateId);

        if (!$template || $template->status !== 'active') {
            // Template not found or inactive, fall back to CMS
            return $this->renderCmsTemplate();
        }

        $templateData = $template->template_data;

        if ($templateData['type'] !== 'full_template') {
            // Not a full template, fall back to CMS
            return $this->renderCmsTemplate();
        }

        // Get main HTML file path
        $mainFile = $templateData['main_file'];
        $assetsPath = $templateData['assets_path'];
        $mainFilePath = "{$assetsPath}/{$mainFile}";

        if (!Storage::exists($mainFilePath)) {
            // Main file not found, fall back to CMS
            return $this->renderCmsTemplate();
        }

        // Read and serve the HTML content
        $htmlContent = Storage::get($mainFilePath);

        // Process the HTML to ensure proper asset URLs
        $htmlContent = $this->processFullTemplateHtml($htmlContent, $assetsPath);

        // Return raw HTML response
        return response($htmlContent)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Render CMS template as fallback
     */
    protected function renderCmsTemplate()
    {
        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('home', [
            'pageTitle' => 'Home',
            'metaDescription' => 'Welcome to our school website'
        ]);

        if ($templateView) {
            return $templateView;
        }

        // Fallback to original home template system
        $template = Theme::getHomeTemplate();
        return view('home-template', compact('template'));
    }

    /**
     * Process HTML to ensure proper asset URLs
     */
    protected function processFullTemplateHtml(string $html, string $assetsPath): string
    {
        $storageUrl = url('storage');
        $baseUrl = "{$storageUrl}/{$assetsPath}";

        // Update relative paths to absolute storage URLs
        $html = preg_replace_callback(
            '/(href|src)="([^"]*)"/',
            function($matches) use ($baseUrl) {
                $attribute = $matches[1];
                $path = $matches[2];

                // Skip if already absolute URL
                if (filter_var($path, FILTER_VALIDATE_URL) || str_starts_with($path, '//')) {
                    return $matches[0];
                }

                // Skip if starts with #
                if (str_starts_with($path, '#')) {
                    return $matches[0];
                }

                // Convert relative path to storage URL
                $absolutePath = "{$baseUrl}/" . ltrim($path, '/');
                return "{$attribute}=\"{$absolutePath}\"";
            },
            $html
        );

        return $html;
    }
}
