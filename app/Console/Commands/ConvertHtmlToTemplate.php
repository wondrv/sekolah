<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ConvertHtmlToTemplate extends Command
{
    protected $signature = 'template:convert-html {html_file} {template_name}';
    protected $description = 'Convert HTML file to CMS template JSON format';

    public function handle()
    {
        $htmlFile = $this->argument('html_file');
        $templateName = $this->argument('template_name');

        if (!File::exists($htmlFile)) {
            $this->error("HTML file not found: {$htmlFile}");
            return 1;
        }

        $htmlContent = File::get($htmlFile);

        // Parse HTML and create template structure
        $templateData = $this->parseHtmlToTemplate($htmlContent, $templateName);

        // Create JSON file
        $outputFile = storage_path("app/templates/{$templateName}.json");

        // Create directory if not exists
        if (!File::exists(dirname($outputFile))) {
            File::makeDirectory(dirname($outputFile), 0755, true);
        }

        File::put($outputFile, json_encode($templateData, JSON_PRETTY_PRINT));

        $this->info("Template JSON created: {$outputFile}");
        $this->info("You can now import this file through the admin panel.");

        return 0;
    }

    protected function parseHtmlToTemplate($htmlContent, $templateName)
    {
        // Extract sections from HTML
        $sections = [];

        // Parse navigation
        if (preg_match('/<nav[^>]*id="navbar"[^>]*>(.*?)<\/nav>/s', $htmlContent, $matches)) {
            $sections[] = [
                'name' => 'Header Navigation',
                'order' => 0,
                'active' => true,
                'blocks' => [
                    [
                        'type' => 'rich_text',
                        'name' => 'Main Navigation',
                        'order' => 0,
                        'content' => [
                            'html' => $this->extractNavigation($matches[1])
                        ],
                        'active' => true
                    ]
                ]
            ];
        }

        // Parse hero section
        if (preg_match('/<header[^>]*id="hero"[^>]*>(.*?)<\/header>/s', $htmlContent, $matches)) {
            $heroContent = $matches[1];

            // Extract hero title
            preg_match('/<h1>(.*?)<\/h1>/s', $heroContent, $titleMatch);
            $title = $titleMatch[1] ?? 'Hero Title';

            // Extract hero subtitle
            preg_match('/<p>(.*?)<\/p>/s', $heroContent, $subtitleMatch);
            $subtitle = $subtitleMatch[1] ?? 'Hero Subtitle';

            $sections[] = [
                'name' => 'Hero Section',
                'order' => 1,
                'active' => true,
                'blocks' => [
                    [
                        'type' => 'hero',
                        'name' => 'Main Hero',
                        'order' => 0,
                        'content' => [
                            'title' => strip_tags($title),
                            'subtitle' => strip_tags($subtitle),
                            'background_image' => 'hero/smam1ta-hero.jpg',
                            'text_align' => 'text-center'
                        ],
                        'active' => true
                    ]
                ]
            ];
        }

        // Parse about section
        if (preg_match('/<section[^>]*id="about"[^>]*>(.*?)<\/section>/s', $htmlContent, $matches)) {
            $sections[] = [
                'name' => 'About Section',
                'order' => 2,
                'active' => true,
                'blocks' => [
                    [
                        'type' => 'rich_text',
                        'name' => 'About Content',
                        'order' => 0,
                        'content' => [
                            'html' => $this->cleanHtml($matches[1])
                        ],
                        'active' => true
                    ]
                ]
            ];
        }

        // Parse news section
        if (preg_match('/<section[^>]*id="news"[^>]*>(.*?)<\/section>/s', $htmlContent, $matches)) {
            $sections[] = [
                'name' => 'News Section',
                'order' => 3,
                'active' => true,
                'blocks' => [
                    [
                        'type' => 'rich_text',
                        'name' => 'News Content',
                        'order' => 0,
                        'content' => [
                            'html' => $this->cleanHtml($matches[1])
                        ],
                        'active' => true
                    ]
                ]
            ];
        }

        // Parse program section
        if (preg_match('/<section[^>]*id="program"[^>]*>(.*?)<\/section>/s', $htmlContent, $matches)) {
            $sections[] = [
                'name' => 'Program Section',
                'order' => 4,
                'active' => true,
                'blocks' => [
                    [
                        'type' => 'rich_text',
                        'name' => 'Program Content',
                        'order' => 0,
                        'content' => [
                            'html' => $this->cleanHtml($matches[1])
                        ],
                        'active' => true
                    ]
                ]
            ];
        }

        // Parse footer
        if (preg_match('/<footer[^>]*id="footer"[^>]*>(.*?)<\/footer>/s', $htmlContent, $matches)) {
            $sections[] = [
                'name' => 'Footer',
                'order' => 5,
                'active' => true,
                'blocks' => [
                    [
                        'type' => 'rich_text',
                        'name' => 'Footer Content',
                        'order' => 0,
                        'content' => [
                            'html' => $this->cleanHtml($matches[1])
                        ],
                        'active' => true
                    ]
                ]
            ];
        }

        // Extract CSS and add as customizations
        preg_match('/<style[^>]*>(.*?)<\/style>/s', $htmlContent, $cssMatch);
        $css = $cssMatch[1] ?? '';

        return [
            'description' => "Template imported from HTML: {$templateName}",
            'template_data' => [
                'templates' => [
                    [
                        'name' => $templateName,
                        'slug' => 'homepage',
                        'description' => "Homepage template converted from HTML",
                        'type' => 'page',
                        'active' => true,
                        'sections' => $sections
                    ]
                ]
            ],
            'customizations' => [
                'css' => $css,
                'javascript' => $this->extractJavaScript($htmlContent)
            ]
        ];
    }

    protected function extractNavigation($navHtml)
    {
        // Convert the navbar to Bootstrap 5 format compatible with our CMS
        $cleanNav = '
        <header class="sticky-top bg-white shadow-sm">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="/">
                        <img src="/images/logo-smamita.png" alt="SMA Muhammadiyah 1 Taman" height="45" class="me-2">
                        <span class="fw-bold text-primary">SMAMITA</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
                            <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
                            <li class="nav-item"><a class="nav-link" href="#news">Berita</a></li>
                            <li class="nav-item"><a class="nav-link" href="#program">Program</a></li>
                            <li class="nav-item"><a class="nav-link" href="#footer">Kontak</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>';

        return $cleanNav;
    }

    protected function cleanHtml($html)
    {
        // Remove container class attributes to avoid conflicts
        $html = preg_replace('/class="container[^"]*"/', 'class="container mx-auto px-4"', $html);

        // Convert some styles to Bootstrap/Tailwind classes
        $html = str_replace('section-title', 'text-center mb-8', $html);
        $html = str_replace('news-grid', 'grid grid-cols-1 md:grid-cols-3 gap-6', $html);
        $html = str_replace('program-grid', 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6', $html);

        return trim($html);
    }

    protected function extractJavaScript($htmlContent)
    {
        if (preg_match('/<script[^>]*>(.*?)<\/script>/s', $htmlContent, $matches)) {
            return $matches[1];
        }
        return '';
    }
}
