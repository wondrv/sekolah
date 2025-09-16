<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;
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
            'primary_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
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
            // PPDB brochure settings
            'ppdb_brochure' => 'nullable|file|mimes:pdf|max:5120',
            'ppdb_brochure_url' => 'nullable|url',
            // Header settings
            'header_logo_position' => 'nullable|in:left,center',
            'header_sticky' => 'nullable|boolean',
            'header_transparent' => 'nullable|boolean',
            'header_bg_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'header_text_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'social_show_in_header' => 'nullable|boolean',
            // Agenda settings
            'agenda_show_on_home' => 'nullable|boolean',
            'agenda_items_home' => 'nullable|integer|min:1|max:12',
            'agenda_section_title' => 'nullable|string|max:100',
            // News settings
            'news_show_on_home' => 'nullable|boolean',
            'news_items_home' => 'nullable|integer|min:1|max:12',
            'news_section_title' => 'nullable|string|max:100',
            // Announcements settings
            'announcements_show_on_home' => 'nullable|boolean',
            'announcements_items_home' => 'nullable|integer|min:1|max:12',
            'announcements_section_title' => 'nullable|string|max:100',
            'announcements_category_slug' => 'nullable|string|max:100',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = function_exists('setting') ? setting('site_logo') : Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('uploads/logos', 'public');
            Setting::set('site_logo', $logoPath);
        }

        // Handle logo upload (alternative field name)
        if ($request->hasFile('logo')) {
            $oldLogo = function_exists('setting') ? setting('logo') : Setting::get('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('logo')->store('uploads/logos', 'public');
            Setting::set('logo', $logoPath);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $oldFavicon = function_exists('setting') ? setting('favicon') : Setting::get('favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('favicon')->store('uploads/favicons', 'public');
            Setting::set('favicon', $faviconPath);
        }

        // Handle PPDB brochure upload (PDF)
        if ($request->hasFile('ppdb_brochure')) {
            $old = function_exists('setting') ? setting('ppdb_brochure') : Setting::get('ppdb_brochure');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('ppdb_brochure')->store('uploads/ppdb', 'public');
            Setting::set('ppdb_brochure', $path);
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
            // PPDB brochure URL (if using external link)
            'ppdb_brochure_url',
            // Agenda settings (non-boolean)
            'agenda_items_home',
            'agenda_section_title',
            // Header settings (non-boolean)
            'header_logo_position',
            'header_bg_color',
            'header_text_color',
            // News settings (non-boolean)
            'news_items_home',
            'news_section_title',
            // Announcements settings (non-boolean)
            'announcements_items_home',
            'announcements_section_title',
            'announcements_category_slug',
        ];

        foreach ($settingsToUpdate as $setting) {
            if ($request->has($setting)) {
                Setting::set($setting, $request->input($setting));
            }
        }

        // Handle boolean toggles explicitly to allow turning off
    $booleanSettings = ['header_sticky', 'header_transparent', 'social_show_in_header', 'agenda_show_on_home', 'news_show_on_home', 'announcements_show_on_home'];
        foreach ($booleanSettings as $boolKey) {
            Setting::set($boolKey, $request->boolean($boolKey) ? '1' : '0');
        }

        // Clear all theme-related caches
        Theme::clearCache();

        return redirect()->route('admin.settings.index')
                        ->with('success', 'Settings updated successfully!');
    }

    /**
     * Ensure the announcements category exists (create if missing)
     */
    public function ensureAnnouncementsCategory(Request $request)
    {
        $slug = trim($request->input('slug') ?: Setting::get('announcements_category_slug', 'pengumuman'));
        if ($slug === '') {
            $slug = 'pengumuman';
        }

        $name = ucwords(str_replace(['-', '_'], ' ', $slug));
        $category = Category::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => 'Kategori Pengumuman']
        );

        return redirect()->route('admin.settings.index')
            ->with('success', "Kategori '{$category->name}' (slug: {$category->slug}) telah dipastikan ada.");
    }

    /**
     * Ensure PPDB pages exist with basic content
     */
        public function ensurePPDBPages(Request $request)
        {
                $body = <<<HTML
<div class="space-y-16">
    <section id="brosur">
        <h2 class="text-2xl font-bold mb-4">Brosur PPDB</h2>
        <p>Unduh brosur PPDB terbaru kami. Informasi program, fasilitas, dan keunggulan sekolah tersaji lengkap.</p>
        <ul class="list-disc pl-6 mt-3">
            <li><a href="#" class="text-blue-600 hover:underline">Brosur PPDB (PDF)</a></li>
        </ul>
    </section>
    <section id="biaya">
        <h2 class="text-2xl font-bold mb-4">Biaya Pendaftaran</h2>
        <p>Berikut adalah rincian biaya pendaftaran PPDB. Silakan hubungi pihak sekolah untuk informasi terbaru.</p>
        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Komponen</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr><td class="px-4 py-2">Formulir Pendaftaran</td><td class="px-4 py-2">Rp 100.000</td></tr>
                <tr><td class="px-4 py-2">Uang Pangkal</td><td class="px-4 py-2">Rp 2.000.000</td></tr>
                <tr><td class="px-4 py-2">Seragam</td><td class="px-4 py-2">Rp 1.000.000</td></tr>
            </tbody>
        </table>
    </section>
</div>
HTML;

                Page::updateOrCreate(
                        ['slug' => 'ppdb'],
                        ['title' => 'PPDB', 'body' => $body]
                );

                return redirect()->route('admin.settings.index')->with('success', 'Halaman PPDB gabungan (brosur dan biaya) sudah dipastikan ada.');
        }

    /**
     * Ensure PPDB menu structure exists under header menu
     */
    public function ensurePPDBMenu(Request $request)
    {
        $menu = Menu::firstOrCreate(['location' => 'header'], [
            'name' => 'Header Menu',
            'slug' => 'header',
            'label' => 'Header',
            'location' => 'header',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Single PPDB menu item pointing to combined page
        MenuItem::updateOrCreate([
            'menu_id' => $menu->id,
            'parent_id' => null,
            'title' => 'PPDB',
        ], [
            'url' => route('ppdb'),
            'sort_order' => 50,
            'is_active' => true,
        ]);

        Theme::clearCache();

        return redirect()->route('admin.settings.index')->with('success', 'Menu PPDB telah dipastikan ada di header.');
    }

    /**
     * Ensure "Tentang Kita" dropdown exists with sample submenus (can be edited later)
     */
    public function ensureProfileMenuDropdown(Request $request)
    {
        $menu = Menu::firstOrCreate(['location' => 'header'], [
            'name' => 'Header Menu',
            'slug' => 'header',
            'label' => 'Header',
            'location' => 'header',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Ensure pages exist
        $pages = [
            ['title' => 'Sejarah', 'slug' => 'tentang-kita-sejarah', 'body' => '<p>Sejarah singkat sekolah.</p>'],
            ['title' => 'Visi Misi', 'slug' => 'tentang-kita-visi-misi', 'body' => '<p>Visi dan misi sekolah.</p>'],
            ['title' => 'Struktur Organisasi', 'slug' => 'tentang-kita-struktur-organisasi', 'body' => '<p>Struktur organisasi sekolah.</p>'],
        ];
        foreach ($pages as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], ['title' => $p['title'], 'body' => $p['body']]);
        }

        // Parent Tentang Kita item
        $profil = MenuItem::firstOrCreate([
            'menu_id' => $menu->id,
            'parent_id' => null,
            'title' => 'Tentang Kita',
        ], [
            'url' => '#',
            'sort_order' => 20,
            'is_active' => true,
        ]);

        // Children submenu items
        $subs = [
            ['title' => 'Sejarah', 'slug' => 'tentang-kita-sejarah', 'order' => 1],
            ['title' => 'Visi Misi', 'slug' => 'tentang-kita-visi-misi', 'order' => 2],
            ['title' => 'Struktur Organisasi', 'slug' => 'tentang-kita-struktur-organisasi', 'order' => 3],
        ];
        foreach ($subs as $s) {
            MenuItem::updateOrCreate([
                'menu_id' => $menu->id,
                'parent_id' => $profil->id,
                'title' => $s['title'],
            ], [
                'url' => route('pages.show', ['slug' => $s['slug']]),
                'sort_order' => $s['order'],
                'is_active' => true,
            ]);
        }

        Theme::clearCache();

        return redirect()->route('admin.settings.index')->with('success', 'Dropdown Tentang Kita beserta submenu contoh telah dibuat.');
    }

    /**
     * Add Agenda page as a child under Tentang Kita dropdown in header menu
     */
    public function addAgendaUnderProfile(Request $request)
    {
        $menu = Menu::firstOrCreate(['location' => 'header'], [
            'name' => 'Header Menu',
            'slug' => 'header',
            'label' => 'Header',
            'location' => 'header',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Find Tentang Kita parent
        $profil = MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->where('title', 'Tentang Kita')
            ->first();

        if (!$profil) {
            // If Tentang Kita absent, create it
            $profil = MenuItem::create([
                'menu_id' => $menu->id,
                'parent_id' => null,
                'title' => 'Tentang Kita',
                'url' => '#',
                'sort_order' => 20,
                'is_active' => true,
            ]);
        }

        // Try to find existing top-level 'Agenda' to MOVE under Profil
        $eventsUrl = route('events.index');
        $absoluteAgenda = url('/agenda');
        $existingTop = MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->where(function ($q) use ($eventsUrl, $absoluteAgenda) {
                $q->where('title', 'Agenda')
                  ->orWhere('url', $eventsUrl)
                  ->orWhere('url', '/agenda')
                  ->orWhere('url', $absoluteAgenda);
            })
            ->first();

        $existingChild = MenuItem::where('menu_id', $menu->id)
            ->where('parent_id', $profil->id)
            ->where(function ($q) use ($eventsUrl, $absoluteAgenda) {
                $q->where('title', 'Agenda')
                  ->orWhere('url', $eventsUrl)
                  ->orWhere('url', '/agenda')
                  ->orWhere('url', $absoluteAgenda);
            })
            ->first();

        if ($existingTop) {
            // If a child already exists and it's different, remove duplicate child
            if ($existingChild && $existingChild->id !== $existingTop->id) {
                $existingChild->delete();
            }
            // Move top-level into Profil
            $existingTop->parent_id = $profil->id;
            $existingTop->url = $eventsUrl; // normalize URL
            $existingTop->sort_order = $existingTop->sort_order ?: 4;
            $existingTop->is_active = true;
            $existingTop->save();
        } else {
            // Ensure a child exists
            MenuItem::updateOrCreate([
                'menu_id' => $menu->id,
                'parent_id' => $profil->id,
                'title' => 'Agenda',
            ], [
                'url' => $eventsUrl,
                'sort_order' => 4,
                'is_active' => true,
            ]);
        }

        Theme::clearCache();

        return redirect()->route('admin.settings.index')->with('success', 'Agenda telah ditambahkan ke submenu Tentang Kita.');
    }
}
