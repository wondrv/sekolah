<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\User;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class SetupCMS extends Command
{
    protected $signature = 'cms:setup';
    protected $description = 'Setup CMS with default settings and admin user';

    public function handle()
    {
        $this->info('Setting up CMS...');

        // Create admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@school.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $this->info('✅ Admin user created: admin@school.local / password');

        // Site Information
        $settings = [
            'site_name' => 'SMK Teknologi Digital',
            'site_description' => 'Sekolah Menengah Kejuruan yang mengutamakan pendidikan teknologi digital dan persiapan karir di era industri 4.0',
            'site_tagline' => 'Membangun Generasi Digital Indonesia',
            'contact_email' => 'info@smkteknologi.id',
            'contact_phone' => '+62 21 1234 5678',
            'contact_address' => 'Jl. Teknologi No. 123, Jakarta Selatan 12345',

            // Theme Colors
            'color_primary' => '#2563eb',
            'color_secondary' => '#64748b',
            'color_accent' => '#f59e0b',
            'color_success' => '#10b981',
            'color_warning' => '#f59e0b',
            'color_danger' => '#ef4444',

            // Typography
            'font_primary' => 'Inter, system-ui, sans-serif',
            'font_secondary' => 'Inter, system-ui, sans-serif',
            'font_size_base' => '16px',

            // Header Settings
            'header_position' => 'top',
            'show_social_header' => true,

            // Footer Settings
            'footer_text' => '© 2024 SMK Teknologi Digital. Semua hak cipta dilindungi.',
            'footer_description' => 'Sekolah Menengah Kejuruan terdepan dalam pendidikan teknologi digital',
            'show_social_footer' => true,

            // Social Media
            'social_facebook' => 'https://facebook.com/smkteknologi',
            'social_instagram' => 'https://instagram.com/smkteknologi',
            'social_youtube' => 'https://youtube.com/@smkteknologi',
            'social_linkedin' => 'https://linkedin.com/company/smkteknologi',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        $this->info('✅ Default settings created');

        // Create header menu (reference-style IA)
        $headerMenu = Menu::updateOrCreate(
            ['name' => 'header'],
            [
                'slug' => 'header',
                'location' => 'header',
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        // Clear existing items for fresh setup
        MenuItem::where('menu_id', $headerMenu->id)->delete();

        // Top-level items
        $home = MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Beranda', 'url' => '/', 'sort_order' => 1, 'is_active' => true]);
    $profil = MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Tentang Kita', 'url' => '/tentang-kami', 'sort_order' => 2, 'is_active' => true]);
        $manajemen = MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Manajemen', 'url' => '#', 'sort_order' => 3, 'is_active' => true]);
        $layanan = MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Layanan', 'url' => '#', 'sort_order' => 4, 'is_active' => true]);
        $resource = MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Resource', 'url' => '#', 'sort_order' => 5, 'is_active' => true]);
        $acara = MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Acara', 'url' => '/agenda', 'sort_order' => 6, 'is_active' => true]);
        $kontak = MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Kontak', 'url' => '/kontak', 'sort_order' => 7, 'is_active' => true]);

        // Manajemen children
        foreach ([
            ['Kurikulum', '/kurikulum', 1],
            ['Kesiswaan', '/kesiswaan', 2],
            ['Ismuba', '/ismuba', 3],
            ['Humas', '/humas', 4],
            ['Sarpras', '/sarpras', 5],
            ['Human Resource', '/human-resource', 6],
        ] as [$title, $url, $order]) {
            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'parent_id' => $manajemen->id,
                'title' => $title,
                'url' => $url,
                'sort_order' => $order,
                'is_active' => true,
            ]);
        }

        // Layanan children
        foreach ([
            ['PPDB', 'https://smam1ta.sch.id/ppdb-sekolah/', 1, '_blank'],
            ['Rapor', '/rapor', 2, '_self'],
            ['SKL', '/skl', 3, '_self'],
            ['Berita', '/berita', 4, '_self'],
            ['Pengumuman', '/berita?kategori=pengumuman', 5, '_self'],
        ] as [$title, $url, $order, $target]) {
            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'parent_id' => $layanan->id,
                'title' => $title,
                'url' => $url,
                'target' => $target,
                'sort_order' => $order,
                'is_active' => true,
            ]);
        }

        // Resource children
        foreach ([
            ['Unduh', '/unduh', 1],
            ['Pusat Bantuan', '/pusat-bantuan', 2],
        ] as [$title, $url, $order]) {
            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'parent_id' => $resource->id,
                'title' => $title,
                'url' => $url,
                'sort_order' => $order,
                'is_active' => true,
            ]);
        }

        $this->info('✅ Header navigation seeded (reference-style)');

        // Clear theme cache
        Cache::forget('theme_settings');
        Cache::forget('theme_colors');
        Cache::forget('theme_typography');
        Cache::forget('site_info');
        $this->info('✅ Cache cleared');

        $this->info('🎉 CMS setup complete!');
        $this->info('');
        $this->info('Admin Login: admin@school.local');
        $this->info('Password: password');
        $this->info('');
        $this->info('Visit: http://127.0.0.1:8000/test-cms to verify setup');
        $this->info('Admin Panel: http://127.0.0.1:8000/admin/settings');

        return 0;
    }
}
