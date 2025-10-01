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

class FullTemplateImporterService
{
    /**
     * Import full template from GitHub repository or URL
     */
    public function importFullTemplate(string $source, int $userId, array $options = []): array
    {
        try {
            Log::info('Starting full template import', ['source' => $source, 'user_id' => $userId]);

            $importResult = $this->detectAndImportSource($source, $options);

            if (!$importResult['success']) {
                return $importResult;
            }

            // Create user template record
            $userTemplate = UserTemplate::create([
                'user_id' => $userId,
                'name' => $importResult['template_name'],
                'slug' => Str::slug($importResult['template_name'] . '-' . time()),
                'description' => $importResult['description'] ?? 'Imported full template',
                'template_data' => [
                    'type' => 'full_template',
                    'source' => $source,
                    'main_file' => $importResult['main_file'],
                    'assets_path' => $importResult['assets_path'],
                    'files' => $importResult['files'],
                    'metadata' => $importResult['metadata']
                ],
                'status' => 'active',
                'version' => '1.0',
                'settings' => [
                    'template_type' => 'full_template',
                    'render_mode' => 'direct'
                ]
            ]);

            return [
                'success' => true,
                'user_template' => $userTemplate,
                'message' => 'Template imported successfully',
                'files_imported' => count($importResult['files']),
                'main_file' => $importResult['main_file']
            ];

        } catch (\Exception $e) {
            Log::error('Full template import failed', [
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
     * Detect source type and import accordingly
     */
    protected function detectAndImportSource(string $source, array $options = []): array
    {
        if ($this->isGitHubRepo($source)) {
            return $this->importFromGitHub($source, $options);
        } elseif ($this->isZipFile($source)) {
            return $this->importFromZip($source, $options);
        } elseif ($this->isWebsite($source)) {
            return $this->importFromWebsite($source, $options);
        } else {
            throw new \InvalidArgumentException('Unsupported source type. Use GitHub repo, ZIP file, or website URL.');
        }
    }

    /**
     * Import from GitHub repository
     */
    protected function importFromGitHub(string $repoUrl, array $options = []): array
    {
        // Extract owner/repo from GitHub URL and handle .git suffix
        preg_match('/github\.com\/([^\/]+)\/([^\/\.]+)(?:\.git)?/', $repoUrl, $matches);
        if (count($matches) < 3) {
            // Try alternative patterns
            preg_match('/github\.com\/([^\/]+)\/([^\?\/]+)/', $repoUrl, $matches);
            if (count($matches) < 3) {
                throw new \InvalidArgumentException('Invalid GitHub repository URL: ' . $repoUrl);
            }
        }

        $owner = trim($matches[1]);
        $repo = trim(str_replace('.git', '', $matches[2]));
        $branch = $options['branch'] ?? 'main';

        Log::info('GitHub import started', ['owner' => $owner, 'repo' => $repo, 'branch' => $branch]);

        // Try different branch names if main fails
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
            throw new \Exception('Failed to download GitHub repository. Tried branches: ' . implode(', ', $branches));
        }

        Log::info('GitHub download successful', ['branch' => $successfulBranch, 'size' => strlen($response->body())]);        // Save ZIP temporarily
        $tempZipPath = storage_path('app/temp/' . Str::uuid() . '.zip');
        File::ensureDirectoryExists(dirname($tempZipPath));
        File::put($tempZipPath, $response->body());

        try {
            $result = $this->extractAndProcessZip($tempZipPath, [
                'template_name' => "{$owner}/{$repo}",
                'source_type' => 'github'
            ]);

            return $result;
        } finally {
            File::delete($tempZipPath);
        }
    }

    /**
     * Import from ZIP file
     */
    protected function importFromZip(string $zipPath, array $options = []): array
    {
        if (!File::exists($zipPath)) {
            throw new \Exception('ZIP file not found: ' . $zipPath);
        }

        return $this->extractAndProcessZip($zipPath, [
            'template_name' => $options['name'] ?? 'Imported Template',
            'source_type' => 'zip'
        ]);
    }

    /**
     * Import from website (crawl and download)
     */
    protected function importFromWebsite(string $url, array $options = []): array
    {
        Log::info('Website import started', ['url' => $url]);

        $response = Http::timeout(30)->get($url);
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch website: ' . $url . ' (Status: ' . $response->status() . ')');
        }

        $html = $response->body();

        // Check if we got valid HTML
        if (empty($html) || !str_contains($html, '<html')) {
            throw new \Exception('The URL did not return valid HTML content. It might be an API endpoint or require authentication.');
        }

        $baseUrl = $this->getBaseUrl($url);
        Log::info('HTML fetched successfully', ['size' => strlen($html), 'base_url' => $baseUrl]);

        // Parse HTML and extract all assets
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress HTML parsing errors
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);

        $assets = $this->extractAssetsFromHtml($dom, $xpath, $baseUrl);
        Log::info('Assets extracted', ['asset_count' => count($assets)]);

        // Create template directory
        $templateId = Str::uuid();
        $templateDir = "templates/full/{$templateId}";

        // Download and save assets
        $downloadedAssets = $this->downloadAssets($assets, $templateDir);
        Log::info('Assets downloaded', ['downloaded_count' => count($downloadedAssets)]);

        // Update HTML with local asset paths
        $processedHtml = $this->updateAssetPaths($html, $assets, $templateDir);

        // Save processed HTML file
        Storage::put("{$templateDir}/index.html", $processedHtml);        $files = ['index.html' => "{$templateDir}/index.html"];
        $files = array_merge($files, $downloadedAssets);

        return [
            'success' => true,
            'template_name' => $options['name'] ?? parse_url($url, PHP_URL_HOST),
            'description' => "Imported from {$url}",
            'main_file' => 'index.html',
            'assets_path' => $templateDir,
            'files' => $files,
            'metadata' => [
                'source_url' => $url,
                'imported_at' => now(),
                'assets_count' => count($downloadedAssets)
            ]
        ];
    }

    /**
     * Extract and process ZIP file
     */
    protected function extractAndProcessZip(string $zipPath, array $metadata = []): array
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Failed to open ZIP file');
        }

        $templateId = Str::uuid();
        $extractPath = storage_path("app/temp/extract_{$templateId}");
        $templateDir = "templates/full/{$templateId}";

        // Extract ZIP
        $zip->extractTo($extractPath);
        $zip->close();

        // Find main HTML file with enhanced detection
        $mainFile = $this->findMainHtmlFile($extractPath);
        if (!$mainFile) {
            // Try to find any HTML file and log the structure
            $allFiles = $this->getAllFiles($extractPath);
            Log::warning('No main HTML file found', [
                'extract_path' => $extractPath,
                'all_files' => array_slice($allFiles, 0, 20) // Log first 20 files
            ]);

            // Check if this might be a source code repository without built HTML
            if ($this->looksLikeSourceRepo($extractPath)) {
                File::deleteDirectory($extractPath);
                throw new \Exception('This appears to be a source code repository. Please use a repository with built HTML files (like dist/ or build/ folder) or a ready-to-use template.');
            }

            File::deleteDirectory($extractPath);
            throw new \Exception('No HTML file found in the template. Available files: ' . implode(', ', array_slice($allFiles, 0, 10)));
        }

        Log::info('Main file found', ['main_file' => $mainFile]);

        // Copy all files to storage
        $files = $this->copyTemplateFiles($extractPath, $templateDir);

        // Clean up temp directory
        File::deleteDirectory($extractPath);

        return [
            'success' => true,
            'template_name' => $metadata['template_name'] ?? 'Imported Template',
            'description' => "Full template imported from {$metadata['source_type']}",
            'main_file' => str_replace($extractPath . '/', '', $mainFile),
            'assets_path' => $templateDir,
            'files' => $files,
            'metadata' => array_merge($metadata, [
                'imported_at' => now(),
                'files_count' => count($files)
            ])
        ];
    }

    /**
     * Find main HTML file in extracted directory
     */
    protected function findMainHtmlFile(string $dir): ?string
    {
        // First, look for common main file names in root
        $possibleFiles = ['index.html', 'home.html', 'main.html', 'default.html'];

        foreach ($possibleFiles as $file) {
            $rootFile = $dir . '/' . $file;
            if (file_exists($rootFile)) {
                return $rootFile;
            }
        }

        // Then look for them recursively, but prefer ones in dist/build folders
        $preferredPaths = ['dist', 'build', 'public', 'www', 'docs'];

        foreach ($preferredPaths as $path) {
            foreach ($possibleFiles as $file) {
                $fullPath = $this->findFileRecursive($dir . '/' . $path, $file);
                if ($fullPath && file_exists($fullPath)) {
                    return $fullPath;
                }
            }
        }

        // Look for any main file recursively
        foreach ($possibleFiles as $file) {
            $fullPath = $this->findFileRecursive($dir, $file);
            if ($fullPath) {
                return $fullPath;
            }
        }

        // Find any HTML file, preferring ones not in source folders
        $htmlFiles = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'html') {
                $htmlFiles[] = $file->getPathname();
            }
        }

        if (!empty($htmlFiles)) {
            // Prefer HTML files not in src, source, or similar folders
            foreach ($htmlFiles as $htmlFile) {
                if (!preg_match('/\/(src|source|scss|sass|less|node_modules)\//', $htmlFile)) {
                    return $htmlFile;
                }
            }
            return $htmlFiles[0]; // Fallback to first HTML file
        }

        return null;
    }    /**
     * Find file recursively in directory
     */
    protected function findFileRecursive(string $dir, string $filename): ?string
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getFilename() === $filename) {
                return $file->getPathname();
            }
        }

        return null;
    }

    /**
     * Copy all template files to storage
     */
    protected function copyTemplateFiles(string $sourceDir, string $targetDir): array
    {
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($sourceDir . '/', '', $file->getPathname());
                $targetPath = "{$targetDir}/{$relativePath}";

                // Ensure directory exists
                Storage::makeDirectory(dirname($targetPath));

                // Copy file
                Storage::put($targetPath, File::get($file->getPathname()));

                $files[$relativePath] = $targetPath;
            }
        }

        return $files;
    }

    /**
     * Extract assets from HTML
     */
    protected function extractAssetsFromHtml(DOMDocument $dom, DOMXPath $xpath, string $baseUrl): array
    {
        $assets = [];

        // CSS files - look for both link and @import
        $cssLinks = $xpath->query('//link[@rel="stylesheet"]/@href | //link[@type="text/css"]/@href');
        foreach ($cssLinks as $href) {
            $url = $this->resolveUrl($href->nodeValue, $baseUrl);
            if ($this->isValidAssetUrl($url)) {
                $assets[] = ['type' => 'css', 'url' => $url, 'local_path' => 'css/' . $this->getAssetFilename($url, 'css')];
            }
        }

        // JavaScript files
        $jsScripts = $xpath->query('//script[@src]/@src');
        foreach ($jsScripts as $src) {
            $url = $this->resolveUrl($src->nodeValue, $baseUrl);
            if ($this->isValidAssetUrl($url)) {
                $assets[] = ['type' => 'js', 'url' => $url, 'local_path' => 'js/' . $this->getAssetFilename($url, 'js')];
            }
        }

        // Images - all types
        $images = $xpath->query('//img/@src | //img/@data-src | //*[@style]');
        foreach ($images as $node) {
            if ($node->nodeName === 'src' || $node->nodeName === 'data-src') {
                $url = $this->resolveUrl($node->nodeValue, $baseUrl);
            } else {
                // Extract background images from style attribute
                $style = $node->nodeValue;
                if (preg_match('/background-image\s*:\s*url\(["\']?([^"\'\)]+)["\']?\)/', $style, $matches)) {
                    $url = $this->resolveUrl($matches[1], $baseUrl);
                } else {
                    continue;
                }
            }

            if ($this->isValidAssetUrl($url) && $this->isImageUrl($url)) {
                $assets[] = ['type' => 'image', 'url' => $url, 'local_path' => 'images/' . $this->getAssetFilename($url, 'img')];
            }
        }

        // Fonts
        $fontLinks = $xpath->query('//link[contains(@href, "font") or contains(@href, ".woff") or contains(@href, ".ttf")]/@href');
        foreach ($fontLinks as $href) {
            $url = $this->resolveUrl($href->nodeValue, $baseUrl);
            if ($this->isValidAssetUrl($url)) {
                $assets[] = ['type' => 'font', 'url' => $url, 'local_path' => 'fonts/' . $this->getAssetFilename($url, 'font')];
            }
        }

        return array_unique($assets, SORT_REGULAR);
    }

    /**
     * Download assets and save locally
     */
    protected function downloadAssets(array $assets, string $templateDir): array
    {
        $downloadedFiles = [];

        foreach ($assets as $asset) {
            try {
                $response = Http::timeout(10)->get($asset['url']);
                if ($response->successful()) {
                    $localPath = "{$templateDir}/{$asset['local_path']}";
                    Storage::put($localPath, $response->body());
                    $downloadedFiles[$asset['local_path']] = $localPath;
                }
            } catch (\Exception $e) {
                Log::warning('Failed to download asset', ['url' => $asset['url'], 'error' => $e->getMessage()]);
            }
        }

        return $downloadedFiles;
    }

    /**
     * Update asset paths in HTML to use local files
     */
    protected function updateAssetPaths(string $html, array $assets, string $templateDir): string
    {
        $storageUrl = url('storage');

        foreach ($assets as $asset) {
            $localUrl = "{$storageUrl}/{$templateDir}/{$asset['local_path']}";
            $html = str_replace($asset['url'], $localUrl, $html);
        }

        return $html;
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

    protected function isWebsite(string $source): bool
    {
        return filter_var($source, FILTER_VALIDATE_URL) !== false;
    }

    protected function getBaseUrl(string $url): string
    {
        $parsed = parse_url($url);
        return $parsed['scheme'] . '://' . $parsed['host'] . (isset($parsed['port']) ? ':' . $parsed['port'] : '');
    }

    protected function resolveUrl(string $url, string $baseUrl): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url; // Already absolute URL
        }

        if (str_starts_with($url, '//')) {
            return 'https:' . $url;
        }

        if (str_starts_with($url, '/')) {
            return rtrim($baseUrl, '/') . $url;
        }

        return rtrim($baseUrl, '/') . '/' . ltrim($url, '/');
    }

    /**
     * Get all files in directory for debugging
     */
    protected function getAllFiles(string $dir): array
    {
        $files = [];
        if (!is_dir($dir)) {
            return $files;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[] = str_replace($dir . '/', '', $file->getPathname());
            }
        }

        return $files;
    }

    /**
     * Check if directory looks like source code repo (not built template)
     */
    protected function looksLikeSourceRepo(string $dir): bool
    {
        $sourceIndicators = [
            'package.json',
            'webpack.config.js',
            'gulpfile.js',
            'src/',
            'source/',
            'scss/',
            'sass/',
            'less/',
            'node_modules/',
            '.git/'
        ];

        foreach ($sourceIndicators as $indicator) {
            if (file_exists($dir . '/' . $indicator) || is_dir($dir . '/' . $indicator)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if URL is valid for asset download
     */
    protected function isValidAssetUrl(string $url): bool
    {
        // Skip data URLs, javascript, etc.
        if (str_starts_with($url, 'data:') ||
            str_starts_with($url, 'javascript:') ||
            str_starts_with($url, 'mailto:') ||
            str_starts_with($url, '#')) {
            return false;
        }

        // Must be valid URL
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Check if URL points to an image
     */
    protected function isImageUrl(string $url): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'];
        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        return in_array($extension, $imageExtensions);
    }

    /**
     * Generate safe filename for asset
     */
    protected function getAssetFilename(string $url, string $type): string
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';
        $filename = basename($path);

        // If no filename, generate one
        if (empty($filename) || strpos($filename, '.') === false) {
            $extension = $this->getDefaultExtension($type);
            $filename = 'asset_' . substr(md5($url), 0, 8) . '.' . $extension;
        }

        // Sanitize filename
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

        return $filename;
    }

    /**
     * Get default file extension for asset type
     */
    protected function getDefaultExtension(string $type): string
    {
        return match($type) {
            'css' => 'css',
            'js' => 'js',
            'img', 'image' => 'jpg',
            'font' => 'woff',
            default => 'txt'
        };
    }
}
