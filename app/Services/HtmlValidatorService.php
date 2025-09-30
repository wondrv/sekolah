<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Log;

class HtmlValidatorService
{
    protected array $validationRules = [
        'duplicate_ids' => true,
        'missing_alt_attributes' => true,
        'heading_structure' => true,
        'form_labels' => true,
        'semantic_structure' => true,
        'link_validation' => true,
        'meta_tags' => true,
        'html5_validation' => true,
    ];

    protected array $errors = [];
    protected array $warnings = [];
    protected array $suggestions = [];

    /**
     * Validate HTML content
     */
    public function validateHtml(string $html, array $options = []): array
    {
        $this->resetResults();
        $this->validationRules = array_merge($this->validationRules, $options);

        // Create DOM document
        $dom = new DOMDocument();
        $previousSetting = libxml_use_internal_errors(true);

        try {
            // Load HTML with UTF-8 encoding
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
            $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $xpath = new DOMXPath($dom);

            // Run validation checks
            if ($this->validationRules['duplicate_ids']) {
                $this->checkDuplicateIds($xpath);
            }

            if ($this->validationRules['missing_alt_attributes']) {
                $this->checkImageAltAttributes($xpath);
            }

            if ($this->validationRules['heading_structure']) {
                $this->checkHeadingStructure($xpath);
            }

            if ($this->validationRules['form_labels']) {
                $this->checkFormLabels($xpath);
            }

            if ($this->validationRules['semantic_structure']) {
                $this->checkSemanticStructure($xpath);
            }

            if ($this->validationRules['link_validation']) {
                $this->checkLinkStructure($xpath);
            }

            if ($this->validationRules['meta_tags']) {
                $this->checkMetaTags($xpath);
            }

            if ($this->validationRules['html5_validation']) {
                $this->checkHtml5Elements($xpath);
            }

            // Check for common accessibility issues
            $this->checkAccessibility($xpath);

            // Check for performance issues
            $this->checkPerformance($xpath);

        } catch (\Exception $e) {
            $this->errors[] = [
                'type' => 'parsing_error',
                'message' => 'Error parsing HTML: ' . $e->getMessage(),
                'line' => null,
                'severity' => 'error'
            ];
        } finally {
            libxml_use_internal_errors($previousSetting);
        }

        return $this->getResults();
    }

    /**
     * Check for duplicate IDs
     */
    protected function checkDuplicateIds(DOMXPath $xpath): void
    {
        $ids = [];
        $elements = $xpath->query('//*[@id]');

        foreach ($elements as $element) {
            if (!$element instanceof \DOMElement) continue;

            $id = $element->getAttribute('id');

            if (empty($id)) {
                $this->warnings[] = [
                    'type' => 'empty_id',
                    'message' => 'Empty ID attribute found',
                    'element' => $element->nodeName,
                    'severity' => 'warning'
                ];
                continue;
            }

            if (isset($ids[$id])) {
                $this->errors[] = [
                    'type' => 'duplicate_id',
                    'message' => "Duplicate ID '{$id}' found",
                    'element' => $element->nodeName,
                    'id' => $id,
                    'severity' => 'error'
                ];
            } else {
                $ids[$id] = true;
            }

            // Check ID format
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*$/', $id)) {
                $this->warnings[] = [
                    'type' => 'invalid_id_format',
                    'message' => "ID '{$id}' should start with letter and contain only letters, numbers, hyphens, and underscores",
                    'element' => $element->nodeName,
                    'id' => $id,
                    'severity' => 'warning'
                ];
            }
        }
    }    /**
     * Check for missing alt attributes on images
     */
    protected function checkImageAltAttributes(DOMXPath $xpath): void
    {
        $images = $xpath->query('//img[not(@alt) or @alt=""]');

        foreach ($images as $img) {
            if (!$img instanceof \DOMElement) continue;

            $src = $img->getAttribute('src');
            $this->errors[] = [
                'type' => 'missing_alt',
                'message' => 'Image missing alt attribute for accessibility',
                'element' => 'img',
                'src' => $src,
                'severity' => 'error'
            ];
        }
    }

    /**
     * Check heading structure (h1-h6)
     */
    protected function checkHeadingStructure(DOMXPath $xpath): void
    {
        $headings = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');
        $levels = [];
        $hasH1 = false;

        foreach ($headings as $heading) {
            $level = (int) substr($heading->nodeName, 1);
            $levels[] = $level;

            if ($level === 1) {
                $hasH1 = true;
            }
        }

        if (!$hasH1 && !empty($levels)) {
            $this->warnings[] = [
                'type' => 'missing_h1',
                'message' => 'Page should have exactly one h1 element',
                'severity' => 'warning'
            ];
        }

        // Check for heading level skipping
        for ($i = 1; $i < count($levels); $i++) {
            if ($levels[$i] - $levels[$i-1] > 1) {
                $this->warnings[] = [
                    'type' => 'heading_skip',
                    'message' => "Heading levels should not skip (found h{$levels[$i-1]} followed by h{$levels[$i]})",
                    'severity' => 'warning'
                ];
            }
        }
    }

    /**
     * Check form labels
     */
    protected function checkFormLabels(DOMXPath $xpath): void
    {
        $inputs = $xpath->query('//input[@type!="hidden" and @type!="submit" and @type!="button"] | //textarea | //select');

        foreach ($inputs as $input) {
            if (!$input instanceof \DOMElement) continue;

            $id = $input->getAttribute('id');
            $hasLabel = false;

            if ($id) {
                $labels = $xpath->query("//label[@for='{$id}']");
                $hasLabel = $labels->length > 0;
            }

            // Check for parent label
            if (!$hasLabel) {
                $parentLabel = $xpath->query('ancestor::label', $input);
                $hasLabel = $parentLabel->length > 0;
            }

            if (!$hasLabel) {
                $this->errors[] = [
                    'type' => 'missing_label',
                    'message' => 'Form input missing associated label',
                    'element' => $input->nodeName,
                    'input_type' => $input->getAttribute('type') ?: 'text',
                    'severity' => 'error'
                ];
            }
        }
    }

    /**
     * Check semantic HTML structure
     */
    protected function checkSemanticStructure(DOMXPath $xpath): void
    {
        $semanticElements = ['header', 'nav', 'main', 'section', 'article', 'aside', 'footer'];
        $foundElements = [];

        foreach ($semanticElements as $element) {
            $elements = $xpath->query("//{$element}");
            if ($elements->length > 0) {
                $foundElements[] = $element;
            }
        }

        if (empty($foundElements)) {
            $this->suggestions[] = [
                'type' => 'no_semantic_elements',
                'message' => 'Consider using semantic HTML5 elements (header, nav, main, section, article, aside, footer) for better structure',
                'severity' => 'suggestion'
            ];
        }

        // Check for proper main element usage
        $mains = $xpath->query('//main');
        if ($mains->length > 1) {
            $this->errors[] = [
                'type' => 'multiple_main',
                'message' => 'Page should have only one main element',
                'severity' => 'error'
            ];
        }
    }

    /**
     * Check link structure
     */
    protected function checkLinkStructure(DOMXPath $xpath): void
    {
        $links = $xpath->query('//a[not(@href) or @href=""]');

        foreach ($links as $link) {
            $this->warnings[] = [
                'type' => 'empty_link',
                'message' => 'Link without href attribute',
                'element' => 'a',
                'severity' => 'warning'
            ];
        }

        // Check for links with only "#"
        $emptyLinks = $xpath->query('//a[@href="#"]');
        foreach ($emptyLinks as $link) {
            $this->warnings[] = [
                'type' => 'placeholder_link',
                'message' => 'Link with placeholder href="#" - should be updated with actual URL',
                'element' => 'a',
                'severity' => 'warning'
            ];
        }
    }

    /**
     * Check meta tags
     */
    protected function checkMetaTags(DOMXPath $xpath): void
    {
        $metaTags = [
            'description' => '//meta[@name="description"]',
            'viewport' => '//meta[@name="viewport"]',
            'charset' => '//meta[@charset] | //meta[@http-equiv="Content-Type"]'
        ];

        foreach ($metaTags as $name => $query) {
            $elements = $xpath->query($query);
            if ($elements->length === 0) {
                $this->suggestions[] = [
                    'type' => 'missing_meta',
                    'message' => "Consider adding meta {$name} tag for better SEO and user experience",
                    'severity' => 'suggestion'
                ];
            }
        }

        // Check title tag
        $titles = $xpath->query('//title');
        if ($titles->length === 0) {
            $this->errors[] = [
                'type' => 'missing_title',
                'message' => 'Page missing title element',
                'severity' => 'error'
            ];
        }
    }

    /**
     * Check HTML5 elements usage
     */
    protected function checkHtml5Elements(DOMXPath $xpath): void
    {
        $deprecatedElements = ['center', 'font', 'big', 'small', 'tt'];

        foreach ($deprecatedElements as $element) {
            $elements = $xpath->query("//{$element}");
            if ($elements->length > 0) {
                $this->warnings[] = [
                    'type' => 'deprecated_element',
                    'message' => "Deprecated HTML element '{$element}' found - consider using CSS for styling instead",
                    'element' => $element,
                    'severity' => 'warning'
                ];
            }
        }
    }

    /**
     * Check accessibility issues
     */
    protected function checkAccessibility(DOMXPath $xpath): void
    {
        // Check for missing lang attribute
        $html = $xpath->query('//html[not(@lang)]');
        if ($html->length > 0) {
            $this->warnings[] = [
                'type' => 'missing_lang',
                'message' => 'HTML element missing lang attribute for accessibility',
                'severity' => 'warning'
            ];
        }

        // Check for empty buttons
        $emptyButtons = $xpath->query('//button[not(text()) and not(*)]');
        foreach ($emptyButtons as $button) {
            $this->errors[] = [
                'type' => 'empty_button',
                'message' => 'Button element is empty - should contain text or aria-label',
                'severity' => 'error'
            ];
        }
    }

    /**
     * Check performance-related issues
     */
    protected function checkPerformance(DOMXPath $xpath): void
    {
        // Check for inline styles
        $inlineStyles = $xpath->query('//*[@style]');
        if ($inlineStyles->length > 5) {
            $this->suggestions[] = [
                'type' => 'too_many_inline_styles',
                'message' => 'Consider moving inline styles to CSS file for better performance and maintainability',
                'count' => $inlineStyles->length,
                'severity' => 'suggestion'
            ];
        }

        // Check for images without width/height
        $imagesWithoutDimensions = $xpath->query('//img[not(@width) and not(@height)]');
        if ($imagesWithoutDimensions->length > 0) {
            $this->suggestions[] = [
                'type' => 'images_without_dimensions',
                'message' => 'Consider adding width and height attributes to images to prevent layout shift',
                'count' => $imagesWithoutDimensions->length,
                'severity' => 'suggestion'
            ];
        }
    }

    /**
     * Reset validation results
     */
    protected function resetResults(): void
    {
        $this->errors = [];
        $this->warnings = [];
        $this->suggestions = [];
    }

    /**
     * Get validation results
     */
    protected function getResults(): array
    {
        $totalIssues = count($this->errors) + count($this->warnings) + count($this->suggestions);

        return [
            'valid' => count($this->errors) === 0,
            'score' => $this->calculateScore(),
            'summary' => [
                'errors' => count($this->errors),
                'warnings' => count($this->warnings),
                'suggestions' => count($this->suggestions),
                'total_issues' => $totalIssues,
            ],
            'issues' => [
                'errors' => $this->errors,
                'warnings' => $this->warnings,
                'suggestions' => $this->suggestions,
            ],
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Calculate validation score (0-100)
     */
    protected function calculateScore(): int
    {
        $errorWeight = 10;
        $warningWeight = 5;
        $suggestionWeight = 1;

        $deductions = (count($this->errors) * $errorWeight) +
                     (count($this->warnings) * $warningWeight) +
                     (count($this->suggestions) * $suggestionWeight);

        $score = max(0, 100 - $deductions);

        return $score;
    }

    /**
     * Validate specific page or template
     */
    public function validatePage(string $url): array
    {
        try {
            // Use cURL for better reliability
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'HTML Validator Bot/1.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For development
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // For development

            $html = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($html === false || !empty($error)) {
                throw new \Exception('cURL error: ' . $error);
            }

            if ($httpCode >= 400) {
                throw new \Exception('HTTP error: ' . $httpCode);
            }

            if (empty($html)) {
                throw new \Exception('Empty response from URL');
            }

            return $this->validateHtml($html);

        } catch (\Exception $e) {
            return [
                'valid' => false,
                'score' => 0,
                'summary' => [
                    'errors' => 1,
                    'warnings' => 0,
                    'suggestions' => 0,
                    'total_issues' => 1,
                ],
                'issues' => [
                    'errors' => [[
                        'type' => 'fetch_error',
                        'message' => 'Could not fetch page: ' . $e->getMessage(),
                        'severity' => 'error'
                    ]],
                    'warnings' => [],
                    'suggestions' => [],
                ],
                'timestamp' => now()->toISOString(),
            ];
        }
    }
}
