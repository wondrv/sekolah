<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PreviewImageService
{
    /**
     * Generate (placeholder) preview image path; future: render or capture screenshot.
     */
    public function generate(array $options = []): string
    {
        // For now return a deterministic placeholder (could be per slug hash)
        $seed = $options['seed'] ?? Str::random(8);
        return 'images/placeholders/template-'.substr(md5($seed),0,8).'.png';
    }

    /**
     * Generate preview from URL using screenshot service or placeholder
     */
    public function generateFromUrl(string $url): array
    {
        try {
            // Try to generate a screenshot using a service
            $screenshot = $this->captureScreenshot($url);

            if ($screenshot['success']) {
                return [
                    'success' => true,
                    'image_url' => $screenshot['image_url'],
                    'stats' => [
                        'method' => 'screenshot',
                        'file_size' => $screenshot['file_size'] ?? null
                    ]
                ];
            }

            // Fallback to placeholder
            $placeholder = $this->generatePlaceholderFromUrl($url);

            return [
                'success' => true,
                'image_url' => $placeholder,
                'stats' => [
                    'method' => 'placeholder',
                    'fallback_reason' => $screenshot['error'] ?? 'Screenshot service unavailable'
                ]
            ];

        } catch (\Exception $e) {
            Log::warning('Preview generation failed', ['url' => $url, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'image_url' => $this->generatePlaceholderFromUrl($url),
                'error' => $e->getMessage(),
                'stats' => [
                    'method' => 'error_fallback'
                ]
            ];
        }
    }

    /**
     * Capture screenshot using external service
     */
    protected function captureScreenshot(string $url): array
    {
        try {
            // Using a free screenshot service (alternative: htmlcsstoimage.com, screenshotapi.net)
            $screenshotUrl = 'https://shot.screenshotapi.net/screenshot';

            $response = Http::timeout(30)->get($screenshotUrl, [
                'url' => $url,
                'width' => 1200,
                'height' => 800,
                'output' => 'image',
                'file_type' => 'png',
                'wait_for_event' => 'load'
            ]);

            if ($response->successful()) {
                // Save the image
                $filename = 'previews/template-' . md5($url) . '-' . time() . '.png';
                $saved = Storage::disk('public')->put($filename, $response->body());

                if ($saved) {
                    return [
                        'success' => true,
                        'image_url' => Storage::url($filename),
                        'file_size' => strlen($response->body())
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Screenshot service failed or returned invalid response'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Screenshot capture failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate placeholder image URL based on URL
     */
    protected function generatePlaceholderFromUrl(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST) ?? 'template';
        $hash = substr(md5($url), 0, 8);

        // Use unsplash for educational placeholders
        $seed = $hash;
        return "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop&crop=center&auto=format&q=60&seed={$seed}";
    }

    /**
     * Generate preview for template data (render template)
     */
    public function generateFromTemplateData(array $templateData, array $options = []): array
    {
        try {
            // For now, return a placeholder based on template content
            $title = $templateData['templates'][0]['name'] ?? 'Template';
            $seed = md5($title);

            return [
                'success' => true,
                'image_url' => $this->generatePlaceholderFromUrl('template://' . $seed),
                'stats' => [
                    'method' => 'template_placeholder'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'image_url' => $this->generate(['seed' => 'fallback']),
                'error' => $e->getMessage(),
                'stats' => [
                    'method' => 'error_fallback'
                ]
            ];
        }
    }
}
