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
        // Extract owner/repo from GitHub URL
        preg_match('/github\.com\/([^\/]+)\/([^\/]+)/', $repoUrl, $matches);
        if (count($matches) < 3) {
            throw new \InvalidArgumentException('Invalid GitHub repository URL');
        }

        $owner = $matches[1];
        $repo = $matches[2];
        $branch = $options['branch'] ?? 'main';

        // Download as ZIP
        $zipUrl = "https://github.com/{$owner}/{$repo}/archive/refs/heads/{$branch}.zip";

        $response = Http::timeout(60)->get($zipUrl);
        if (!$response->successful()) {
            throw new \Exception('Failed to download GitHub repository');
        }

        // Save ZIP temporarily
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
        $response = Http::timeout(30)->get($url);
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch website: ' . $url);
        }

        $html = $response->body();
        $baseUrl = $this->getBaseUrl($url);

        // Parse HTML and extract all assets
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $assets = $this->extractAssetsFromHtml($dom, $xpath, $baseUrl);

        // Create template directory
        $templateId = Str::uuid();
        $templateDir = "templates/full/{$templateId}";

        // Save main HTML file
        Storage::put("{$templateDir}/index.html", $html);

        // Download and save assets
        $downloadedAssets = $this->downloadAssets($assets, $templateDir);

        // Update HTML with local asset paths
        $processedHtml = $this->updateAssetPaths($html, $assets, $templateDir);
        Storage::put("{$templateDir}/index.html", $processedHtml);

        $files = ['index.html' => "{$templateDir}/index.html"];
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

        // Find main HTML file
        $mainFile = $this->findMainHtmlFile($extractPath);
        if (!$mainFile) {
            File::deleteDirectory($extractPath);
            throw new \Exception('No index.html or main HTML file found in the template');
        }

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
        $possibleFiles = ['index.html', 'home.html', 'main.html', 'default.html'];

        foreach ($possibleFiles as $file) {
            $fullPath = $this->findFileRecursive($dir, $file);
            if ($fullPath) {
                return $fullPath;
            }
        }

        // Find any HTML file
        $htmlFiles = File::glob($dir . '/**/*.html');
        return $htmlFiles[0] ?? null;
    }

    /**
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

        // CSS files
        $cssLinks = $xpath->query('//link[@rel="stylesheet"]/@href');
        foreach ($cssLinks as $href) {
            $url = $this->resolveUrl($href->nodeValue, $baseUrl);
            $assets[] = ['type' => 'css', 'url' => $url, 'local_path' => 'css/' . basename($url)];
        }

        // JavaScript files
        $jsScripts = $xpath->query('//script[@src]/@src');
        foreach ($jsScripts as $src) {
            $url = $this->resolveUrl($src->nodeValue, $baseUrl);
            $assets[] = ['type' => 'js', 'url' => $url, 'local_path' => 'js/' . basename($url)];
        }

        // Images
        $images = $xpath->query('//img/@src');
        foreach ($images as $src) {
            $url = $this->resolveUrl($src->nodeValue, $baseUrl);
            $assets[] = ['type' => 'image', 'url' => $url, 'local_path' => 'images/' . basename($url)];
        }

        return $assets;
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
}
