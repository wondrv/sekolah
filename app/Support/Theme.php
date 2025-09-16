<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Widget;
use App\Models\Template;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Theme
{
    public static function getSetting($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function getSettings($keys = [])
    {
        if (empty($keys)) {
            return Cache::remember("settings.all", 3600, function () {
                return Setting::pluck('value', 'key')->toArray();
            });
        }

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = self::getSetting($key);
        }
        return $settings;
    }

    public static function getSiteInfo()
    {
        return Cache::remember('site.info', 3600, function () {
            $settings = Setting::pluck('value', 'key')->toArray();
            // Helper to normalize media paths to web URLs
            $normalize = function ($path, $default = null) {
                if (empty($path)) {
                    return $default;
                }
                // If already a full URL or a public path we can serve, return as-is
                if (Str::startsWith($path, ['http://', 'https://', '/storage/', '/images/', '/assets/'])) {
                    return $path;
                }
                // Otherwise, assume it's stored on the public disk
                return Storage::url($path);
            };

            return [
                'name' => $settings['site_name'] ?? 'SMK Teknologi Informatika',
                'tagline' => $settings['site_tagline'] ?? 'Mencetak Generasi Digital Unggul',
                'description' => $settings['site_description'] ?? 'Sekolah teknologi terdepan yang mempersiapkan siswa menghadapi era digital.',
                'keywords' => $settings['site_keywords'] ?? 'SMK, teknologi, informatika, komputer, digital',
                'logo' => $normalize($settings['logo'] ?? ($settings['site_logo'] ?? null), '/images/logo.png'),
                'favicon' => $normalize($settings['favicon'] ?? null, '/favicon.ico'),
                'hero_image' => $normalize($settings['hero_image'] ?? null, '/images/hero.jpg'),
                'email' => $settings['contact_email'] ?? 'info@sekolah.sch.id',
                'phone' => $settings['contact_phone'] ?? '(021) 123-4567',
                'address' => $settings['contact_address'] ?? 'Jl. Pendidikan No. 123, Jakarta Pusat 10430',
                'whatsapp' => $settings['contact_whatsapp'] ?? '+62812-3456-7890',
                // Structure for footer compatibility
                'contact' => [
                    'address' => $settings['contact_address'] ?? 'Jl. Pendidikan No. 123, Jakarta Pusat 10430',
                    'phone' => $settings['contact_phone'] ?? '(021) 123-4567',
                    'email' => $settings['contact_email'] ?? 'info@sekolah.sch.id',
                    'whatsapp' => $settings['contact_whatsapp'] ?? '+62812-3456-7890',
                ],
                'social' => [
                    'facebook' => $settings['facebook_url'] ?? '',
                    'instagram' => $settings['instagram_url'] ?? '',
                    'youtube' => $settings['youtube_url'] ?? '',
                    'twitter' => $settings['twitter_url'] ?? '',
                    'tiktok' => $settings['tiktok_url'] ?? '',
                    'linkedin' => $settings['linkedin_url'] ?? '',
                ],
            ];
        });
    }

    public static function getThemeColors()
    {
        return Cache::remember('theme.colors', 3600, function () {
            $settings = Setting::pluck('value', 'key')->toArray();
            return [
                'primary' => $settings['primary_color'] ?? '#3b82f6',
                'secondary' => $settings['secondary_color'] ?? '#64748b',
                'accent' => $settings['accent_color'] ?? '#f59e0b',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'error' => '#ef4444',
            ];
        });
    }

    public static function getTypography()
    {
        return Cache::remember('theme.typography', 3600, function () {
            $settings = Setting::pluck('value', 'key')->toArray();
            return [
                'font_family' => $settings['font_family'] ?? 'Inter',
                'font_size_base' => '16px',
                'line_height_base' => '1.6',
                'font_weight_normal' => '400',
                'font_weight_semibold' => '600',
                'font_weight_bold' => '700',
                'border_radius' => $settings['border_radius'] ?? '0.375rem',
            ];
        });
    }

    public static function clearCache()
    {
        $cacheKeys = ['site.info', 'theme.colors', 'theme.typography', 'settings.all', 'home.template'];
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        $locations = ['header', 'footer', 'primary', 'sidebar'];
        foreach ($locations as $location) {
            Cache::forget("menu.{$location}");
            Cache::forget("widgets.{$location}");
        }
    }

    public static function getMenu($location)
    {
        return Cache::remember("menu.{$location}", 3600, function () use ($location) {
            $menu = Menu::where('location', $location)->where('is_active', true)->first();
            if (!$menu) {
                return collect();
            }
            return MenuItem::with(['children'])->where('menu_id', $menu->id)->whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get();
        });
    }

    public static function getWidgets($location)
    {
        return Cache::remember("widgets.{$location}", 3600, function () use ($location) {
            return Widget::where('location', $location)->where('is_active', true)->orderBy('sort_order')->get();
        });
    }

    public static function getHomeTemplate()
    {
        return Cache::remember('home.template', 3600, function () {
            return Template::with(['sections.blocks'])->where('is_active', true)->where('type', 'homepage')->first();
        });
    }
}
