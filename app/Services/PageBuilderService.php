<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class PageBuilderService
{
    protected array $registeredBlocks = [];

    public function __construct()
    {
        $this->registerDefaultBlocks();
    }

    /**
     * Register default blocks
     */
    protected function registerDefaultBlocks(): void
    {
        $this->registeredBlocks = [
            'hero' => [
                'name' => 'Hero Section',
                'category' => 'layout',
                'icon' => 'fas fa-image',
                'view' => 'blocks.hero',
                'settings' => [
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'subtitle' => ['type' => 'text', 'label' => 'Subtitle'],
                    'description' => ['type' => 'textarea', 'label' => 'Description'],
                    'image' => ['type' => 'image', 'label' => 'Background Image'],
                    'button_text' => ['type' => 'text', 'label' => 'Button Text'],
                    'button_url' => ['type' => 'url', 'label' => 'Button URL'],
                    'alignment' => ['type' => 'select', 'label' => 'Text Alignment', 'options' => ['left', 'center', 'right']]
                ]
            ],
            'rich_text' => [
                'name' => 'Rich Text',
                'category' => 'content',
                'icon' => 'fas fa-align-left',
                'view' => 'blocks.rich-text',
                'settings' => [
                    'content' => ['type' => 'editor', 'label' => 'Content']
                ]
            ],
            'gallery' => [
                'name' => 'Gallery',
                'category' => 'media',
                'icon' => 'fas fa-images',
                'view' => 'blocks.gallery',
                'settings' => [
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'gallery_id' => ['type' => 'gallery_select', 'label' => 'Select Gallery'],
                    'columns' => ['type' => 'select', 'label' => 'Columns', 'options' => [2, 3, 4, 6]],
                    'show_captions' => ['type' => 'checkbox', 'label' => 'Show Captions']
                ]
            ],
            'contact_form' => [
                'name' => 'Contact Form',
                'category' => 'forms',
                'icon' => 'fas fa-envelope',
                'view' => 'blocks.contact-form',
                'settings' => [
                    'title' => ['type' => 'text', 'label' => 'Form Title'],
                    'fields' => ['type' => 'repeater', 'label' => 'Form Fields'],
                    'submit_text' => ['type' => 'text', 'label' => 'Submit Button Text'],
                    'success_message' => ['type' => 'text', 'label' => 'Success Message']
                ]
            ],
            'stats' => [
                'name' => 'Statistics',
                'category' => 'content',
                'icon' => 'fas fa-chart-bar',
                'view' => 'blocks.stats',
                'settings' => [
                    'title' => ['type' => 'text', 'label' => 'Section Title'],
                    'stats' => ['type' => 'repeater', 'label' => 'Statistics']
                ]
            ],
            // PPDB Blocks
            'ppdb-brochure' => [
                'name' => 'PPDB Brochure',
                'category' => 'ppdb',
                'icon' => 'fas fa-file-download',
                'view' => 'components.blocks.ppdb-brochure',
                'settings' => [
                    'enabled' => ['type' => 'checkbox', 'label' => 'Enable Brochure Section', 'default' => true],
                    'title' => ['type' => 'text', 'label' => 'Section Title'],
                    'description' => ['type' => 'textarea', 'label' => 'Description'],
                    'file' => ['type' => 'file', 'label' => 'Brochure File (PDF)'],
                    'size' => ['type' => 'text', 'label' => 'File Size Display'],
                    'format' => ['type' => 'text', 'label' => 'File Format', 'default' => 'PDF'],
                ]
            ],
            'ppdb-cost-table' => [
                'name' => 'PPDB Cost Table',
                'category' => 'ppdb',
                'icon' => 'fas fa-money-bill-wave',
                'view' => 'components.blocks.ppdb-cost-table',
                'settings' => [
                    'enabled' => ['type' => 'checkbox', 'label' => 'Enable Cost Table', 'default' => true],
                    'title' => ['type' => 'text', 'label' => 'Section Title'],
                    'description' => ['type' => 'textarea', 'label' => 'Description'],
                    'academic_year' => ['type' => 'text', 'label' => 'Academic Year'],
                    'show_total' => ['type' => 'checkbox', 'label' => 'Show Total Mandatory Costs', 'default' => true],
                ]
            ],
            // Event Blocks
            'event_list' => [
                'name' => 'Event List',
                'category' => 'content',
                'icon' => 'fas fa-calendar-alt',
                'view' => 'blocks.event-list',
                'settings' => [
                    'title' => ['type' => 'text', 'label' => 'Section Title'],
                    'description' => ['type' => 'textarea', 'label' => 'Description'],
                    'limit' => ['type' => 'number', 'label' => 'Number of Events to Show', 'default' => 6],
                    'layout' => ['type' => 'select', 'label' => 'Layout', 'options' => ['grid', 'list']],
                    'show_images' => ['type' => 'checkbox', 'label' => 'Show Event Images'],
                    'show_dates' => ['type' => 'checkbox', 'label' => 'Show Event Dates'],
                    'show_view_all' => ['type' => 'checkbox', 'label' => 'Show View All Button']
                ]
            ],
            // News Blocks
            'latest_posts' => [
                'name' => 'Latest Posts',
                'category' => 'content',
                'icon' => 'fas fa-newspaper',
                'view' => 'blocks.latest-posts',
                'settings' => [
                    'title' => ['type' => 'text', 'label' => 'Section Title'],
                    'description' => ['type' => 'textarea', 'label' => 'Description'],
                    'limit' => ['type' => 'number', 'label' => 'Number of Posts to Show', 'default' => 6],
                    'category_id' => ['type' => 'category_select', 'label' => 'Filter by Category'],
                    'layout' => ['type' => 'select', 'label' => 'Layout', 'options' => ['grid', 'list']],
                    'show_images' => ['type' => 'checkbox', 'label' => 'Show Featured Images'],
                    'show_dates' => ['type' => 'checkbox', 'label' => 'Show Publication Dates'],
                    'show_excerpt' => ['type' => 'checkbox', 'label' => 'Show Post Excerpt'],
                    'show_view_all' => ['type' => 'checkbox', 'label' => 'Show View All Button']
                ]
            ]
        ];
    }

    /**
     * Register a new block type
     */
    public function registerBlock(string $type, array $config): void
    {
        $this->registeredBlocks[$type] = $config;
    }

    /**
     * Get all registered blocks
     */
    public function getRegisteredBlocks(): array
    {
        return $this->registeredBlocks;
    }

    /**
     * Get registered blocks by category
     */
    public function getBlocksByCategory(): array
    {
        $categorized = [];
        
        foreach ($this->registeredBlocks as $type => $config) {
            $category = $config['category'] ?? 'other';
            $categorized[$category][] = array_merge($config, ['type' => $type]);
        }

        return $categorized;
    }

    /**
     * Render page content from JSON
     */
    public function renderPageContent(Page $page): string
    {
        if (!$page->use_page_builder || !$page->content_json) {
            return $page->content ?? '';
        }

        try {
            $blocks = json_decode($page->content_json, true);
            
            if (!is_array($blocks)) {
                Log::warning("Invalid content_json format for page {$page->id}");
                return $page->content ?? '';
            }

            return $this->renderBlocks($blocks);
        } catch (\Exception $e) {
            Log::error("Error rendering page content for page {$page->id}: " . $e->getMessage());
            return $page->content ?? '';
        }
    }

    /**
     * Render blocks from array
     */
    public function renderBlocks(array $blocks): string
    {
        $html = '';

        foreach ($blocks as $blockData) {
            $html .= $this->renderBlock($blockData);
        }

        return $html;
    }

    /**
     * Render single block
     */
    public function renderBlock(array $blockData): string
    {
        $type = $blockData['type'] ?? null;
        $settings = $blockData['settings'] ?? [];
        
        if (!$type || !isset($this->registeredBlocks[$type])) {
            return "<!-- Unknown block type: {$type} -->";
        }

        $blockConfig = $this->registeredBlocks[$type];
        $viewName = $blockConfig['view'];

        try {
            if (!View::exists($viewName)) {
                return "<!-- Block view not found: {$viewName} -->";
            }

            return View::make($viewName, [
                'settings' => $settings,
                'blockId' => $blockData['id'] ?? uniqid('block_'),
                'blockType' => $type
            ])->render();
        } catch (\Exception $e) {
            Log::error("Error rendering block {$type}: " . $e->getMessage());
            return "<!-- Error rendering block: {$type} -->";
        }
    }

    /**
     * Validate block data
     */
    public function validateBlock(array $blockData): array
    {
        $errors = [];
        $type = $blockData['type'] ?? null;

        if (!$type) {
            $errors[] = 'Block type is required';
            return $errors;
        }

        if (!isset($this->registeredBlocks[$type])) {
            $errors[] = "Unknown block type: {$type}";
            return $errors;
        }

        $blockConfig = $this->registeredBlocks[$type];
        $settings = $blockData['settings'] ?? [];
        $requiredSettings = $blockConfig['settings'] ?? [];

        foreach ($requiredSettings as $key => $config) {
            if (isset($config['required']) && $config['required'] && empty($settings[$key])) {
                $errors[] = "Required setting '{$key}' is missing for block type '{$type}'";
            }
        }

        return $errors;
    }

    /**
     * Get block configuration
     */
    public function getBlockConfig(string $type): ?array
    {
        return $this->registeredBlocks[$type] ?? null;
    }

    /**
     * Get sample block data
     */
    public function getSampleBlockData(string $type): array
    {
        $blockConfig = $this->getBlockConfig($type);
        
        if (!$blockConfig) {
            return [];
        }

        $sampleData = [
            'id' => uniqid('block_'),
            'type' => $type,
            'settings' => []
        ];

        foreach ($blockConfig['settings'] ?? [] as $key => $config) {
            $sampleData['settings'][$key] = $this->getSampleValueForType($config['type'] ?? 'text');
        }

        return $sampleData;
    }

    /**
     * Get sample value for field type
     */
    protected function getSampleValueForType(string $type): string
    {
        return match ($type) {
            'text' => 'Sample text',
            'textarea' => 'Sample description text',
            'editor' => '<p>Sample rich text content</p>',
            'url' => 'https://example.com',
            'image' => '/images/sample.jpg',
            'select' => '',
            'checkbox' => false,
            'number' => '1',
            'repeater' => [],
            default => ''
        };
    }
}