<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TemplateRenderService;
use App\Models\UserTemplate;
use App\Models\Setting;
use App\Support\Theme;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function index(Request $request)
    {
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
