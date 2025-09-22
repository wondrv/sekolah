<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemeService
{
    protected string $themesPath;
    protected string $activeTheme;

    public function __construct()
    {
        $this->themesPath = resource_path('views/themes');
        $this->activeTheme = $this->getActiveTheme();
    }

    /**
     * Get active theme name
     */
    public function getActiveTheme(): string
    {
        return Cache::remember('active_theme', 3600, function () {
            return Setting::where('key', 'active_theme')->value('value') ?? 'default';
        });
    }

    /**
     * Set active theme
     */
    public function setActiveTheme(string $theme): bool
    {
        if (!$this->themeExists($theme)) {
            return false;
        }

        Setting::updateOrCreate(
            ['key' => 'active_theme'],
            ['value' => $theme]
        );

        Cache::forget('active_theme');
        $this->activeTheme = $theme;

        return true;
    }

    /**
     * Get all available themes
     */
    public function getAvailableThemes(): array
    {
        if (!File::exists($this->themesPath)) {
            return [];
        }

        $themes = [];
        $directories = File::directories($this->themesPath);

        foreach ($directories as $directory) {
            $themeName = basename($directory);
            $themeConfig = $this->getThemeConfig($themeName);
            
            $themes[$themeName] = [
                'name' => $themeName,
                'title' => $themeConfig['title'] ?? ucfirst($themeName),
                'description' => $themeConfig['description'] ?? '',
                'version' => $themeConfig['version'] ?? '1.0.0',
                'author' => $themeConfig['author'] ?? '',
                'screenshot' => $themeConfig['screenshot'] ?? null,
                'active' => $themeName === $this->activeTheme
            ];
        }

        return $themes;
    }

    /**
     * Get theme configuration
     */
    public function getThemeConfig(string $theme): array
    {
        $configPath = $this->themesPath . "/{$theme}/theme.json";
        
        if (!File::exists($configPath)) {
            return [];
        }

        return json_decode(File::get($configPath), true) ?? [];
    }

    /**
     * Check if theme exists
     */
    public function themeExists(string $theme): bool
    {
        return File::exists($this->themesPath . "/{$theme}");
    }

    /**
     * Get theme view path
     */
    public function getThemeViewPath(string $view = ''): string
    {
        $basePath = "themes.{$this->activeTheme}";
        return $view ? "{$basePath}.{$view}" : $basePath;
    }

    /**
     * Get theme asset path
     */
    public function getThemeAssetPath(string $asset = ''): string
    {
        $basePath = "/themes/{$this->activeTheme}";
        return $asset ? "{$basePath}/{$asset}" : $basePath;
    }

    /**
     * Render theme view
     */
    public function view(string $view, array $data = [])
    {
        $themeView = $this->getThemeViewPath($view);
        
        if (!view()->exists($themeView)) {
            // Fallback to default theme
            $fallbackView = "themes.default.{$view}";
            if (view()->exists($fallbackView)) {
                return view($fallbackView, $data);
            }
            
            throw new \Exception("View {$view} not found in theme {$this->activeTheme} or default theme");
        }

        return view($themeView, $data);
    }
}