<?php

namespace App\Services;

use App\Models\UserTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ZipArchive;
use DOMDocument;
use DOMXPath;

class AdvancedTemplateImporterService
{
    /**
     * Import complete project from GitHub ZIP or uploaded ZIP
     */
    public function importCompleteProject(string $source, int $userId, array $options = []): array
    {
        try {
            Log::info('Starting complete project import', ['source' => $source, 'user_id' => $userId]);

            if ($this->isGitHubRepo($source)) {
                return $this->importFromGitHubProject($source, $userId, $options);
            } elseif ($this->isZipFile($source)) {
                return $this->importFromZipFile($source, $userId, $options);
            } else {
                throw new \InvalidArgumentException('Source must be GitHub repository URL or ZIP file path');
            }

        } catch (\Exception $e) {
            Log::error('Complete project import failed', [
                'source' => $source,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Import from GitHub repository (download as ZIP)
     */
    protected function importFromGitHubProject(string $repoUrl, int $userId, array $options = []): array
    {
        // Extract owner/repo from GitHub URL
        preg_match('/github\.com\/([^\/]+)\/([^\/\.]+)(?:\.git)?/', $repoUrl, $matches);
        if (count($matches) < 3) {
            throw new \InvalidArgumentException('Invalid GitHub repository URL');
        }

        $owner = trim($matches[1]);
        $repo = trim(str_replace('.git', '', $matches[2]));
        $branch = $options['branch'] ?? 'main';

        Log::info('GitHub project import started', ['owner' => $owner, 'repo' => $repo, 'branch' => $branch]);

        // Try different branch names
        $branches = [$branch, 'master', 'main'];
        $response = null;
        $successfulBranch = null;

        foreach (array_unique($branches) as $tryBranch) {
            $zipUrl = "https://github.com/{$owner}/{$repo}/archive/refs/heads/{$tryBranch}.zip";
            Log::info('Trying GitHub download', ['url' => $zipUrl]);

            $response = Http::timeout(60)->get($zipUrl);
            if ($response->successful()) {
                $successfulBranch = $tryBranch;
                break;
            }
        }

        if (!$response || !$response->successful()) {
            throw new \Exception('Failed to download GitHub repository. Repository might be private or branches not found: ' . implode(', ', $branches));
        }

        // Validate response is ZIP, not HTML error page
        $content = $response->body();
        if (str_starts_with(trim($content), '<!DOCTYPE') || str_starts_with(trim($content), '<html')) {
            throw new \Exception('GitHub returned error page. Repository might be private or not exist.');
        }

        Log::info('GitHub download successful', ['branch' => $successfulBranch, 'size' => strlen($content)]);

        // Save ZIP temporarily
        $tempZipPath = storage_path('app/temp/' . Str::uuid() . '.zip');
        File::ensureDirectoryExists(dirname($tempZipPath));
        File::put($tempZipPath, $content);

        try {
            return $this->processCompleteProjectZip($tempZipPath, $userId, [
                'project_name' => "{$owner}/{$repo}",
                'source_type' => 'github',
                'source_url' => $repoUrl,
                'branch' => $successfulBranch
            ]);
        } finally {
            File::delete($tempZipPath);
        }
    }

    /**
     * Import from uploaded ZIP file
     */
    protected function importFromZipFile(string $zipPath, int $userId, array $options = []): array
    {
        if (!File::exists($zipPath)) {
            throw new \Exception('ZIP file not found');
        }

        return $this->processCompleteProjectZip($zipPath, $userId, [
            'project_name' => $options['name'] ?? 'Imported Project',
            'source_type' => 'upload',
            'source_file' => basename($zipPath)
        ]);
    }

    /**
     * Process complete project ZIP - analyze all files and create template
     */
    protected function processCompleteProjectZip(string $zipPath, int $userId, array $metadata = []): array
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Failed to open ZIP file');
        }

        $projectId = Str::uuid();
        $extractPath = storage_path("app/temp/project_{$projectId}");
        $templateDir = "templates/projects/{$projectId}";

        try {
            // Extract all files
            $zip->extractTo($extractPath);
            $zip->close();

            Log::info('ZIP extracted successfully', ['extract_path' => $extractPath]);

            // Analyze project structure
            $projectAnalysis = $this->analyzeProjectStructure($extractPath);
            Log::info('Project analysis completed', $projectAnalysis['summary']);

            // Find or create main HTML file
            $mainHtmlFile = $this->findOrCreateMainHtml($extractPath, $projectAnalysis);

            // Process all assets and files
            $processedFiles = $this->processAllProjectFiles($extractPath, $templateDir, $projectAnalysis);

            // Generate template data structure for CMS
            $templateData = $this->generateTemplateDataFromProject($projectAnalysis, $mainHtmlFile, $processedFiles);

            // Create UserTemplate record
            $userTemplate = UserTemplate::create([
                'user_id' => $userId,
                'name' => $metadata['project_name'],
                'slug' => Str::slug($metadata['project_name'] . '-' . time()),
                'description' => 'Complete project imported: ' . ($metadata['project_name'] ?? 'Unknown'),
                'template_data' => $templateData,
                'status' => 'active',
                'version' => '1.0',
                'settings' => [
                    'template_type' => 'complete_project',
                    'render_mode' => 'full_site',
                    'main_file' => $mainHtmlFile,
                    'assets_path' => $templateDir,
                    'project_structure' => $projectAnalysis['summary']
                ],
                'metadata' => array_merge($metadata, [
                    'imported_at' => now(),
                    'files_count' => count($processedFiles),
                    'project_type' => $projectAnalysis['project_type']
                ])
            ]);

            return [
                'success' => true,
                'user_template' => $userTemplate,
                'message' => 'Complete project imported successfully',
                'files_imported' => count($processedFiles),
                'main_file' => $mainHtmlFile,
                'project_analysis' => $projectAnalysis['summary']
            ];

        } finally {
            // Clean up
            if (File::exists($extractPath)) {
                File::deleteDirectory($extractPath);
            }
        }
    }

    /**
     * Analyze complete project structure
     */
    protected function analyzeProjectStructure(string $projectPath): array
    {
        $analysis = [
            'html_files' => [],
            'css_files' => [],
            'js_files' => [],
            'image_files' => [],
            'config_files' => [],
            'source_files' => [],
            'build_files' => [],
            'has_package_json' => false,
            'has_index_html' => false,
            'project_type' => 'static',
            'main_directories' => [],
            'all_files' => []
        ];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($projectPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath);
                $extension = strtolower($file->getExtension());
                $filename = $file->getFilename();

                $analysis['all_files'][] = $relativePath;

                // Categorize files
                switch ($extension) {
                    case 'html':
                    case 'htm':
                        $analysis['html_files'][] = $relativePath;
                        if (strtolower($filename) === 'index.html') {
                            $analysis['has_index_html'] = true;
                        }
                        break;
                    case 'css':
                        $analysis['css_files'][] = $relativePath;
                        break;
                    case 'js':
                        $analysis['js_files'][] = $relativePath;
                        break;
                    case 'png':
                    case 'jpg':
                    case 'jpeg':
                    case 'gif':
                    case 'svg':
                    case 'webp':
                        $analysis['image_files'][] = $relativePath;
                        break;
                    case 'json':
                        if ($filename === 'package.json') {
                            $analysis['has_package_json'] = true;
                        }
                        $analysis['config_files'][] = $relativePath;
                        break;
                    case 'md':
                    case 'txt':
                    case 'yml':
                    case 'yaml':
                        $analysis['config_files'][] = $relativePath;
                        break;
                }

                // Detect main directories
                $pathParts = explode('/', $relativePath);
                if (count($pathParts) > 1) {
                    $topDir = $pathParts[0];
                    if (!in_array($topDir, $analysis['main_directories'])) {
                        $analysis['main_directories'][] = $topDir;
                    }
                }
            }
        }

        // Determine project type
        if ($analysis['has_package_json']) {
            $analysis['project_type'] = 'nodejs';
        } elseif (in_array('dist', $analysis['main_directories']) ||
                  in_array('build', $analysis['main_directories'])) {
            $analysis['project_type'] = 'built';
        } elseif (count($analysis['html_files']) > 0) {
            $analysis['project_type'] = 'static';
        } else {
            $analysis['project_type'] = 'source';
        }

        $analysis['summary'] = [
            'total_files' => count($analysis['all_files']),
            'html_count' => count($analysis['html_files']),
            'css_count' => count($analysis['css_files']),
            'js_count' => count($analysis['js_files']),
            'image_count' => count($analysis['image_files']),
            'project_type' => $analysis['project_type'],
            'has_index' => $analysis['has_index_html']
        ];

        return $analysis;
    }

    /**
     * Find or create main HTML file
     */
    protected function findOrCreateMainHtml(string $projectPath, array $analysis): string
    {
        // Priority order for finding main HTML file
        $priorities = [
            'index.html',
            'home.html',
            'main.html',
            'default.html'
        ];

        // Check in root first
        foreach ($priorities as $filename) {
            if (File::exists($projectPath . '/' . $filename)) {
                return $filename;
            }
        }

        // Check in build/dist directories
        $buildDirs = ['dist', 'build', 'public', 'www'];
        foreach ($buildDirs as $dir) {
            foreach ($priorities as $filename) {
                $fullPath = $projectPath . '/' . $dir . '/' . $filename;
                if (File::exists($fullPath)) {
                    return $dir . '/' . $filename;
                }
            }
        }

        // If no main file found, use first HTML file
        if (!empty($analysis['html_files'])) {
            return $analysis['html_files'][0];
        }

        // If no HTML files, create a basic index.html
        $basicHtml = $this->createBasicHtmlFromProject($analysis);
        File::put($projectPath . '/index.html', $basicHtml);
        return 'index.html';
    }

    /**
     * Create basic HTML file from project analysis
     */
    protected function createBasicHtmlFromProject(array $analysis): string
    {
        $title = 'Imported Project Template';
        $cssLinks = '';
        $jsScripts = '';

        // Add CSS files
        foreach ($analysis['css_files'] as $cssFile) {
            $cssLinks .= "<link rel=\"stylesheet\" href=\"{$cssFile}\">\n    ";
        }

        // Add JS files
        foreach ($analysis['js_files'] as $jsFile) {
            $jsScripts .= "<script src=\"{$jsFile}\"></script>\n    ";
        }

        return "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>{$title}</title>
    {$cssLinks}
</head>
<body>
    <div class=\"container\">
        <header>
            <h1>Welcome to {$title}</h1>
            <p>This template was automatically imported and converted for the School CMS.</p>
        </header>

        <main>
            <section class=\"hero\">
                <h2>Template Successfully Imported</h2>
                <p>This project contains {$analysis['summary']['total_files']} files including:</p>
                <ul>
                    <li>{$analysis['summary']['css_count']} CSS files</li>
                    <li>{$analysis['summary']['js_count']} JavaScript files</li>
                    <li>{$analysis['summary']['image_count']} images</li>
                </ul>
                <p>You can now customize this template through the CMS admin panel.</p>
            </section>
        </main>
    </div>

    {$jsScripts}
</body>
</html>";
    }

    /**
     * Process all project files and copy to storage
     */
    protected function processAllProjectFiles(string $projectPath, string $templateDir, array $analysis): array
    {
        $processedFiles = [];
        $storageUrl = url('storage');

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($projectPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath);
                $targetPath = "{$templateDir}/{$relativePath}";

                // Ensure directory exists
                Storage::makeDirectory(dirname($targetPath));

                // Copy file
                Storage::put($targetPath, File::get($file->getPathname()));

                $processedFiles[$relativePath] = [
                    'storage_path' => $targetPath,
                    'public_url' => "{$storageUrl}/{$targetPath}",
                    'size' => $file->getSize(),
                    'type' => $file->getExtension()
                ];
            }
        }

        Log::info('All project files processed', ['file_count' => count($processedFiles)]);
        return $processedFiles;
    }

    /**
     * Generate template data structure for CMS
     */
    protected function generateTemplateDataFromProject(array $analysis, string $mainFile, array $files): array
    {
        return [
            'type' => 'complete_project',
            'templates' => [
                [
                    'name' => 'Complete Project Template',
                    'slug' => 'complete-project',
                    'description' => 'Full project imported with all assets',
                    'active' => true,
                    'type' => 'full_site',
                    'main_file' => $mainFile,
                    'project_info' => $analysis['summary'],
                    'sections' => [
                        [
                            'name' => 'Full Project Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'full_project',
                                    'name' => 'Complete Project Block',
                                    'order' => 1,
                                    'content' => [
                                        'main_file' => $mainFile,
                                        'files' => $files,
                                        'project_type' => $analysis['project_type'],
                                        'file_counts' => $analysis['summary']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    // Helper methods
    protected function isGitHubRepo(string $source): bool
    {
        return strpos($source, 'github.com') !== false;
    }

    protected function isZipFile(string $source): bool
    {
        return str_ends_with(strtolower($source), '.zip') ||
               (File::exists($source) && File::mimeType($source) === 'application/zip');
    }
}
