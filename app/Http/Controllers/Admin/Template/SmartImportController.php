<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SmartImportController extends Controller
{
    public function index()
    {
        return view('admin.templates.smart-import.index', [
            'recent_imports' => $this->getRecentImports(),
            'import_stats' => $this->getImportStats()
        ]);
    }

    /**
     * Import template file
     */
    public function importFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:zip|max:51200', // 50MB max
                'template_name' => 'nullable|string|max:255',
                'auto_activate' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed: ' . $validator->errors()->first(),
                    'code' => 'VALIDATION_FAILED'
                ], 422);
            }

            $file = $request->file('file');
            $templateName = $request->input('template_name');
            $autoActivate = $request->boolean('auto_activate', false);
            $userId = Auth::id();

            Log::info('Starting template import', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'template_name' => $templateName,
                'user_id' => $userId
            ]);

            // Process the uploaded file
            $result = $this->processUploadedFile($file, $userId, $templateName, $autoActivate);

            if ($result['success']) {
                Log::info('Template import successful', [
                    'template_id' => $result['template_id'] ?? null,
                    'template_name' => $result['template_name'] ?? null
                ]);
            } else {
                Log::warning('Template import failed', [
                    'error' => $result['error'] ?? 'Unknown error',
                    'code' => $result['code'] ?? 'UNKNOWN'
                ]);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Template import exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred while importing the template.',
                'code' => 'IMPORT_EXCEPTION'
            ], 500);
        }
    }

    /**
     * Process uploaded file based on type
     */
    protected function processUploadedFile($file, int $userId, ?string $templateName, bool $autoActivate): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        switch ($extension) {
            case 'zip':
                return $this->processZipFile($file, $userId, $templateName, $autoActivate);
            default:
                return [
                    'success' => false,
                    'error' => 'Unsupported file type. Only ZIP files are supported.',
                    'code' => 'UNSUPPORTED_FILE_TYPE'
                ];
        }
    }

    /**
     * Process ZIP file containing template files
     */
    protected function processZipFile($file, int $userId, ?string $templateName, bool $autoActivate): array
    {
        $zip = new \ZipArchive();
        $result = $zip->open($file->getRealPath());

        if ($result !== TRUE) {
            return [
                'success' => false,
                'error' => 'Failed to open ZIP file: ' . $this->getZipError($result),
                'code' => 'ZIP_OPEN_FAILED'
            ];
        }

        try {
            // Extract all files for analysis
            $extractedFiles = [];
            $fileStructure = [
                'blade_files' => [],
                'php_files' => [],
                'controller_files' => [],
                'route_files' => [],
                'css_files' => [],
                'js_files' => [],
                'image_files' => [],
                'layout_files' => [],
                'view_files' => [],
                'has_views_folder' => false,
                'has_resources_folder' => false,
                'has_public_folder' => false,
                'has_app_folder' => false,
                'has_routes_folder' => false,
                'has_controllers' => false,
                'is_full_laravel_structure' => false,
                'total_files' => 0
            ];

            Log::info('Analyzing ZIP structure', ['file' => $file->getClientOriginalName()]);

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                if (substr($filename, -1) === '/') {
                    continue; // Skip directories
                }

                $content = $zip->getFromIndex($i);
                if ($content === false) {
                    Log::warning('Failed to extract file from ZIP', ['filename' => $filename]);
                    continue;
                }

                $extractedFiles[$filename] = [
                    'content' => base64_encode($content),
                    'size' => strlen($content)
                ];

                $fileStructure['total_files']++;
                $this->analyzeFileStructure($filename, $fileStructure);
            }

            $zip->close();

            Log::info('ZIP structure analyzed', [
                'total_files' => $fileStructure['total_files'],
                'blade_files' => count($fileStructure['blade_files']),
                'php_files' => count($fileStructure['php_files']),
                'controller_files' => count($fileStructure['controller_files']),
                'route_files' => count($fileStructure['route_files']),
                'has_views_folder' => $fileStructure['has_views_folder'],
                'is_full_laravel_structure' => $fileStructure['is_full_laravel_structure']
            ]);

            // Determine template type and process accordingly
            return $this->processUnifiedTemplate($extractedFiles, $fileStructure, $userId, $templateName, $autoActivate);

        } catch (\Exception $e) {
            $zip->close();
            throw $e;
        }
    }

    /**
     * Analyze file structure to determine template type
     */
    protected function analyzeFileStructure(string $filename, array &$structure): void
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $path = strtolower(dirname($filename));
        $basename = strtolower(basename($filename));

        // Check for Laravel folder structures
        if (str_contains($path, 'views') || str_contains($path, 'templates')) {
            $structure['has_views_folder'] = true;
        }
        if (str_contains($path, 'resources')) {
            $structure['has_resources_folder'] = true;
        }
        if (str_contains($path, 'public') || str_contains($path, 'assets')) {
            $structure['has_public_folder'] = true;
        }
        if (str_contains($path, 'app/http/controllers') || str_contains($path, 'app\\http\\controllers')) {
            $structure['has_app_folder'] = true;
            $structure['has_controllers'] = true;
        }
        if (str_contains($path, 'routes')) {
            $structure['has_routes_folder'] = true;
        }

        // Detect full Laravel structure
        if ($structure['has_resources_folder'] && $structure['has_app_folder'] && $structure['has_public_folder']) {
            $structure['is_full_laravel_structure'] = true;
        }

        // Categorize files
        switch ($extension) {
            case 'php':
                if (str_contains($basename, '.blade.php')) {
                    $structure['blade_files'][] = $filename;
                    // Check if it's a layout file
                    if (str_contains($basename, 'layout') || str_contains($basename, 'app.blade') || str_contains($basename, 'main.blade')) {
                        $structure['layout_files'][] = $filename;
                    } else {
                        $structure['view_files'][] = $filename;
                    }
                } else {
                    // Regular PHP files
                    $structure['php_files'][] = $filename;

                    // Check if it's a controller
                    if (str_contains($path, 'controller') || str_contains($basename, 'controller.php')) {
                        $structure['controller_files'][] = $filename;
                    }

                    // Check if it's a route file
                    if (str_contains($path, 'routes') || $basename === 'web.php' || $basename === 'api.php') {
                        $structure['route_files'][] = $filename;
                    }
                }
                break;
            case 'css':
                $structure['css_files'][] = $filename;
                break;
            case 'js':
                $structure['js_files'][] = $filename;
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'svg':
                $structure['image_files'][] = $filename;
                break;
        }
    }

    /**
     * Process template based on detected structure
     */
    protected function processUnifiedTemplate(array $extractedFiles, array $fileStructure, int $userId, ?string $templateName, bool $autoActivate): array
    {
        // Priority 1: Full Laravel Structure (with controllers, routes, and views)
        if ($fileStructure['is_full_laravel_structure'] && !empty($fileStructure['blade_files'])) {
            Log::info('Processing as Full Laravel Template', [
                'blade_files' => count($fileStructure['blade_files']),
                'controller_files' => count($fileStructure['controller_files']),
                'route_files' => count($fileStructure['route_files']),
                'has_full_structure' => true
            ]);
            return $this->processFullLaravelTemplate($extractedFiles, $fileStructure, $userId, $templateName, $autoActivate);
        }

        // Priority 2: Laravel Blade Views (.blade.php files)
        if (!empty($fileStructure['blade_files']) || ($fileStructure['has_views_folder'] && !empty($fileStructure['view_files']))) {
            Log::info('Processing as Laravel Blade template', [
                'blade_files' => count($fileStructure['blade_files']),
                'layout_files' => count($fileStructure['layout_files']),
                'view_files' => count($fileStructure['view_files'])
            ]);
            return $this->processBladeTemplate($extractedFiles, $fileStructure, $userId, $templateName, $autoActivate);
        }

        // Priority 3: PHP files (regular PHP templates)
        if (!empty($fileStructure['php_files'])) {
            Log::info('Processing as PHP template', [
                'php_files' => count($fileStructure['php_files'])
            ]);
            return $this->processPhpTemplate($extractedFiles, $fileStructure, $userId, $templateName, $autoActivate);
        }

        // No supported files found
        return [
            'success' => false,
            'error' => 'No supported template files found. Please ensure your ZIP contains Laravel Blade views (.blade.php), PHP files (.php), or a full Laravel structure.',
            'code' => 'NO_SUPPORTED_FILES',
            'debug' => [
                'total_files' => $fileStructure['total_files'],
                'blade_files' => count($fileStructure['blade_files'] ?? []),
                'php_files' => count($fileStructure['php_files'] ?? []),
                'controller_files' => count($fileStructure['controller_files'] ?? []),
                'route_files' => count($fileStructure['route_files'] ?? []),
                'has_views_folder' => $fileStructure['has_views_folder'],
                'is_full_laravel_structure' => $fileStructure['is_full_laravel_structure']
            ]
        ];
    }

    /**
     * Process Full Laravel Template (with controllers, routes, views, and assets)
     */
    protected function processFullLaravelTemplate(array $extractedFiles, array $fileStructure, int $userId, ?string $templateName, bool $autoActivate): array
    {
        // For now, treat full Laravel structure the same as blade template but with enhanced metadata
        // Future enhancement can add controller and route installation

        // Determine primary layout file
        $primaryLayout = null;
        if (!empty($fileStructure['layout_files'])) {
            $primaryLayout = $fileStructure['layout_files'][0];
        } elseif (!empty($fileStructure['blade_files'])) {
            $primaryLayout = $fileStructure['blade_files'][0];
        }

        if (!$primaryLayout) {
            return [
                'success' => false,
                'error' => 'No Blade template files found in the Laravel template.',
                'code' => 'NO_BLADE_FILES'
            ];
        }

        // Generate template name
        if (!$templateName) {
            $templateName = 'Full Laravel Template ' . date('Y-m-d H:i:s');
        }

        // Create the template structure with enhanced metadata
        $templateStructure = [
            'template_type' => 'full_laravel',
            'primary_file' => $primaryLayout,
            'structure' => [
                'blade_files' => $fileStructure['blade_files'],
                'layout_files' => $fileStructure['layout_files'],
                'view_files' => $fileStructure['view_files'],
                'controller_files' => $fileStructure['controller_files'],
                'route_files' => $fileStructure['route_files'],
                'css_files' => $fileStructure['css_files'],
                'js_files' => $fileStructure['js_files'],
                'image_files' => $fileStructure['image_files']
            ],
            'metadata' => [
                'total_files' => $fileStructure['total_files'],
                'blade_count' => count($fileStructure['blade_files']),
                'controller_count' => count($fileStructure['controller_files']),
                'route_count' => count($fileStructure['route_files']),
                'has_full_structure' => true,
                'has_views_folder' => $fileStructure['has_views_folder'],
                'has_resources_folder' => $fileStructure['has_resources_folder'],
                'has_app_folder' => $fileStructure['has_app_folder'],
                'has_public_folder' => $fileStructure['has_public_folder']
            ]
        ];

        // Create UserTemplate
        $template = UserTemplate::create([
            'user_id' => $userId,
            'name' => $templateName,
            'slug' => Str::slug($templateName . '-' . time()),
            'description' => "Full Laravel template with " . count($fileStructure['blade_files']) . " views, " . count($fileStructure['controller_files']) . " controllers, and " . count($fileStructure['route_files']) . " route files",
            'template_type' => 'blade_views', // Use same type for compatibility with rendering system
            'template_data' => $templateStructure,
            'template_files' => $extractedFiles,
            'source' => 'import',
            'is_active' => false,
            'settings' => [
                'import_date' => now()->toISOString(),
                'file_count' => $fileStructure['total_files'],
                'primary_file' => $primaryLayout,
                'is_full_laravel' => true
            ]
        ]);

        // Activate template if requested
        if ($autoActivate) {
            $template->activate();
        }

        Log::info('Full Laravel template created successfully', [
            'template_id' => $template->id,
            'template_name' => $template->name,
            'blade_files' => count($fileStructure['blade_files']),
            'controller_files' => count($fileStructure['controller_files']),
            'route_files' => count($fileStructure['route_files'])
        ]);

        return [
            'success' => true,
            'template_id' => $template->id,
            'template_name' => $template->name,
            'template_type' => 'full_laravel',
            'message' => 'Full Laravel template imported successfully. Views will be available immediately. Controllers and routes are stored for future implementation.',
            'stats' => [
                'blade_files' => count($fileStructure['blade_files']),
                'layout_files' => count($fileStructure['layout_files']),
                'controller_files' => count($fileStructure['controller_files']),
                'route_files' => count($fileStructure['route_files']),
                'css_files' => count($fileStructure['css_files']),
                'js_files' => count($fileStructure['js_files']),
                'image_files' => count($fileStructure['image_files']),
                'total_files' => $fileStructure['total_files']
            ]
        ];
    }

    /**
     * Process Laravel Blade template
     */
    protected function processBladeTemplate(array $extractedFiles, array $fileStructure, int $userId, ?string $templateName, bool $autoActivate): array
    {
        // Determine primary layout file
        $primaryLayout = null;
        if (!empty($fileStructure['layout_files'])) {
            $primaryLayout = $fileStructure['layout_files'][0];
        } elseif (!empty($fileStructure['blade_files'])) {
            $primaryLayout = $fileStructure['blade_files'][0];
        }

        if (!$primaryLayout) {
            return [
                'success' => false,
                'error' => 'No Blade template files found in the ZIP.',
                'code' => 'NO_BLADE_FILES'
            ];
        }

        // Generate template name
        if (!$templateName) {
            $templateName = 'Blade Template ' . date('Y-m-d H:i:s');
        }

        // Create the template structure
        $templateStructure = [
            'template_type' => 'blade',
            'primary_file' => $primaryLayout,
            'structure' => [
                'blade_files' => $fileStructure['blade_files'],
                'layout_files' => $fileStructure['layout_files'],
                'view_files' => $fileStructure['view_files'],
                'css_files' => $fileStructure['css_files'],
                'js_files' => $fileStructure['js_files'],
                'image_files' => $fileStructure['image_files']
            ],
            'metadata' => [
                'total_files' => $fileStructure['total_files'],
                'blade_count' => count($fileStructure['blade_files']),
                'has_views_folder' => $fileStructure['has_views_folder']
            ]
        ];

        // Create UserTemplate
        $template = UserTemplate::create([
            'user_id' => $userId,
            'name' => $templateName,
            'slug' => Str::slug($templateName . '-' . time()),
            'description' => "Imported Laravel Blade template with " . count($fileStructure['blade_files']) . " Blade files",
            'template_type' => 'blade_views', // Changed to match HomeController
            'template_data' => $templateStructure,
            'template_files' => $extractedFiles, // Store as array, not base64
            'source' => 'import',
            'is_active' => false, // Always create as inactive first
            'settings' => [
                'import_date' => now()->toISOString(),
                'file_count' => $fileStructure['total_files'],
                'primary_file' => $primaryLayout
            ]
        ]);

        // Activate template if requested
        if ($autoActivate) {
            $template->activate();
        }

        Log::info('Blade template created successfully', [
            'template_id' => $template->id,
            'template_name' => $template->name,
            'blade_files' => count($fileStructure['blade_files'])
        ]);

        return [
            'success' => true,
            'template_id' => $template->id,
            'template_name' => $template->name,
            'template_type' => 'blade',
            'message' => 'Laravel Blade template imported successfully with ' . count($fileStructure['blade_files']) . ' Blade files.',
            'stats' => [
                'blade_files' => count($fileStructure['blade_files']),
                'layout_files' => count($fileStructure['layout_files']),
                'total_files' => $fileStructure['total_files']
            ]
        ];
    }

    /**
     * Process PHP template
     */
    protected function processPhpTemplate(array $extractedFiles, array $fileStructure, int $userId, ?string $templateName, bool $autoActivate): array
    {
        // Find main PHP file
        $mainPhpFile = null;
        foreach ($fileStructure['php_files'] as $phpFile) {
            $basename = strtolower(basename($phpFile));
            if (str_contains($basename, 'index') || str_contains($basename, 'main') || str_contains($basename, 'home')) {
                $mainPhpFile = $phpFile;
                break;
            }
        }

        if (!$mainPhpFile) {
            $mainPhpFile = $fileStructure['php_files'][0];
        }

        // Generate template name
        if (!$templateName) {
            $templateName = 'PHP Template ' . date('Y-m-d H:i:s');
        }

        // Create the template structure
        $templateStructure = [
            'template_type' => 'php',
            'primary_file' => $mainPhpFile,
            'structure' => [
                'php_files' => $fileStructure['php_files'],
                'css_files' => $fileStructure['css_files'],
                'js_files' => $fileStructure['js_files'],
                'image_files' => $fileStructure['image_files']
            ],
            'metadata' => [
                'total_files' => $fileStructure['total_files'],
                'php_count' => count($fileStructure['php_files'])
            ]
        ];

        // Create UserTemplate
        $template = UserTemplate::create([
            'user_id' => $userId,
            'name' => $templateName,
            'slug' => Str::slug($templateName . '-' . time()),
            'description' => "Imported PHP template with " . count($fileStructure['php_files']) . " PHP files",
            'template_type' => 'blade_views', // Use blade_views for compatibility
            'template_data' => $templateStructure,
            'template_files' => $extractedFiles,
            'source' => 'import',
            'is_active' => false,
            'settings' => [
                'import_date' => now()->toISOString(),
                'file_count' => $fileStructure['total_files'],
                'primary_file' => $mainPhpFile
            ]
        ]);

        // Activate template if requested
        if ($autoActivate) {
            $template->activate();
        }

        Log::info('PHP template created successfully', [
            'template_id' => $template->id,
            'template_name' => $template->name,
            'php_files' => count($fileStructure['php_files'])
        ]);

        return [
            'success' => true,
            'template_id' => $template->id,
            'template_name' => $template->name,
            'template_type' => 'php',
            'message' => 'PHP template imported successfully with ' . count($fileStructure['php_files']) . ' PHP files.',
            'stats' => [
                'php_files' => count($fileStructure['php_files']),
                'total_files' => $fileStructure['total_files']
            ]
        ];
    }

    /**
     * Get recent imports for dashboard
     */
    protected function getRecentImports(): array
    {
        return UserTemplate::where('source', 'import')
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    /**
     * Get import statistics
     */
    protected function getImportStats(): array
    {
        return [
            'total_imported' => UserTemplate::where('source', 'import')->count(),
            'active_templates' => UserTemplate::where('is_active', true)->count(),
            'recent_count' => UserTemplate::where('source', 'import')
                ->where('created_at', '>=', now()->subDays(7))
                ->count()
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
            \ZipArchive::ER_DELETED => 'Entry has been deleted'
        ];

        return $errors[$code] ?? 'Unknown error';
    }
}
