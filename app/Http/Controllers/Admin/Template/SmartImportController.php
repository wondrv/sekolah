<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Services\SmartTemplateImporterService;
use App\Services\ExternalTemplateService;
use App\Models\UserTemplate;
use App\Models\TemplateGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SmartImportController extends Controller
{
    protected SmartTemplateImporterService $importer;
    protected ExternalTemplateService $externalService;

    public function __construct(
        SmartTemplateImporterService $importer,
        ExternalTemplateService $externalService
    ) {
        $this->importer = $importer;
        $this->externalService = $externalService;
    }

    /**
     * Show smart import interface
     */
    public function index()
    {
        return view('admin.templates.smart-import.index', [
            'recent_imports' => $this->getRecentImports(),
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
            'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json,zip,html,htm|max:10240', // 10MB max
            'template_name' => 'string|max:255',
            'auto_activate' => 'boolean'
        ]);

        if ($validator->fails()) {
            Log::error('Smart Import Validation Failed', $validator->errors()->toArray());

            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }        try {
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
                    ]);
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

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => $result['error'],
                        'code' => $result['code'] ?? 'IMPORT_FAILED'
                    ], 422);
                } else {
                    return redirect()->back()
                        ->with('error', 'Import gagal: ' . $result['error'])
                        ->withInput();
                }
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
            ], 500);
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
        $content = file_get_contents($file->getPathname());
        $templateData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid JSON file: ' . json_last_error_msg(),
                'code' => 'INVALID_JSON'
            ];
        }

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
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (preg_match('/template\.json$/i', $filename) ||
                    preg_match('/\.json$/i', $filename)) {
                    $templateFile = $filename;
                    break;
                }
            }

            if (!$templateFile) {
                $zip->close();
                return [
                    'success' => false,
                    'error' => 'No template JSON file found in ZIP archive',
                    'code' => 'NO_TEMPLATE_FILE'
                ];
            }

            // Extract and process the template file
            $content = $zip->getFromName($templateFile);
            $zip->close();

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

            return $this->processJsonFile($mockFile, $userId, $templateName, $autoActivate);

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
}
