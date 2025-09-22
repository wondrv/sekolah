<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    public function index(): View
    {
        $colors = ThemeSetting::getByCategory('colors');
        $typography = ThemeSetting::getByCategory('typography');
        $spacing = ThemeSetting::getByCategory('spacing');
        $layout = ThemeSetting::getByCategory('layout');

        return view('admin.theme.index', compact('colors', 'typography', 'spacing', 'layout'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'colors' => 'array',
            'typography' => 'array',
            'spacing' => 'array',
            'layout' => 'array',
        ]);

        foreach ($validated as $category => $settings) {
            foreach ($settings as $key => $value) {
                ThemeSetting::set($key, $value, $category);
            }
        }

        // Clear theme cache
        Cache::forget('theme_styles');

        return redirect()->route('admin.theme.index')
            ->with('success', 'Theme settings updated successfully.');
    }

    public function reset(Request $request): RedirectResponse
    {
        $category = $request->get('category');

        if ($category) {
            ThemeSetting::where('category', $category)->delete();
            $message = ucfirst($category) . ' settings reset to defaults.';
        } else {
            ThemeSetting::truncate();
            $message = 'All theme settings reset to defaults.';
        }

        // Clear theme cache
        Cache::forget('theme_styles');

        return redirect()->route('admin.theme.index')
            ->with('success', $message);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $settings = ThemeSetting::all()->groupBy('category');

        $export = [];
        foreach ($settings as $category => $categorySettings) {
            $export[$category] = $categorySettings->pluck('value', 'key')->toArray();
        }

        $filename = 'theme-settings-' . date('Y-m-d-H-i-s') . '.json';

        return response()->streamDownload(function () use ($export) {
            echo json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimetypes:application/json,text/plain',
        ]);

        $data = json_decode(file_get_contents($request->file('file')->getRealPath()), true);

        if (!is_array($data)) {
            return back()->with('error', 'Invalid JSON file format.');
        }

        foreach ($data as $category => $settings) {
            if (is_array($settings)) {
                foreach ($settings as $key => $value) {
                    ThemeSetting::set($key, $value, $category);
                }
            }
        }

        // Clear theme cache
        Cache::forget('theme_styles');

        return redirect()->route('admin.theme.index')
            ->with('success', 'Theme settings imported successfully.');
    }
}