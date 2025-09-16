<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Support\Theme;

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
            'site_description' => 'nullable|string',
            'site_keywords' => 'nullable|string',
            'site_tagline' => 'nullable|string|max:255',
            'school_phone' => 'nullable|string|max:20',
            'school_email' => 'nullable|email|max:255',
            'school_address' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'contact_whatsapp' => 'nullable|string|max:20',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'accent_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'font_family' => 'nullable|string|max:100',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string|max:20',
            // Header settings
            'header_logo_position' => 'nullable|in:left,center',
            'header_sticky' => 'nullable|boolean',
            'header_transparent' => 'nullable|boolean',
            'header_bg_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'header_text_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'social_show_in_header' => 'nullable|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = setting('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('uploads/logos', 'public');
            Setting::set('site_logo', $logoPath);
        }

        // Handle logo upload (alternative field name)
        if ($request->hasFile('logo')) {
            $oldLogo = setting('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('logo')->store('uploads/logos', 'public');
            Setting::set('logo', $logoPath);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $oldFavicon = setting('favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('favicon')->store('uploads/favicons', 'public');
            Setting::set('favicon', $faviconPath);
        }

        // Update all other settings
        $settingsToUpdate = [
            'site_name',
            'site_description',
            'site_keywords',
            'site_tagline',
            'school_phone',
            'school_email',
            'school_address',
            'contact_email',
            'contact_phone',
            'contact_address',
            'contact_whatsapp',
            'primary_color',
            'secondary_color',
            'accent_color',
            'font_family',
            'meta_keywords',
            'meta_description',
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url',
            'tiktok_url',
            'linkedin_url',
            'whatsapp_number',
            // Header settings (non-boolean)
            'header_logo_position',
            'header_bg_color',
            'header_text_color',
        ];

        foreach ($settingsToUpdate as $setting) {
            if ($request->has($setting)) {
                Setting::set($setting, $request->input($setting));
            }
        }

        // Handle boolean toggles explicitly to allow turning off
        $booleanSettings = ['header_sticky', 'header_transparent', 'social_show_in_header'];
        foreach ($booleanSettings as $boolKey) {
            Setting::set($boolKey, $request->boolean($boolKey) ? '1' : '0');
        }

        // Clear all theme-related caches
        Theme::clearCache();

        return redirect()->route('admin.settings.index')
                        ->with('success', 'Settings updated successfully!');
    }
}
