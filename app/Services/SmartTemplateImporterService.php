<?php

namespace App\Services;

use App\Models\UserTemplate;
use App\Models\TemplateGallery;
use App\Models\TemplateCategory;
use App\Services\LanguageDetectionService;
use App\Services\AutoTranslationService;
use App\Services\HtmlValidatorService;
use App\Services\PreviewImageService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use DOMDocument;
use DOMXPath;

class SmartTemplateImporterService
{
    protected LanguageDetectionService $languageDetector;
    protected AutoTranslationService $translator;
    protected HtmlValidatorService $htmlValidator;
    protected PreviewImageService $previewService;

    public function __construct(
        LanguageDetectionService $languageDetector,
        AutoTranslationService $translator,
        HtmlValidatorService $htmlValidator,
        PreviewImageService $previewService
    ) {
        $this->languageDetector = $languageDetector;
        $this->translator = $translator;
        $this->htmlValidator = $htmlValidator;
        $this->previewService = $previewService;
    }

    /**
     * Import template from URL with automatic language detection and translation
     */
    public function importFromUrl(string $url, int $userId, array $options = []): array
    {
        try {
            Log::info('Starting smart template import', ['url' => $url, 'user_id' => $userId]);

            // Step 1: Fetch and analyze the template
            $analysis = $this->analyzeTemplate($url, $options);
            if (!$analysis['success']) {
                return $analysis;
            }

            // Step 2: Convert HTML to our CMS format
            $conversion = $this->convertToTemplateData($analysis['content'], $analysis);
            if (!$conversion['success']) {
                return $conversion;
            }

            // Step 3: Detect language and translate if needed
            $localization = $this->localizeTemplate($conversion['template_data'], $analysis);

            // Step 4: Create preview image
            $preview = $this->generatePreview($url, $analysis);

            // Step 5: Create template in database
            $creation = $this->createUserTemplate($userId, [
                'name' => $analysis['meta']['title'] ?? 'Imported Template',
                'description' => $analysis['meta']['description'] ?? 'Auto-imported template',
                'template_data' => $localization['template_data'],
                'preview_image' => $preview['image_url'] ?? null,
                'source' => 'imported',
                'source_url' => $url,
                'metadata' => [
                    'import_analysis' => $analysis,
                    'conversion_stats' => $conversion['stats'],
                    'localization_stats' => $localization['stats'],
                    'imported_at' => now()->toISOString(),
                    'auto_activated' => $options['auto_activate'] ?? false
                ]
            ]);

            if (!$creation['success']) {
                return $creation;
            }

            // Step 6: Auto-activate if requested
            if ($options['auto_activate'] ?? false) {
                $activation = $this->activateTemplate($creation['template'], $userId);
                $creation['activation'] = $activation;
            }

            return [
                'success' => true,
                'template' => $creation['template'],
                'stats' => [
                    'analysis' => $analysis['stats'],
                    'conversion' => $conversion['stats'],
                    'localization' => $localization['stats'],
                    'preview' => $preview['stats'] ?? null
                ],
                'message' => 'Template imported successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Template import failed', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Import failed: ' . $e->getMessage(),
                'code' => 'IMPORT_ERROR'
            ];
        }
    }

    /**
     * Analyze template from URL - public method for controller access
     */
    public function analyzeTemplate(string $url, array $options = []): array
    {
        try {
            // Fetch the HTML content
            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'error' => 'Failed to fetch template from URL',
                    'code' => 'FETCH_ERROR'
                ];
            }

            $html = $response->body();

            // Parse HTML
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            libxml_clear_errors();

            $xpath = new DOMXPath($dom);

            // Extract metadata
            $meta = $this->extractMetadata($dom, $xpath);

            // Extract text content for language detection
            $textContent = $this->extractTextContent($dom);

            // Detect language
            $languageDetection = $this->languageDetector->detectLanguage($textContent, true);

            // Analyze structure
            $structure = $this->analyzeHtmlStructure($dom, $xpath);

            // Extract CSS and assets
            $assets = $this->extractAssets($dom, $xpath, $url);

            return [
                'success' => true,
                'content' => $html,
                'meta' => $meta,
                'language' => $languageDetection,
                'structure' => $structure,
                'assets' => $assets,
                'stats' => [
                    'html_size' => strlen($html),
                    'text_length' => strlen($textContent),
                    'elements_count' => $structure['total_elements'],
                    'assets_count' => count($assets['all'])
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Template analysis failed: ' . $e->getMessage(),
                'code' => 'ANALYSIS_ERROR'
            ];
        }
    }

    /**
     * Extract metadata from HTML
     */
    protected function extractMetadata(DOMDocument $dom, DOMXPath $xpath): array
    {
        $meta = [];

        // Title
        $titleNodes = $xpath->query('//title');
        $meta['title'] = $titleNodes->length > 0 ? trim($titleNodes->item(0)->textContent) : null;

        // Meta description
        $descNodes = $xpath->query('//meta[@name="description"]/@content');
        $meta['description'] = $descNodes->length > 0 ? trim($descNodes->item(0)->nodeValue) : null;

        // Meta keywords
        $keywordNodes = $xpath->query('//meta[@name="keywords"]/@content');
        $meta['keywords'] = $keywordNodes->length > 0 ? trim($keywordNodes->item(0)->nodeValue) : null;

        // Open Graph data
        $meta['og'] = [];
        $ogNodes = $xpath->query('//meta[starts-with(@property, "og:")]');
        foreach ($ogNodes as $node) {
            if ($node instanceof \DOMElement) {
                $property = $node->getAttribute('property');
                $content = $node->getAttribute('content');
                $meta['og'][str_replace('og:', '', $property)] = $content;
            }
        }

        // Viewport
        $viewportNodes = $xpath->query('//meta[@name="viewport"]/@content');
        $meta['viewport'] = $viewportNodes->length > 0 ? trim($viewportNodes->item(0)->nodeValue) : null;

        return $meta;
    }

    /**
     * Extract text content for language detection
     */
    protected function extractTextContent(DOMDocument $dom): string
    {
        // Remove script and style elements
        $xpath = new DOMXPath($dom);
        $scripts = $xpath->query('//script | //style');
        foreach ($scripts as $script) {
            $script->parentNode->removeChild($script);
        }

        // Get body text
        $body = $dom->getElementsByTagName('body')->item(0);
        $textContent = $body ? $body->textContent : $dom->textContent;

        // Clean up whitespace
        $textContent = preg_replace('/\s+/', ' ', $textContent);
        return trim($textContent);
    }

    /**
     * Analyze HTML structure
     */
    protected function analyzeHtmlStructure(DOMDocument $dom, DOMXPath $xpath): array
    {
        $structure = [
            'has_header' => false,
            'has_nav' => false,
            'has_main' => false,
            'has_footer' => false,
            'has_sidebar' => false,
            'sections' => [],
            'total_elements' => 0,
            'framework' => 'unknown'
        ];

        // Detect semantic elements
        $structure['has_header'] = $xpath->query('//header')->length > 0;
        $structure['has_nav'] = $xpath->query('//nav')->length > 0;
        $structure['has_main'] = $xpath->query('//main')->length > 0;
        $structure['has_footer'] = $xpath->query('//footer')->length > 0;
        $structure['has_sidebar'] = $xpath->query('//aside | //*[contains(@class, "sidebar")]')->length > 0;

        // Count total elements
        $structure['total_elements'] = $xpath->query('//*')->length;

        // Detect framework
        $htmlContent = $dom->saveHTML();
        if (strpos($htmlContent, 'bootstrap') !== false) {
            $structure['framework'] = 'bootstrap';
        } elseif (strpos($htmlContent, 'tailwind') !== false) {
            $structure['framework'] = 'tailwind';
        } elseif (strpos($htmlContent, 'foundation') !== false) {
            $structure['framework'] = 'foundation';
        }

        // Analyze sections
        $sections = $xpath->query('//section | //div[contains(@class, "section")] | //div[contains(@id, "section")]');
        foreach ($sections as $section) {
            if ($section instanceof \DOMElement) {
                $sectionData = [
                    'tag' => $section->tagName,
                    'classes' => $section->getAttribute('class'),
                    'id' => $section->getAttribute('id'),
                    'text_length' => strlen($section->textContent)
                ];
                $structure['sections'][] = $sectionData;
            }
        }

        return $structure;
    }

    /**
     * Extract assets (CSS, JS, images)
     */
    protected function extractAssets(DOMDocument $dom, DOMXPath $xpath, string $baseUrl): array
    {
        $assets = [
            'css' => [],
            'js' => [],
            'images' => [],
            'fonts' => [],
            'all' => []
        ];

        // CSS files
        $cssNodes = $xpath->query('//link[@rel="stylesheet"]/@href');
        foreach ($cssNodes as $node) {
            $href = $this->resolveUrl($node->nodeValue, $baseUrl);
            $assets['css'][] = $href;
            $assets['all'][] = ['type' => 'css', 'url' => $href];
        }

        // JavaScript files
        $jsNodes = $xpath->query('//script[@src]/@src');
        foreach ($jsNodes as $node) {
            $src = $this->resolveUrl($node->nodeValue, $baseUrl);
            $assets['js'][] = $src;
            $assets['all'][] = ['type' => 'js', 'url' => $src];
        }

        // Images
        $imgNodes = $xpath->query('//img/@src');
        foreach ($imgNodes as $node) {
            $src = $this->resolveUrl($node->nodeValue, $baseUrl);
            $assets['images'][] = $src;
            $assets['all'][] = ['type' => 'image', 'url' => $src];
        }

        return $assets;
    }

    /**
     * Convert HTML to our template data format
     */
    protected function convertToTemplateData(string $html, array $analysis): array
    {
        try {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            libxml_clear_errors();

            $xpath = new DOMXPath($dom);

            // Extract main sections
            $sections = $this->extractSections($dom, $xpath, $analysis);

            $templateData = [
                'templates' => [
                    [
                        'name' => $analysis['meta']['title'] ?? 'Imported Template',
                        'slug' => 'imported-' . time(),
                        'description' => $analysis['meta']['description'] ?? 'Auto-imported template',
                        'active' => true,
                        'type' => 'page',
                        'sections' => $sections,
                        'metadata' => [
                            'framework' => $analysis['structure']['framework'],
                            'original_url' => $analysis['url'] ?? null,
                            'import_method' => 'smart_import'
                        ]
                    ]
                ]
            ];

            return [
                'success' => true,
                'template_data' => $templateData,
                'stats' => [
                    'sections_created' => count($sections),
                    'blocks_created' => array_sum(array_map(fn($s) => count($s['blocks']), $sections))
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'HTML conversion failed: ' . $e->getMessage(),
                'code' => 'CONVERSION_ERROR'
            ];
        }
    }

    /**
     * Extract sections from HTML
     */
    protected function extractSections(DOMDocument $dom, DOMXPath $xpath, array $analysis): array
    {
        $sections = [];
        $sectionOrder = 1;

        // Header section
        $header = $xpath->query('//header')->item(0);
        if ($header) {
            $sections[] = $this->createSectionFromElement($header, 'Header', $sectionOrder++, $xpath);
        }

        // Navigation section
        $nav = $xpath->query('//nav')->item(0);
        if ($nav && (!$header || !$this->isChildOf($nav, $header))) {
            $sections[] = $this->createSectionFromElement($nav, 'Navigation', $sectionOrder++, $xpath);
        }

        // Main content sections
        $mainSections = $xpath->query('//main//section | //section | //div[contains(@class, "section")]');
        foreach ($mainSections as $section) {
            $sectionName = $this->guessSectionName($section);
            $sections[] = $this->createSectionFromElement($section, $sectionName, $sectionOrder++, $xpath);
        }

        // If no explicit sections found, create from main content areas
        if (count($sections) <= 2) {
            $contentAreas = $xpath->query('//main | //div[contains(@class, "content")] | //div[contains(@class, "main")]');
            foreach ($contentAreas as $area) {
                $sections[] = $this->createSectionFromElement($area, 'Main Content', $sectionOrder++, $xpath);
            }
        }

        // Footer section
        $footer = $xpath->query('//footer')->item(0);
        if ($footer) {
            $sections[] = $this->createSectionFromElement($footer, 'Footer', $sectionOrder++, $xpath, ['is_footer' => true]);
        }

        return $sections;
    }

    /**
     * Create section from DOM element
     */
    protected function createSectionFromElement(\DOMElement $element, string $name, int $order, DOMXPath $xpath, array $settings = []): array
    {
        $blocks = $this->extractBlocksFromElement($element, $xpath);

        return [
            'name' => $name,
            'order' => $order,
            'settings' => $settings,
            'blocks' => $blocks
        ];
    }

    /**
     * Extract blocks from DOM element
     */
    protected function extractBlocksFromElement(\DOMElement $element, DOMXPath $xpath): array
    {
        $blocks = [];
        $blockOrder = 1;

        // Look for common content patterns
        $contentElements = $xpath->query('.//h1 | .//h2 | .//h3 | .//p | .//div[contains(@class, "hero")] | .//div[contains(@class, "card")] | .//ul | .//img', $element);

        $currentBlock = null;
        $currentBlockContent = [];

        foreach ($contentElements as $el) {
            $blockType = $this->guessBlockType($el, $xpath);

            if ($blockType && ($currentBlock === null || $currentBlock !== $blockType)) {
                // Save previous block if exists
                if ($currentBlock && !empty($currentBlockContent)) {
                    $blocks[] = $this->createBlock($currentBlock, $currentBlockContent, $blockOrder++);
                    $currentBlockContent = [];
                }
                $currentBlock = $blockType;
            }

            // Add content to current block
            $this->addElementToBlockContent($el, $currentBlockContent, $xpath);
        }

        // Save final block
        if ($currentBlock && !empty($currentBlockContent)) {
            $blocks[] = $this->createBlock($currentBlock, $currentBlockContent, $blockOrder++);
        }

        // If no blocks created, create a rich text block with all content
        if (empty($blocks)) {
            $blocks[] = [
                'type' => 'rich_text',
                'name' => 'Content',
                'order' => 1,
                'content' => [
                    'text' => $this->cleanHtmlContent($element->ownerDocument->saveHTML($element))
                ]
            ];
        }

        return $blocks;
    }

    /**
     * Guess block type from element
     */
    protected function guessBlockType(\DOMElement $element, DOMXPath $xpath): ?string
    {
        $tagName = strtolower($element->tagName);
        $className = strtolower($element->getAttribute('class'));

        // Hero section
        if (strpos($className, 'hero') !== false || strpos($className, 'banner') !== false) {
            return 'hero';
        }

        // Card grids
        if (strpos($className, 'card') !== false || strpos($className, 'grid') !== false) {
            return 'card_grid';
        }

        // Gallery
        if (strpos($className, 'gallery') !== false || strpos($className, 'image') !== false) {
            return 'gallery_teaser';
        }

        // CTA
        if (strpos($className, 'cta') !== false || strpos($className, 'call-to-action') !== false) {
            return 'cta_banner';
        }

        // Stats
        if (strpos($className, 'stat') !== false || strpos($className, 'number') !== false) {
            return 'stats';
        }

        // Headings and paragraphs default to rich text
        if (in_array($tagName, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'])) {
            return 'rich_text';
        }

        return null;
    }

    /**
     * Create block from content
     */
    protected function createBlock(string $type, array $content, int $order): array
    {
        $block = [
            'type' => $type,
            'name' => ucfirst(str_replace('_', ' ', $type)),
            'order' => $order,
            'content' => [],
            'active' => true
        ];

        switch ($type) {
            case 'hero':
                $block['content'] = [
                    'title' => $content['title'] ?? 'Welcome',
                    'subtitle' => $content['subtitle'] ?? 'Your educational journey starts here',
                    'background_color' => 'bg-blue-600'
                ];
                break;

            case 'card_grid':
                $block['content'] = [
                    'title' => $content['title'] ?? 'Our Services',
                    'cards' => $content['cards'] ?? []
                ];
                break;

            case 'rich_text':
                $block['content'] = [
                    'text' => $content['html'] ?? implode("\n", $content['text'] ?? [])
                ];
                break;

            default:
                $block['content'] = $content;
        }

        return $block;
    }

    /**
     * Add element content to block
     */
    protected function addElementToBlockContent(\DOMElement $element, array &$content, DOMXPath $xpath): void
    {
        $tagName = strtolower($element->tagName);
        $text = trim($element->textContent);

        if (empty($text)) return;

        switch ($tagName) {
            case 'h1':
            case 'h2':
            case 'h3':
                $content['title'] = $text;
                break;

            case 'p':
                if (!isset($content['text'])) $content['text'] = [];
                $content['text'][] = $text;
                break;

            default:
                if (!isset($content['html'])) $content['html'] = '';
                $content['html'] .= $element->ownerDocument->saveHTML($element);
        }
    }

    /**
     * Localize template (detect language and translate)
     */
    protected function localizeTemplate(array $templateData, array $analysis): array
    {
        $sourceLanguage = $analysis['language']['primary_language'];
        $needsTranslation = $this->languageDetector->needsTranslation($sourceLanguage);

        if (!$needsTranslation) {
            return [
                'success' => true,
                'template_data' => $templateData,
                'stats' => [
                    'translation_needed' => false,
                    'source_language' => $sourceLanguage
                ]
            ];
        }

        // Extract translatable content
        $translatableContent = $this->languageDetector->extractTranslatableContent($templateData);

        // Translate content
        $translationResult = $this->translator->translateTemplateContent($translatableContent, $sourceLanguage);

        // Apply translations
        $localizedData = $this->languageDetector->applyTranslations(
            $templateData,
            $translationResult['translated_content']
        );

        return [
            'success' => true,
            'template_data' => $localizedData,
            'stats' => array_merge([
                'translation_needed' => true,
                'source_language' => $sourceLanguage,
                'target_language' => 'id'
            ], $translationResult['stats'])
        ];
    }

    /**
     * Generate preview image
     */
    protected function generatePreview(string $url, array $analysis): array
    {
        try {
            return $this->previewService->generateFromUrl($url);
        } catch (\Exception $e) {
            Log::warning('Preview generation failed', ['url' => $url, 'error' => $e->getMessage()]);
            return [
                'success' => false,
                'image_url' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create user template in database
     */
    protected function createUserTemplate(int $userId, array $data): array
    {
        try {
            $template = UserTemplate::create([
                'user_id' => $userId,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . time(),
                'description' => $data['description'],
                'template_data' => $data['template_data'],
                'preview_image' => $data['preview_image'],
                'source' => $data['source'],
                'is_active' => false,
                'customizations' => $data['metadata'] ?? []
            ]);

            return [
                'success' => true,
                'template' => $template
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to create template: ' . $e->getMessage(),
                'code' => 'CREATE_ERROR'
            ];
        }
    }

    /**
     * Activate template for immediate use
     */
    protected function activateTemplate(UserTemplate $template, int $userId): array
    {
        try {
            $template->activate();

            return [
                'success' => true,
                'message' => 'Template activated successfully'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to activate template: ' . $e->getMessage(),
                'code' => 'ACTIVATION_ERROR'
            ];
        }
    }

    /**
     * Helper methods
     */
    protected function resolveUrl(string $url, string $baseUrl): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $parsedBase = parse_url($baseUrl);
        $scheme = $parsedBase['scheme'] ?? 'https';
        $host = $parsedBase['host'] ?? '';

        if (strpos($url, '//') === 0) {
            return $scheme . ':' . $url;
        }

        if (strpos($url, '/') === 0) {
            return $scheme . '://' . $host . $url;
        }

        return $scheme . '://' . $host . '/' . ltrim($url, '/');
    }

    protected function guessSectionName(\DOMElement $element): string
    {
        $className = strtolower($element->getAttribute('class'));
        $id = strtolower($element->getAttribute('id'));

        if (strpos($className . $id, 'hero') !== false) return 'Hero Section';
        if (strpos($className . $id, 'about') !== false) return 'About Section';
        if (strpos($className . $id, 'service') !== false) return 'Services Section';
        if (strpos($className . $id, 'feature') !== false) return 'Features Section';
        if (strpos($className . $id, 'contact') !== false) return 'Contact Section';
        if (strpos($className . $id, 'testimonial') !== false) return 'Testimonials Section';

        return 'Content Section';
    }

    protected function isChildOf(\DOMElement $child, \DOMElement $parent): bool
    {
        $current = $child->parentNode;
        while ($current) {
            if ($current === $parent) return true;
            $current = $current->parentNode;
        }
        return false;
    }

    protected function cleanHtmlContent(string $html): string
    {
        // Remove script and style tags
        $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $html);
        $html = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi', '', $html);

        // Clean up whitespace
        $html = preg_replace('/\s+/', ' ', $html);

        return trim($html);
    }
}
