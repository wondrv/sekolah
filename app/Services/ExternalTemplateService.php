<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\TemplateGallery;
use App\Models\TemplateCategory;

class ExternalTemplateService
{
    protected array $sources = [
        'github_school_templates' => [
            'name' => 'GitHub School Templates',
            'url' => 'https://api.github.com/search/repositories',
            'query' => 'school+template+website+in:name,description+language:HTML',
            'type' => 'github_repos'
        ],
        'github_education_themes' => [
            'name' => 'Education Themes',
            'url' => 'https://api.github.com/search/repositories',
            'query' => 'education+theme+template+school+university+in:name,description',
            'type' => 'github_repos'
        ],
        'free_css_school' => [
            'name' => 'Free CSS School Templates',
            'url' => 'https://www.free-css.com/assets/files/free-css-templates/preview/',
            'type' => 'scrape_css'
        ]
    ];

    public function discoverTemplates(string $source = 'all', int $limit = 20): array
    {
        $cacheKey = "external_templates_{$source}_" . md5($source . $limit);

        return Cache::remember($cacheKey, 3600, function () use ($source, $limit) {
            $templates = [];

            if ($source === 'all') {
                foreach ($this->sources as $sourceKey => $config) {
                    $templates = array_merge($templates, $this->fetchFromSource($sourceKey, $config, $limit));
                }
            } else {
                $config = $this->sources[$source] ?? null;
                if ($config) {
                    $templates = $this->fetchFromSource($source, $config, $limit);
                }
            }

            return $this->processTemplates($templates);
        });
    }

    protected function fetchFromSource(string $sourceKey, array $config, int $limit): array
    {
        try {
            switch ($config['type']) {
                case 'github_repos':
                    return $this->fetchGitHubTemplates($sourceKey, $config, $limit);
                case 'scrape_css':
                    return $this->fetchFreeCSSTemplates($sourceKey, $config, $limit);
                default:
                    return [];
            }
        } catch (\Exception $e) {
            Log::warning("Failed to fetch from {$sourceKey}: " . $e->getMessage());
            return [];
        }
    }

    protected function fetchGitHubTemplates(string $sourceKey, array $config, int $limit): array
    {
        $response = Http::timeout(10)->get($config['url'], [
            'q' => $config['query'],
            'sort' => 'stars',
            'order' => 'desc',
            'per_page' => $limit
        ]);

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        $templates = [];

        foreach ($data['items'] ?? [] as $repo) {
            if ($this->isSchoolTemplate($repo)) {
                $template = $this->convertGitHubRepoToTemplate($repo);
                $template['source_type'] = $sourceKey;
                $templates[] = $template;
            }
        }

        return $templates;
    }

    protected function fetchFreeCSSTemplates(string $sourceKey, array $config, int $limit): array
    {
        // Simplified mock for free CSS templates
        return [
            [
                'external_id' => 'freecss_educare_' . time(),
                'name' => 'EduCare - School Template',
                'description' => 'Modern school website template with responsive design',
                'source_url' => 'https://example.com/educare-template',
                'preview_image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600',
                'author' => 'Free-CSS',
                'features' => ['Responsive', 'Modern Design', 'School Features'],
                'rating' => 4.5,
                'source_type' => $sourceKey
            ]
        ];
    }

    protected function isSchoolTemplate(array $repo): bool
    {
        $keywords = ['school', 'education', 'university', 'college', 'academic', 'student', 'learning'];
        $text = strtolower($repo['name'] . ' ' . ($repo['description'] ?? ''));

        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }
        return false;
    }

    protected function convertGitHubRepoToTemplate(array $repo): array
    {
        return [
            'external_id' => 'github_' . $repo['id'],
            'name' => $repo['name'],
            'description' => $repo['description'] ?? 'School website template from GitHub',
            'source_url' => $repo['html_url'],
            'clone_url' => $repo['clone_url'],
            'preview_image' => $this->getGitHubPreviewImage($repo),
            'author' => $repo['owner']['login'] ?? 'GitHub User',
            'stars' => $repo['stargazers_count'] ?? 0,
            'language' => $repo['language'],
            'updated_at' => $repo['updated_at'],
            'features' => $this->extractFeaturesFromRepo($repo),
            'rating' => min(5.0, ($repo['stargazers_count'] ?? 0) / 10), // Convert stars to rating
            'source_type' => 'github'
        ];
    }

    protected function getGitHubPreviewImage(array $repo): string
    {
        // Use repository owner's avatar or a placeholder
        return $repo['owner']['avatar_url'] ?? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600';
    }

    protected function extractFeaturesFromRepo(array $repo): array
    {
        $features = ['External Template'];

        if ($repo['language']) {
            $features[] = $repo['language'];
        }

        $description = strtolower($repo['description'] ?? '');

        if (str_contains($description, 'responsive')) $features[] = 'Responsive';
        if (str_contains($description, 'bootstrap')) $features[] = 'Bootstrap';
        if (str_contains($description, 'tailwind')) $features[] = 'Tailwind CSS';
        if (str_contains($description, 'react')) $features[] = 'React';
        if (str_contains($description, 'vue')) $features[] = 'Vue.js';

        return array_unique($features);
    }

    protected function processTemplates(array $templates): array
    {
        return collect($templates)
            ->filter(function ($template) {
                return !empty($template['name']) && !empty($template['description']);
            })
            ->map(function ($template) {
                // Debug: Let's see what fields are in the template
                if (!isset($template['source_type'])) {
                    Log::debug('Template missing source_type', ['template_keys' => array_keys($template)]);
                }

                // Ensure required fields have defaults first
                $processed = [
                    'external_id' => $template['external_id'] ?? 'ext_' . time() . '_' . rand(1000, 9999),
                    'source_type' => $template['source_type'] ?? 'external',
                    'author' => $template['author'] ?? 'External Author',
                    'features' => $template['features'] ?? ['External Template'],
                    'rating' => $template['rating'] ?? 4.0,
                    'preview_image' => $template['preview_image'] ?? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600',
                    // Additional fields
                    'is_external' => true,
                    'install_method' => $this->determineInstallMethod($template),
                    'preview_available' => true,
                    'converted_template_data' => $this->convertToTemplateData($template)
                ];

                return array_merge($template, $processed);
            })
            ->take(50) // Limit total results
            ->toArray();
    }

    protected function determineInstallMethod(array $template): string
    {
        if (isset($template['source_type'])) {
            switch ($template['source_type']) {
                case 'github':
                    return 'clone_and_convert';
                case 'free_css':
                    return 'download_and_convert';
                default:
                    return 'manual';
            }
        }
        return 'manual';
    }

    protected function convertToTemplateData(array $template): array
    {
        // Convert external template to our CMS template_data format
        return [
            'templates' => [
                [
                    'name' => $template['name'],
                    'slug' => 'external-' . time(),
                    'description' => $template['description'],
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
                                        'title' => $template['name'],
                                        'subtitle' => $template['description'],
                                        'background_color' => 'bg-gradient-to-r from-blue-600 to-blue-800'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Content Section',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'External Template Info',
                                    'order' => 1,
                                    'content' => [
                                        'text' => '<p>This template was imported from an external source. Features: ' . implode(', ', $template['features'] ?? []) . '</p>'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function installExternalTemplate(array $template, int $userId): ?TemplateGallery
    {
        try {
            // Find or create "External" category
            $category = TemplateCategory::firstOrCreate(
                ['slug' => 'external'],
                [
                    'name' => 'External Templates',
                    'description' => 'Templates imported from external sources',
                    'color' => '#10B981',
                    'sort_order' => 999
                ]
            );

            // Create gallery template entry
            $galleryTemplate = TemplateGallery::create([
                'name' => $template['name'],
                'slug' => 'ext-' . time() . '-' . \Illuminate\Support\Str::random(6),
                'description' => $template['description'],
                'category_id' => $category->id,
                'preview_image' => $template['preview_image'] ?? null,
                'template_data' => $template['converted_template_data'],
                'author' => $template['author'] ?? 'External',
                'version' => '1.0.0',
                'features' => $template['features'] ?? [],
                'rating' => $template['rating'] ?? 4.0,
                'featured' => false,
                'premium' => false,
                'active' => true
            ]);

            // Auto-create user template
            $userTemplate = $galleryTemplate->createUserTemplate($userId, [
                'source_url' => $template['source_url'] ?? null,
                'install_method' => $template['install_method'] ?? 'external',
                'external_id' => $template['external_id'] ?? null
            ]);

            return $galleryTemplate;

        } catch (\Exception $e) {
            Log::error("Failed to install external template: " . $e->getMessage());
            return null;
        }
    }
}
