<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_keywords' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'header_logo_position' => 'in:left,center',
            'header_sticky' => 'boolean',
            'header_transparent' => 'boolean',
            'header_bg_color' => 'required|string',
            'header_text_color' => 'required|string',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
            'font_family' => 'required|string',
            'border_radius' => 'required|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'contact_whatsapp' => 'nullable|string',
            'footer_copyright' => 'nullable|string',
            'footer_bg_color' => 'required|string',
            'footer_show_map' => 'boolean',
            'google_maps_embed' => 'nullable|url',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_tiktok' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_show_in_header' => 'boolean',
            'social_show_in_footer' => 'boolean',
        ]);

        $settings = $request->except(['_token', '_method', 'logo', 'favicon', 'hero_image']);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('images/branding', 'public');
            $settings['logo'] = 'storage/' . $logoPath;
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('images/branding', 'public');
            $settings['favicon'] = 'storage/' . $faviconPath;
        }

        if ($request->hasFile('hero_image')) {
            $heroPath = $request->file('hero_image')->store('images/hero', 'public');
            $settings['hero_image'] = 'storage/' . $heroPath;
        }

        // Handle boolean fields
        $booleanFields = [
            'header_sticky', 'header_transparent', 'footer_show_map',
            'social_show_in_header', 'social_show_in_footer'
        ];

        foreach ($booleanFields as $field) {
            $settings[$field] = $request->boolean($field);
        }

        // Save settings to database
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Clear theme cache
        Cache::forget('theme_settings');
        Cache::forget('site_info');
        Cache::forget('theme_colors');
        Cache::forget('typography');

        // Use Theme class cache clearing method
        \App\Support\Theme::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan! Website akan menampilkan perubahan yang Anda buat.');
    }
}
