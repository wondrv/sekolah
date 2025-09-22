<?php

if (!function_exists('setting')) {
    /**
     * Get or set a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('theme')) {
    /**
     * Get theme service instance
     *
     * @return \App\Services\ThemeService
     */
    function theme()
    {
        return app(\App\Services\ThemeService::class);
    }
}

if (!function_exists('theme_setting')) {
    /**
     * Get theme specific setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function theme_setting($key, $default = null)
    {
        $activeTheme = theme()->getActiveTheme();
        $themeConfig = theme()->getThemeConfig($activeTheme);
        
        return data_get($themeConfig, "settings.{$key}", $default);
    }
}

if (!function_exists('menu')) {
    /**
     * Get menu by location
     *
     * @param string $location
     * @return \App\Models\Menu|null
     */
    function menu($location)
    {
        return \App\Models\Menu::where('location', $location)->first();
    }
}
