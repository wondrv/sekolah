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
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'school_phone' => 'nullable|string|max:20',
            'school_email' => 'nullable|email|max:255',
            'school_address' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            $oldLogo = setting('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('site_logo')->store('uploads/logos', 'public');
            Setting::set('site_logo', $logoPath);
        }

        // Update all other settings
        $settingsToUpdate = [
            'site_name',
            'site_description',
            'school_phone',
            'school_email',
            'school_address',
            'primary_color',
            'secondary_color',
            'meta_keywords',
            'meta_description',
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url',
            'contact_email',
            'whatsapp_number',
        ];

        foreach ($settingsToUpdate as $setting) {
            if ($request->has($setting)) {
                Setting::set($setting, $request->input($setting));
            }
        }

        // Clear settings cache
        Cache::forget('settings');

        return redirect()->route('admin.settings.index')
                        ->with('success', 'Settings updated successfully!');
    }
}
