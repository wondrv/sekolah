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
            'site_description' => 'nullable|string',
            'site_keywords' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
            'font_family' => 'required|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);

        $settings = $request->except(['_token', '_method', 'logo', 'favicon']);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            // Delete old logo
            $oldLogo = Setting::where('key', 'logo')->first();
            if ($oldLogo && $oldLogo->value && Storage::disk('public')->exists($oldLogo->value)) {
                Storage::disk('public')->delete($oldLogo->value);
            }

            $logoPath = $request->file('logo')->store('images', 'public');
            $settings['logo'] = $logoPath;
        }

        if ($request->hasFile('favicon')) {
            // Delete old favicon
            $oldFavicon = Setting::where('key', 'favicon')->first();
            if ($oldFavicon && $oldFavicon->value && Storage::disk('public')->exists($oldFavicon->value)) {
                Storage::disk('public')->delete($oldFavicon->value);
            }

            $faviconPath = $request->file('favicon')->store('images', 'public');
            $settings['favicon'] = $faviconPath;
        }

        // Save all settings
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear theme cache
        Cache::forget('theme_data');
        Cache::forget('theme_colors');
        Cache::forget('site_settings');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
