<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Services\SmartTemplateImporterService;
use App\Services\FullTemplateImporterService;
use App\Services\ExternalTemplateService;
use App\Services\AdvancedTemplateImporterService;
use App\Models\UserTemplate;
use App\Models\TemplateGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SmartImportController extends Controller
{
    protected SmartTemplateImporterService $importer;
    protected FullTemplateImporterService $fullImporter;
    protected ExternalTemplateService $externalService;
    protected AdvancedTemplateImporterService $advancedImporter;

    public function __construct(
        SmartTemplateImporterService $importer,
        FullTemplateImporterService $fullImporter,
        ExternalTemplateService $externalService,
        AdvancedTemplateImporterService $advancedImporter
    ) {
        $this->importer = $importer;
        $this->fullImporter = $fullImporter;
        $this->externalService = $externalService;
        $this->advancedImporter = $advancedImporter;
    }

    /**
     * Show smart import interface
     */
    public function index()
    {
        $recentCompleteProjects = UserTemplate::where('user_id', Auth::id())
            ->where('settings->template_type', 'complete_project')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.templates.smart-import.index', [
            'recent_imports' => $this->getRecentImports(),
            'recent_complete_projects' => $recentCompleteProjects,
            'import_stats' => $this->getImportStats(),
            'supported_sources' => $this->getSupportedSources()
        ]);
    }

    /**
     * Discover templates from external sources
     */
    public function discover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'string|in:all,github_school_templates,github_education_themes,free_css_school',
            'limit' => 'integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $source = $request->get('source', 'all');
            $limit = $request->get('limit', 20);

            $templates = $this->externalService->discoverTemplates($source, $limit);

            return response()->json([
                'success' => true,
                'templates' => $templates,
                'total' => count($templates),
                'source' => $source
            ]);

        } catch (\Exception $e) {
            Log::error('Template discovery failed', [
                'error' => $e->getMessage(),
                'source' => $request->get('source')
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to discover templates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import template from URL with smart analysis
     */
    public function importFromUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'auto_activate' => 'boolean',
            'custom_name' => 'string|max:255',
            'custom_description' => 'string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $url = $request->get('url');
            $userId = Auth::id();

            $options = [
                'auto_activate' => $request->boolean('auto_activate', false),
                'custom_name' => $request->get('custom_name'),
                'custom_description' => $request->get('custom_description')
            ];

            // Start the import process
            $result = $this->importer->importFromUrl($url, $userId, $options);

            if ($result['success']) {
                $this->logImportSuccess($result);

                return response()->json([
                    'success' => true,
                    'template' => [
                        'id' => $result['template']->id,
                        'name' => $result['template']->name,
                        'slug' => $result['template']->slug,
                        'preview_url' => $result['template']->preview_image_url,
                        'is_active' => $result['template']->is_active
                    ],
                    'stats' => $result['stats'],
                    'message' => $result['message'],
                    'redirect' => route('admin.templates.my-templates.edit', $result['template']->id)
                ]);
            } else {
                $this->logImportError($url, $result);

                return response()->json([
                    'success' => false,
                    'error' => $result['error'],
                    'code' => $result['code'] ?? 'IMPORT_FAILED'
                ], 422);
            }

        } catch (\Exception $e) {
            Log::error('Smart import failed', [
                'url' => $request->get('url'),
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Import failed due to an unexpected error. Please try again.',
                'code' => 'UNEXPECTED_ERROR'
            ], 500);
        }
    }

    /**
     * Install external template from discovery
     */
    public function installExternal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'external_id' => 'required|string',
            'template_data' => 'required|array',
            'auto_activate' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = Auth::id();
            $templateData = $request->get('template_data');

            // Install the external template
            $galleryTemplate = $this->externalService->installExternalTemplate($templateData, $userId);

            if (!$galleryTemplate) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to install external template',
                    'code' => 'INSTALL_FAILED'
                ], 422);
            }

            // Get the created user template
            $userTemplate = $galleryTemplate->userTemplates()->where('user_id', $userId)->first();

            if (!$userTemplate) {
                return response()->json([
                    'success' => false,
                    'error' => 'Template installed but user template not found',
                    'code' => 'USER_TEMPLATE_NOT_FOUND'
                ], 422);
            }

            // Auto-activate if requested
            if ($request->boolean('auto_activate', false)) {
                $userTemplate->activate();
            }

            return response()->json([
                'success' => true,
                'template' => [
                    'id' => $userTemplate->id,
                    'name' => $userTemplate->name,
                    'slug' => $userTemplate->slug,
                    'preview_url' => $userTemplate->preview_image_url,
                    'is_active' => $userTemplate->is_active
                ],
                'message' => 'External template installed successfully',
                'redirect' => route('admin.templates.my-templates.edit', $userTemplate->id)
            ]);

        } catch (\Exception $e) {
            Log::error('External template installation failed', [
                'external_id' => $request->get('external_id'),
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Installation failed: ' . $e->getMessage(),
                'code' => 'INSTALLATION_ERROR'
            ], 500);
        }
    }

    /**
     * Analyze URL before import (preview)
     */
    public function analyzeUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $url = $request->get('url');

            // Use the same analysis method from the importer
            $analysis = $this->importer->analyzeTemplate($url);

            if ($analysis['success']) {
                Log::info('Analyze successful', [
                    'url' => $url,
                    'framework' => $analysis['structure']['framework'] ?? null,
                    'sections' => isset($analysis['structure']['sections']) ? count($analysis['structure']['sections']) : 0,
                    'language' => $analysis['language']['primary_language'] ?? null,
                    'html_size' => $analysis['stats']['html_size'] ?? null
                ]);
            } else {
                Log::warning('Analyze failed', [
                    'url' => $url,
                    'code' => $analysis['code'] ?? null,
                    'error' => $analysis['error'] ?? null
                ]);
            }

            if ($analysis['success']) {
                return response()->json([
                    'success' => true,
                    'analysis' => [
                        'title' => $analysis['meta']['title'],
                        'description' => $analysis['meta']['description'],
                        'language' => [
                            'detected' => $analysis['language']['primary_language'],
                            'confidence' => $analysis['language']['confidence'],
                            'needs_translation' => $analysis['language']['primary_language'] !== 'id'
                        ],
                        'structure' => [
                            'framework' => $analysis['structure']['framework'],
                            'has_header' => $analysis['structure']['has_header'],
                            'has_footer' => $analysis['structure']['has_footer'],
                            'sections_count' => count($analysis['structure']['sections'])
                        ],
                        'assets' => [
                            'css_files' => count($analysis['assets']['css']),
                            'js_files' => count($analysis['assets']['js']),
                            'images' => count($analysis['assets']['images'])
                        ],
                        'stats' => $analysis['stats']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $analysis['error'],
                    'code' => $analysis['code'],
                    'debug' => [
                        'http_status' => $analysis['http_status'] ?? null
                    ]
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'URL analysis failed: ' . $e->getMessage(),
                'code' => 'ANALYSIS_ERROR'
            ], 500);
        }
    }

    /**
     * Import template from uploaded file (JSON or ZIP)
     */
    public function importFromFile(Request $request)
    {
        Log::info('Smart Import Request Received', [
            'user_id' => Auth::id(),
            'has_file' => $request->hasFile('file'),
            'file_name' => $request->hasFile('file') ? $request->file('file')->getClientOriginalName() : null,
            'file_extension' => $request->hasFile('file') ? $request->file('file')->getClientOriginalExtension() : null,
            'file_mime_type' => $request->hasFile('file') ? $request->file('file')->getMimeType() : null,
            'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        $validator = Validator::make($request->all(), [
            // Simplified: rely on extension; JSON/HTML content will still be parsed/validated manually
            'file' => 'required|file|mimes:json,zip,html,htm,txt|max:10240', // 10MB
            'template_name' => 'nullable|string|max:255',
            'auto_activate' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            Log::warning('Smart Import Validation Failed', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first(),
                'errors' => $validator->errors(),
                'code' => 'VALIDATION_FAILED'
            ], 422)->header('Content-Type', 'application/json');
        }

        try {
            $file = $request->file('file');
            $userId = Auth::id();
            $autoActivate = $request->boolean('auto_activate', false);
            $templateName = $request->get('template_name');

            Log::info('File import started', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'user_id' => $userId
            ]);

            // Process the uploaded file
            $result = $this->processUploadedFile($file, $userId, $templateName, $autoActivate);

            if ($result['success']) {
                Log::info('Smart Import Success', [
                    'template_id' => $result['template']->id,
                    'template_name' => $result['template']->name,
                    'user_id' => Auth::id()
                ]);

                // Check if request expects JSON (AJAX) or normal redirect
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'template' => [
                            'id' => $result['template']->id,
                            'name' => $result['template']->name,
                            'slug' => $result['template']->slug,
                            'preview_url' => $result['template']->preview_image_url ?? null,
                            'is_active' => $result['template']->is_active
                        ],
                        'stats' => $result['stats'] ?? [],
                        'message' => $autoActivate ?
                            'Template berhasil diimpor dan diaktifkan!' :
                            'Template berhasil diimpor!',
                        'redirect' => route('admin.templates.my-templates.show', $result['template']->id)
                    ])->header('Content-Type', 'application/json');
                } else {
                    // Normal form submission - redirect with success message
                    return redirect()->route('admin.templates.my-templates.show', $result['template']->id)
                        ->with('success', $autoActivate ?
                            'Template berhasil diimpor dan diaktifkan!' :
                            'Template berhasil diimpor!');
                }
            } else {
                Log::error('Smart Import Failed', [
                    'error' => $result['error'],
                    'code' => $result['code'] ?? 'UNKNOWN',
                    'user_id' => Auth::id()
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $result['error'],
                    'code' => $result['code'] ?? 'IMPORT_FAILED'
                ], 422)->header('Content-Type', 'application/json');
            }

        } catch (\Exception $e) {
            Log::error('File import failed', [
                'filename' => $request->file('file')?->getClientOriginalName(),
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Import failed: ' . $e->getMessage(),
                'code' => 'UNEXPECTED_ERROR'
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Process uploaded template file
     */
    protected function processUploadedFile($file, int $userId, ?string $templateName, bool $autoActivate): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $originalName = $file->getClientOriginalName();

        try {
            switch ($extension) {
                case 'json':
                    return $this->processJsonFile($file, $userId, $templateName, $autoActivate);
                case 'zip':
                    return $this->processZipFile($file, $userId, $templateName, $autoActivate);
                case 'html':
                case 'htm':
                    return $this->processHtmlFile($file, $userId, $templateName, $autoActivate);
                default:
                    return [
                        'success' => false,
                        'error' => 'Unsupported file type: ' . $extension,
                        'code' => 'UNSUPPORTED_FILE_TYPE'
                    ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to process file: ' . $e->getMessage(),
                'code' => 'FILE_PROCESSING_ERROR'
            ];
        }
    }

    /**
     * Process JSON template file
     */
    protected function processJsonFile($file, int $userId, ?string $templateName, bool $autoActivate): array
    {
        Log::info('Processing JSON file', [
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'user_id' => $userId
        ]);

        $content = file_get_contents($file->getPathname());

        Log::info('JSON file content loaded', [
            'content_length' => strlen($content),
            'content_preview' => substr($content, 0, 200)
        ]);

        // Check if content looks like HTML instead of JSON
        $contentPreview = substr(trim($content), 0, 20);
        if (str_starts_with($contentPreview, '<!DOCTYPE') || str_starts_with($contentPreview, '<html')) {
            Log::error('File contains HTML instead of JSON', [
                'filename' => $file->getClientOriginalName(),
                'content_preview' => $contentPreview
            ]);
            return [
                'success' => false,
                'error' => 'The uploaded file contains HTML content instead of JSON. Please upload a valid JSON template file.',
                'code' => 'HTML_NOT_JSON'
            ];
        }

        $templateData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode failed', [
                'filename' => $file->getClientOriginalName(),
                'error' => json_last_error_msg(),
                'error_code' => json_last_error(),
                'content_preview' => substr($content, 0, 500)
            ]);

            return [
                'success' => false,
                'error' => 'Invalid JSON file "' . $file->getClientOriginalName() . '": ' . json_last_error_msg() . '. Please ensure the file contains valid JSON format.',
                'code' => 'INVALID_JSON'
            ];
        }

        Log::info('JSON decoded successfully', [
            'data_keys' => array_keys($templateData),
            'has_template_data' => isset($templateData['template_data'])
        ]);

        // Normalize template structure
        $normalizedData = $this->normalizeTemplateData($templateData);
        if (!$normalizedData) {
            return [
                'success' => false,
                'error' => 'Invalid template structure. Please ensure the JSON contains valid template data.',
                'code' => 'INVALID_TEMPLATE_STRUCTURE'
            ];
        }
        $templateData = $normalizedData;

        // Sanitize structure (auto-repair missing fields / legacy shapes)
        $templateData = $this->sanitizeTemplateData($templateData);

    // Apply domain-specific transformations (hero / statistics / card_grid mapping, metadata extraction)
    $templateData = $this->transformDomainSpecificBlocks($templateData);

    // Collect diagnostics for easier debugging of 422 issues
    $diagnostics = $this->collectTemplateDiagnostics($templateData);
    Log::info('Template diagnostics after transformation', $diagnostics);

        // Generate template name if not provided
        if (!$templateName) {
            $templateName = $templateData['name'] ??
                           pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        }

        // Create user template
        $template = UserTemplate::create([
            'user_id' => $userId,
            'name' => $templateName,
            'slug' => Str::slug($templateName) . '-' . time(),
            'description' => $templateData['description'] ?? 'Imported from JSON file',
            'template_data' => $templateData,
            'source' => 'imported',
            'is_active' => false,
            'customizations' => [
                'import_method' => 'json_file',
                'original_filename' => $file->getClientOriginalName(),
                'imported_at' => now()->toISOString()
            ],
            'settings' => $templateData['site_meta'] ?? null,
        ]);

        // Auto-activate if requested
        if ($autoActivate) {
            $template->activate();
        }

        // Generate stats
        $stats = $this->generateImportStats($template);

        // Attempt to derive and store a preview image from hero block background (non-blocking)
        try {
            $this->extractPreviewImage($templateData, $template);
        } catch (\Exception $e) {
            Log::warning('Preview image extraction failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);
        }

        return [
            'success' => true,
            'template' => $template,
            'stats' => $stats
        ];
    }

    /**
     * Attempt to sanitize / normalize blocks & sections shape to expected internal format.
     * - Adds missing keys (order, active, key)
     * - Converts block 'content' -> 'data'
     * - Wraps raw unknown block objects (without 'type') into a 'raw' block preserving original payload
     */
    protected function sanitizeTemplateData(array $data): array
    {
        if (!isset($data['templates']) || !is_array($data['templates'])) {
            return $data; // nothing to do
        }

        foreach ($data['templates'] as $tIndex => $tpl) {
            // Ensure template has sections array
            if (!isset($tpl['sections']) || !is_array($tpl['sections'])) {
                $data['templates'][$tIndex]['sections'] = [];
                continue;
            }
            foreach ($tpl['sections'] as $sIndex => $section) {
                // Ensure blocks array
                if (!isset($section['blocks']) || !is_array($section['blocks'])) {
                    $data['templates'][$tIndex]['sections'][$sIndex]['blocks'] = [];
                }
                // Add default section metadata
                if (!isset($section['name'])) {
                    $data['templates'][$tIndex]['sections'][$sIndex]['name'] = 'Section '.($sIndex+1);
                }
                if (!isset($section['key'])) {
                    $data['templates'][$tIndex]['sections'][$sIndex]['key'] = Str::slug($data['templates'][$tIndex]['sections'][$sIndex]['name']);
                }
                if (!isset($section['order'])) {
                    $data['templates'][$tIndex]['sections'][$sIndex]['order'] = $sIndex + 1;
                }
                if (!isset($section['active'])) {
                    $data['templates'][$tIndex]['sections'][$sIndex]['active'] = true;
                }

                // Process blocks
                foreach ($data['templates'][$tIndex]['sections'][$sIndex]['blocks'] as $bIndex => $block) {
                    // If block missing type, wrap it as raw
                    if (!isset($block['type'])) {
                        $data['templates'][$tIndex]['sections'][$sIndex]['blocks'][$bIndex] = [
                            'type' => 'raw',
                            'order' => $bIndex + 1,
                            'active' => true,
                            'data' => [ 'raw' => $block ]
                        ];
                        continue;
                    }
                    // Map legacy 'content' -> 'data'
                    if (!isset($block['data']) && isset($block['content'])) {
                        $block['data'] = $block['content'];
                        unset($block['content']);
                    }
                    if (!isset($block['data'])) {
                        $block['data'] = [];
                    }
                    if (!isset($block['order'])) {
                        $block['order'] = $bIndex + 1;
                    }
                    if (!isset($block['active'])) {
                        $block['active'] = true;
                    }
                    $data['templates'][$tIndex]['sections'][$sIndex]['blocks'][$bIndex] = $block;
                }
            }
        }
        return $data;
    }

    /**
     * Apply domain-specific transformations:
     * - Hero block: map background_image_url/image -> background_image
     * - Statistics block: map items[value=>number] -> stats[number]
     * - Card grid: normalize link field into object {url,text}
     * - Normalize block type aliases (statistics->stats, card-grid->card_grid)
     * - Extract site / menu metadata if embedded inside blocks
     */
    protected function transformDomainSpecificBlocks(array $data): array
    {
        if (!isset($data['templates']) || !is_array($data['templates'])) {
            return $data;
        }

        $siteMeta = $data['site_meta'] ?? [];
        $movedMetaCount = 0;

        foreach ($data['templates'] as $tIndex => $tpl) {
            if (!isset($tpl['sections']) || !is_array($tpl['sections'])) {
                continue;
            }
            foreach ($tpl['sections'] as $sIndex => $section) {
                if (!isset($section['blocks']) || !is_array($section['blocks'])) {
                    continue;
                }
                foreach ($section['blocks'] as $bIndex => $block) {
                    if (!isset($block['type'])) {
                        continue; // already sanitized earlier (raw)
                    }

                    $originalType = $block['type'];
                    $type = str_replace('-', '_', strtolower($originalType));

                    // Aliases
                    if ($type === 'statistics') { $type = 'stats'; }
                    if ($type === 'cardgrid') { $type = 'card_grid'; }

                    $block['type'] = $type; // persist normalization
                    $dataField = $block['data'] ?? [];
                    if (!is_array($dataField)) { $dataField = []; }

                    // Hero mapping
                    if ($type === 'hero') {
                        if (isset($dataField['background_image_url']) && !isset($dataField['background_image'])) {
                            $dataField['background_image'] = $dataField['background_image_url'];
                        }
                        if (isset($dataField['image']) && !isset($dataField['background_image'])) {
                            $dataField['background_image'] = $dataField['image'];
                        }
                        // Single button object -> array
                        if (isset($dataField['buttons']) && is_array($dataField['buttons']) && array_keys($dataField['buttons']) !== range(0, count($dataField['buttons']) - 1)) {
                            $dataField['buttons'] = [$dataField['buttons']];
                        }
                    }

                    // Stats mapping
                    if ($type === 'stats') {
                        if (!isset($dataField['stats']) && isset($dataField['items']) && is_array($dataField['items'])) {
                            $converted = [];
                            foreach ($dataField['items'] as $item) {
                                if (!is_array($item)) { continue; }
                                $converted[] = [
                                    'number' => $item['value'] ?? ($item['number'] ?? null),
                                    'label' => $item['label'] ?? ($item['title'] ?? ''),
                                    'description' => $item['description'] ?? ($item['text'] ?? null),
                                ];
                            }
                            if ($converted) { $dataField['stats'] = $converted; }
                        }
                    }

                    // Card grid mapping
                    if ($type === 'card_grid') {
                        if (isset($dataField['cards']) && is_array($dataField['cards'])) {
                            foreach ($dataField['cards'] as $cIdx => $card) {
                                if (!is_array($card)) { continue; }
                                // Plain link string
                                if (isset($card['link']) && is_string($card['link'])) {
                                    $dataField['cards'][$cIdx]['link'] = [
                                        'url' => $card['link'],
                                        'text' => 'Selengkapnya',
                                    ];
                                } elseif (!isset($card['link']) && isset($card['url'])) {
                                    $dataField['cards'][$cIdx]['link'] = [
                                        'url' => $card['url'],
                                        'text' => $card['link_text'] ?? ($card['title'] ?? 'Detail'),
                                    ];
                                }
                            }
                        }
                    }

                    // Attempt to detect site metadata accidentally embedded as a block
                    $possibleMetaKeys = ['site_name','site_title','tagline','menus','navigation','contact_email'];
                    $intersects = array_intersect($possibleMetaKeys, array_keys($dataField));
                    if (count($intersects) > 1) { // treat as meta block
                        foreach ($possibleMetaKeys as $mk) {
                            if (isset($dataField[$mk]) && !isset($siteMeta[$mk])) {
                                $siteMeta[$mk] = $dataField[$mk];
                            }
                        }
                        $movedMetaCount++;
                    }

                    $block['data'] = $dataField; // assign mutated data
                    $data['templates'][$tIndex]['sections'][$sIndex]['blocks'][$bIndex] = $block;
                }
            }
        }

        if ($siteMeta) {
            $data['site_meta'] = $siteMeta;
            if ($movedMetaCount > 0) {
                $data['site_meta']['_extracted_from_blocks'] = $movedMetaCount;
            }
        }

        return $data;
    }

    /**
     * Collect lightweight diagnostics for logging/debugging.
     */
    protected function collectTemplateDiagnostics(array $data): array
    {
        $templates = $data['templates'] ?? [];
        $sectionCount = 0; $blockCount = 0; $types = [];
        foreach ($templates as $tpl) {
            foreach ($tpl['sections'] ?? [] as $section) {
                $sectionCount++;
                foreach ($section['blocks'] ?? [] as $block) {
                    $blockCount++;
                    $t = $block['type'] ?? 'unknown';
                    $types[$t] = ($types[$t] ?? 0) + 1;
                }
            }
        }
        return [
            'templates' => count($templates),
            'sections' => $sectionCount,
            'blocks' => $blockCount,
            'block_types' => $types,
            'has_site_meta' => isset($data['site_meta']),
        ];
    }

    /**
     * Try to fetch a hero background image and store it as preview.
     */
    protected function extractPreviewImage(array $data, UserTemplate $template): void
    {
        if ($template->preview_image) { return; }
        $heroImage = null;
        foreach (($data['templates'] ?? []) as $tpl) {
            foreach ($tpl['sections'] ?? [] as $section) {
                foreach ($section['blocks'] ?? [] as $block) {
                    if (($block['type'] ?? null) === 'hero') {
                        $heroImage = $block['data']['background_image'] ?? ($block['data']['background_image_url'] ?? null);
                        break 3;
                    }
                }
            }
        }
        if (!$heroImage || !is_string($heroImage)) { return; }
        if (!str_starts_with($heroImage, 'http')) { return; }
        try {
            $contents = @file_get_contents($heroImage);
            if ($contents === false) { return; }
            $ext = 'jpg';
            if (str_contains(strtolower($heroImage), '.png')) { $ext = 'png'; }
            $path = 'previews/template-'.$template->id.'-hero.'. $ext;
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, $contents);
            $template->update(['preview_image' => $path]);
            Log::info('Preview image stored', ['template_id' => $template->id, 'path' => $path]);
        } catch (\Exception $e) {
            Log::warning('Failed to download hero image for preview', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Process ZIP template file
     */
    protected function processZipFile($file, int $userId, ?string $templateName, bool $autoActivate): array
    {
        $zip = new \ZipArchive();
        $result = $zip->open($file->getPathname());

        if ($result !== TRUE) {
            return [
                'success' => false,
                'error' => 'Could not open ZIP file: ' . $this->getZipError($result),
                'code' => 'ZIP_OPEN_ERROR'
            ];
        }

        try {
            // Look for template.json or similar files
            $templateFile = null;
            $htmlFile = null;
            $availableFiles = [];
            $extractedFiles = []; // Store all files for template switching

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $availableFiles[] = $filename;

                // Extract all files to storage for template switching
                if (!str_ends_with($filename, '/')) { // Skip directories
                    $content = $zip->getFromName($filename);
                    if ($content !== false) {
                        $extractedFiles[$filename] = [
                            'content' => base64_encode($content),
                            'type' => $this->getFileType($filename),
                            'size' => strlen($content)
                        ];
                    }
                }

                // Prioritize template.json, then any .json file
                if (preg_match('/template\.json$/i', $filename)) {
                    $templateFile = $filename;
                    break;
                } elseif (preg_match('/\.json$/i', $filename) && !$templateFile) {
                    $templateFile = $filename;
                }
                // Look for index.html as fallback
                elseif (preg_match('/index\.html?$/i', $filename) && !$htmlFile) {
                    $htmlFile = $filename;
                }
            }

            // Determine template type and processing method
            if ($templateFile) {
                // Process as JSON-based template but also store files
                $result = $this->processZipJsonTemplate($zip, $templateFile, $extractedFiles, $userId, $templateName, $autoActivate);
            } elseif ($htmlFile) {
                // Process as file-based template
                $result = $this->processZipFileBasedTemplate($zip, $htmlFile, $extractedFiles, $userId, $templateName, $autoActivate);
            } else {
                $zip->close();
                Log::warning('No JSON or HTML file found in ZIP', ['available_files' => array_slice($availableFiles, 0, 20)]);
                return [
                    'success' => false,
                    'error' => 'No template.json or index.html found in ZIP archive. Available files: ' . implode(', ', array_slice($availableFiles, 0, 5)) . (count($availableFiles) > 5 ? '...' : ''),
                    'code' => 'NO_TEMPLATE_FILE'
                ];
            }

            $zip->close();
            return $result;

        } catch (\Exception $e) {
            $zip->close();
            throw $e;
        }
    }

    /**
     * Get file type from filename
     */
    protected function getFileType(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $typeMap = [
            'html' => 'html',
            'htm' => 'html',
            'css' => 'css',
            'js' => 'javascript',
            'json' => 'json',
            'php' => 'php',
            'blade' => 'blade',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
            'svg' => 'image',
            'pdf' => 'document',
            'txt' => 'text',
            'md' => 'markdown'
        ];

        return $typeMap[$extension] ?? 'other';
    }

    /**
     * Process ZIP with JSON template but also store files for switching
     */
    protected function processZipJsonTemplate($zip, string $templateFile, array $extractedFiles, int $userId, ?string $templateName, bool $autoActivate): array
    {
        // Extract and process the template file
        $content = $zip->getFromName($templateFile);

        if ($content === false) {
            return [
                'success' => false,
                'error' => 'Could not extract template file from ZIP',
                'code' => 'EXTRACT_ERROR'
            ];
        }

        // Create temporary file and process as JSON
        $tempFile = tmpfile();
        fwrite($tempFile, $content);
        $tempPath = stream_get_meta_data($tempFile)['uri'];

        $mockFile = new \Illuminate\Http\UploadedFile(
            $tempPath,
            $templateFile,
            'application/json',
            null,
            true
        );

        // Process the JSON template normally
        $result = $this->processJsonFile($mockFile, $userId, $templateName, $autoActivate);

        // If successful, also store the extracted files for template switching
        if ($result['success']) {
            $template = $result['template'];
            $template->update([
                'template_files' => $extractedFiles,
                'template_type' => 'blocks' // JSON-based but with file storage
            ]);
        }

        return $result;
    }

    /**
     * Process ZIP as file-based template (WordPress-like)
     */
    protected function processZipFileBasedTemplate($zip, string $htmlFile, array $extractedFiles, int $userId, ?string $templateName, bool $autoActivate): array
    {
        try {
            // Extract main HTML content
            $content = $zip->getFromName($htmlFile);

            if ($content === false) {
                return [
                    'success' => false,
                    'error' => 'Could not extract HTML file from ZIP',
                    'code' => 'EXTRACT_ERROR'
                ];
            }

            // Extract title from HTML if no template name provided
            if (!$templateName) {
                if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $content, $matches)) {
                    $templateName = trim(strip_tags($matches[1]));
                }
                if (!$templateName) {
                    $templateName = pathinfo($htmlFile, PATHINFO_FILENAME);
                }
            }

            // Create template data structure for file-based template
            $templateData = [
                'type' => 'file_based',
                'main_file' => $htmlFile,
                'description' => 'File-based template imported from ZIP',
                'files' => array_keys($extractedFiles)
            ];

            // Create user template with file-based type
            $template = UserTemplate::create([
                'user_id' => $userId,
                'name' => $templateName,
                'slug' => Str::slug($templateName) . '-' . time(),
                'description' => 'File-based template imported from ZIP',
                'template_data' => $templateData,
                'template_files' => $extractedFiles,
                'template_type' => 'files', // File-based template
                'source' => 'imported',
                'is_active' => false,
                'customizations' => [
                    'import_method' => 'zip_file_based',
                    'main_file' => $htmlFile,
                    'imported_at' => now()->toISOString()
                ]
            ]);

            // Auto-activate if requested
            if ($autoActivate) {
                $template->activate();
            }

            // Generate stats
            $stats = [
                'templates_created' => 1,
                'files_stored' => count($extractedFiles),
                'main_file' => $htmlFile,
                'template_type' => 'file_based',
                'import_method' => 'zip'
            ];

            return [
                'success' => true,
                'template' => $template,
                'stats' => $stats
            ];

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Process HTML fallback from ZIP file
     */
    protected function processZipHtmlFallback($zip, string $htmlFile, int $userId, ?string $templateName, bool $autoActivate): array
    {
        try {
            $content = $zip->getFromName($htmlFile);
            $zip->close();

            if ($content === false) {
                return [
                    'success' => false,
                    'error' => 'Could not extract HTML file from ZIP',
                    'code' => 'EXTRACT_ERROR'
                ];
            }

            Log::info('Processing HTML from ZIP', [
                'html_file' => $htmlFile,
                'content_length' => strlen($content)
            ]);

            // Extract title from HTML if no template name provided
            if (!$templateName) {
                if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $content, $matches)) {
                    $templateName = trim(strip_tags($matches[1]));
                }
                if (!$templateName) {
                    $templateName = pathinfo($htmlFile, PATHINFO_FILENAME);
                }
            }

            // Extract body content
            $bodyContent = $content;
            if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
                $bodyContent = trim($matches[1]);
            }

            // Create template data structure
            $templateData = [
                'templates' => [[
                    'name' => $templateName,
                    'slug' => Str::slug($templateName),
                    'description' => 'Imported from ZIP HTML file',
                    'active' => true,
                    'type' => 'page',
                    'sections' => [[
                        'name' => 'HTML Content',
                        'key' => 'html-content',
                        'order' => 1,
                        'active' => true,
                        'blocks' => [[
                            'type' => 'rich_text',
                            'order' => 1,
                            'active' => true,
                            'data' => [
                                'content' => $bodyContent
                            ]
                        ]]
                    ]]
                ]]
            ];

            // Create user template
            $template = UserTemplate::create([
                'user_id' => $userId,
                'name' => $templateName,
                'slug' => Str::slug($templateName) . '-' . time(),
                'description' => 'Imported from ZIP HTML file',
                'template_data' => $templateData,
                'source' => 'imported',
                'is_active' => false,
                'customizations' => [
                    'import_method' => 'zip_html_fallback',
                    'original_filename' => $htmlFile,
                    'imported_at' => now()->toISOString()
                ]
            ]);

            // Auto-activate if requested
            if ($autoActivate) {
                $template->activate();
            }

            // Generate stats
            $stats = $this->generateImportStats($template);

            return [
                'success' => true,
                'template' => $template,
                'stats' => $stats
            ];

        } catch (\Exception $e) {
            $zip->close();
            throw $e;
        }
    }

    /**
     * Process HTML template file
     */
    protected function processHtmlFile($file, int $userId, ?string $templateName, bool $autoActivate): array
    {
        $content = file_get_contents($file->getPathname());

        if (empty($content)) {
            return [
                'success' => false,
                'error' => 'HTML file is empty or could not be read',
                'code' => 'EMPTY_HTML_FILE'
            ];
        }

        // Extract title from HTML if no template name provided
        if (!$templateName) {
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $content, $matches)) {
                $templateName = trim(strip_tags($matches[1]));
            }
            if (!$templateName) {
                $templateName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }
        }

        // Extract body content
        $bodyContent = $content;
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
            $bodyContent = trim($matches[1]);
        }

        // Create template data structure
        $templateData = [
            'templates' => [[
                'name' => $templateName,
                'slug' => Str::slug($templateName),
                'description' => 'Imported from HTML file',
                'active' => true,
                'type' => 'page',
                'sections' => [[
                    'name' => 'HTML Content',
                    'key' => 'html-content',
                    'order' => 1,
                    'active' => true,
                    'blocks' => [[
                        'type' => 'rich_text',
                        'order' => 1,
                        'active' => true,
                        'data' => [
                            'content' => $bodyContent
                        ]
                    ]]
                ]]
            ]]
        ];

        // Create user template
        $template = UserTemplate::create([
            'user_id' => $userId,
            'name' => $templateName,
            'slug' => Str::slug($templateName) . '-' . time(),
            'description' => 'Imported from HTML file',
            'template_data' => $templateData,
            'source' => 'imported',
            'is_active' => false,
            'customizations' => [
                'import_method' => 'html_file',
                'original_filename' => $file->getClientOriginalName(),
                'imported_at' => now()->toISOString()
            ]
        ]);

        // Auto-activate if requested
        if ($autoActivate) {
            $template->activate();
        }

        // Generate stats
        $stats = $this->generateImportStats($template);

        return [
            'success' => true,
            'template' => $template,
            'stats' => $stats
        ];
    }

    /**
     * Normalize template data to consistent structure
     */
    protected function normalizeTemplateData(array $data): ?array
    {
        // If it already has the expected structure
        if (isset($data['templates']) && is_array($data['templates'])) {
            return $data;
        }

        // If it has template_data wrapper
        if (isset($data['template_data'])) {
            if (isset($data['template_data']['templates'])) {
                return $data['template_data'];
            }
            return $data['template_data'];
        }

        // If it's a legacy structure with template and sections
        if (isset($data['template']) && isset($data['sections'])) {
            return [
                'templates' => [[
                    'name' => $data['template']['name'] ?? 'Imported Template',
                    'slug' => $data['template']['slug'] ?? Str::slug($data['template']['name'] ?? 'imported'),
                    'description' => $data['template']['description'] ?? null,
                    'active' => $data['template']['active'] ?? true,
                    'type' => 'page',
                    'sections' => $data['sections']
                ]]
            ];
        }

        // If it's a direct template structure
        if (isset($data['name']) && isset($data['sections'])) {
            return [
                'templates' => [$data]
            ];
        }

        // If it's just sections array
        if (isset($data['sections']) && is_array($data['sections'])) {
            return [
                'templates' => [[
                    'name' => 'Imported Template',
                    'slug' => 'imported-template',
                    'description' => 'Imported template',
                    'active' => true,
                    'type' => 'page',
                    'sections' => $data['sections']
                ]]
            ];
        }

        return null;
    }

    /**
     * Validate template structure
     */
    protected function validateTemplateStructure(array $data): bool
    {
        // Check if it has template_data or direct templates array
        if (isset($data['template_data']['templates']) || isset($data['templates'])) {
            return true;
        }

        // Check if it's a direct template structure
        if (isset($data['name']) && isset($data['sections'])) {
            return true;
        }

        return false;
    }

    /**
     * Generate import statistics
     */
    protected function generateImportStats(UserTemplate $template): array
    {
        $templateData = $template->template_data;
        $sectionsCount = 0;
        $blocksCount = 0;

        if (isset($templateData['templates'])) {
            foreach ($templateData['templates'] as $tpl) {
                if (isset($tpl['sections'])) {
                    $sectionsCount += count($tpl['sections']);
                    foreach ($tpl['sections'] as $section) {
                        if (isset($section['blocks'])) {
                            $blocksCount += count($section['blocks']);
                        }
                    }
                }
            }
        }

        return [
            'templates_created' => isset($templateData['templates']) ? count($templateData['templates']) : 1,
            'sections_created' => $sectionsCount,
            'blocks_created' => $blocksCount,
            'import_method' => 'file',
            'file_size' => 'N/A'
        ];
    }

    /**
     * Get ZIP error message
     */
    protected function getZipError(int $code): string
    {
        $errors = [
            \ZipArchive::ER_OK => 'No error',
            \ZipArchive::ER_MULTIDISK => 'Multi-disk zip archives not supported',
            \ZipArchive::ER_RENAME => 'Renaming temporary file failed',
            \ZipArchive::ER_CLOSE => 'Closing zip archive failed',
            \ZipArchive::ER_SEEK => 'Seek error',
            \ZipArchive::ER_READ => 'Read error',
            \ZipArchive::ER_WRITE => 'Write error',
            \ZipArchive::ER_CRC => 'CRC error',
            \ZipArchive::ER_ZIPCLOSED => 'Containing zip archive was closed',
            \ZipArchive::ER_NOENT => 'No such file',
            \ZipArchive::ER_EXISTS => 'File already exists',
            \ZipArchive::ER_OPEN => 'Can\'t open file',
            \ZipArchive::ER_TMPOPEN => 'Failure to create temporary file',
            \ZipArchive::ER_ZLIB => 'Zlib error',
            \ZipArchive::ER_MEMORY => 'Memory allocation failure',
            \ZipArchive::ER_CHANGED => 'Entry has been changed',
            \ZipArchive::ER_COMPNOTSUPP => 'Compression method not supported',
            \ZipArchive::ER_EOF => 'Premature EOF',
            \ZipArchive::ER_INVAL => 'Invalid argument',
            \ZipArchive::ER_NOZIP => 'Not a zip archive',
            \ZipArchive::ER_INTERNAL => 'Internal error',
            \ZipArchive::ER_INCONS => 'Zip archive inconsistent',
            \ZipArchive::ER_REMOVE => 'Can\'t remove file',
            \ZipArchive::ER_DELETED => 'Entry has been deleted',
        ];

        return $errors[$code] ?? 'Unknown error code: ' . $code;
    }

    /**
     * Get import progress (for real-time updates)
     */
    public function getProgress(Request $request)
    {
        $importId = $request->get('import_id');

        // For now, return mock progress
        // In production, this would check a job queue or cache
        return response()->json([
            'success' => true,
            'progress' => [
                'step' => 'Converting template',
                'percentage' => 75,
                'message' => 'Converting HTML structure to CMS format...'
            ]
        ]);
    }

    /**
     * Get recent imports for the current user
     */
    protected function getRecentImports()
    {
        return UserTemplate::where('user_id', Auth::id())
            ->where('source', 'imported')
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'preview_image', 'is_active', 'created_at']);
    }

    /**
     * Get import statistics
     */
    protected function getImportStats()
    {
        $userId = Auth::id();

        return [
            'total_imports' => UserTemplate::where('user_id', $userId)->where('source', 'imported')->count(),
            'active_imports' => UserTemplate::where('user_id', $userId)->where('source', 'imported')->where('is_active', true)->count(),
            'successful_imports' => UserTemplate::where('user_id', $userId)->where('source', 'imported')->count(), // All in DB are successful
            'last_import' => UserTemplate::where('user_id', $userId)->where('source', 'imported')->latest()->first()?->created_at
        ];
    }

    /**
     * Get supported import sources
     */
    protected function getSupportedSources()
    {
        return [
            [
                'id' => 'url',
                'name' => 'Import from URL',
                'description' => 'Import any school website template from a URL',
                'icon' => 'fas fa-link',
                'features' => ['Auto language detection', 'Smart conversion', 'Instant translation']
            ],
            [
                'id' => 'github',
                'name' => 'GitHub Templates',
                'description' => 'Discover and import templates from GitHub',
                'icon' => 'fab fa-github',
                'features' => ['Curated selection', 'Quality templates', 'Open source']
            ],
            [
                'id' => 'html_upload',
                'name' => 'Upload HTML',
                'description' => 'Upload HTML/CSS files directly',
                'icon' => 'fas fa-upload',
                'features' => ['File upload', 'Bulk import', 'Asset extraction']
            ],
            [
                'id' => 'live_demo',
                'name' => 'Live Demo Import',
                'description' => 'Import from live demo websites',
                'icon' => 'fas fa-globe',
                'features' => ['Live capture', 'Real-time import', 'Preview generation']
            ]
        ];
    }

    /**
     * Log successful import
     */
    protected function logImportSuccess(array $result)
    {
        Log::info('Template import successful', [
            'template_id' => $result['template']->id,
            'template_name' => $result['template']->name,
            'user_id' => Auth::id(),
            'stats' => $result['stats']
        ]);
    }

    /**
     * Log import error
     */
    protected function logImportError(string $url, array $result)
    {
        Log::warning('Template import failed', [
            'url' => $url,
            'user_id' => Auth::id(),
            'error' => $result['error'],
            'code' => $result['code'] ?? 'UNKNOWN_ERROR'
        ]);
    }

    /**
     * Import full template from various sources
     */
    public function importFullTemplate(Request $request)
    {
        $request->validate([
            'source' => 'required|string',
            'type' => 'required|in:github,url,zip',
            'name' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:50'
        ]);

        try {
            $options = [];
            if ($request->name) {
                $options['name'] = $request->name;
            }
            if ($request->branch) {
                $options['branch'] = $request->branch;
            }

            $result = $this->fullImporter->importFullTemplate(
                $request->source,
                Auth::id(),
                $options
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'template_id' => $result['user_template']->id,
                    'files_imported' => $result['files_imported'],
                    'redirect' => route('admin.templates.smart-import.index')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Full template import failed', [
                'source' => $request->source,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload and import full template ZIP
     */
    public function uploadFullTemplate(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:50000', // 50MB max
            'name' => 'nullable|string|max:255'
        ]);

        try {
            $zipFile = $request->file('zip_file');
            $tempPath = $zipFile->store('temp');
            $fullPath = Storage::path($tempPath);

            $options = [];
            if ($request->name) {
                $options['name'] = $request->name;
            }

            $result = $this->fullImporter->importFullTemplate(
                $fullPath,
                Auth::id(),
                $options
            );

            // Clean up temp file
            Storage::delete($tempPath);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'template_id' => $result['user_template']->id,
                    'files_imported' => $result['files_imported'],
                    'redirect' => route('admin.templates.smart-import.index')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Full template ZIP upload failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List full templates
     */
    public function listFullTemplates()
    {
        $templates = UserTemplate::where('user_id', Auth::id())
            ->where('settings->template_type', 'full_template')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'templates' => $templates->map(function($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->name,
                    'description' => $template->description,
                    'status' => $template->status,
                    'created_at' => $template->created_at->format('M d, Y'),
                    'files_count' => count($template->template_data['files'] ?? [])
                ];
            })
        ]);
    }

    /**
     * Activate full template for homepage
     */
    public function activateFullTemplate(UserTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // Deactivate other full templates
            UserTemplate::where('user_id', Auth::id())
                ->where('settings->template_type', 'full_template')
                ->update(['status' => 'inactive']);

            // Activate this template
            $template->update(['status' => 'active']);

            // Set as homepage template
            \App\Models\Setting::set('homepage_template_type', 'full_template');
            \App\Models\Setting::set('active_full_template_id', $template->id);

            return response()->json([
                'success' => true,
                'message' => 'Template activated successfully for homepage'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate template: ' . $e->getMessage()
            ], 500);
        }
    }
}
