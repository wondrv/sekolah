<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\Menu;
use App\Models\Widget;
use App\Models\Template;
use Illuminate\Support\Facades\Cache;

class Theme
{
    public static function getSetting($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            return Setting::get($key, $default);
        });
    }

    public static function getMenu($location)
    {
        return Cache::remember("menu.{$location}", 3600, function () use ($location) {
            return Menu::with(['children'])
                ->where('location', $location)
                ->where('active', true)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();
        });
    }

    public static function getWidgets($location)
    {
        return Cache::remember("widgets.{$location}", 3600, function () use ($location) {
            return Widget::where('location', $location)
                ->where('active', true)
                ->orderBy('order')
                ->get();
        });
    }

    public static function getHomeTemplate()
    {
        return Cache::remember('home.template', 3600, function () {
            return Template::with(['sections.blocks'])
                ->where('active', true)
                ->where('slug', 'homepage')
                ->first();
        });
    }

    public static function getSiteInfo()
    {
        return [
            'name' => self::getSetting('site_name', 'Nama Sekolah'),
            'logo' => self::getSetting('site_logo', '/images/logosekolah.png'),
            'favicon' => self::getSetting('site_favicon', '/favicon.ico'),
            'description' => self::getSetting('site_description', 'Profil resmi sekolah'),
            'contact' => self::getSetting('contact_info', [
                'address' => 'Jl. Pendidikan No. 123, Jakarta Pusat 10430',
                'phone' => '(021) 123-4567',
                'email' => 'info@namasekolah.sch.id'
            ]),
            'social' => self::getSetting('social_links', [
                'facebook' => '#',
                'instagram' => '#',
                'youtube' => '#'
            ]),
        ];
    }

    public static function getThemeColors()
    {
        return self::getSetting('theme_colors', [
            'primary' => '#1e40af',
            'secondary' => '#64748b',
            'accent' => '#f59e0b',
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'error' => '#ef4444',
        ]);
    }

    public static function getTypography()
    {
        return self::getSetting('typography', [
            'font_family' => 'Inter, system-ui, sans-serif',
            'font_size_base' => '16px',
            'line_height_base' => '1.6',
            'font_weight_normal' => '400',
            'font_weight_semibold' => '600',
            'font_weight_bold' => '700',
        ]);
    }

    public static function clearCache()
    {
        Cache::flush();
    }
}
