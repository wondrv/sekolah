<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Template;
use App\Models\Section;
use App\Models\Block;
use App\Models\Widget;

class DefaultThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Site Settings
        Setting::set('site_name', 'SMA Negeri 1 Contoh');
        Setting::set('site_description', 'Website resmi SMA Negeri 1 Contoh - Membangun Generasi Cerdas dan Berkarakter');
        Setting::set('site_logo', '/images/logosekolah.png');
        Setting::set('site_favicon', '/favicon.ico');

        Setting::set('contact_info', [
            'address' => 'Jl. Pendidikan No. 123, Jakarta Pusat 10430',
            'phone' => '(021) 123-4567',
            'email' => 'info@sma1contoh.sch.id'
        ]);

        Setting::set('social_links', [
            'facebook' => 'https://facebook.com/sma1contoh',
            'instagram' => 'https://instagram.com/sma1contoh',
            'youtube' => 'https://youtube.com/@sma1contoh'
        ]);

        Setting::set('theme_colors', [
            'primary' => '#1e40af',
            'secondary' => '#64748b',
            'accent' => '#f59e0b',
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'error' => '#ef4444',
        ]);

        Setting::set('typography', [
            'font_family' => 'Inter, system-ui, sans-serif',
            'font_size_base' => '16px',
            'line_height_base' => '1.6',
            'font_weight_normal' => '400',
            'font_weight_semibold' => '600',
            'font_weight_bold' => '700',
        ]);

        // Create Primary Menu
        $primaryMenu = Menu::create([
            'name' => 'Primary Menu',
            'slug' => 'primary',
            'location' => 'primary',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $primaryMenuItems = [
            ['title' => 'Beranda', 'url' => '/', 'sort_order' => 1],
            ['title' => 'Profil', 'url' => '/profil', 'sort_order' => 2],
            ['title' => 'Berita', 'url' => '/berita', 'sort_order' => 3],
            ['title' => 'Agenda', 'url' => '/agenda', 'sort_order' => 4],
            ['title' => 'Galeri', 'url' => '/galeri', 'sort_order' => 5],
            ['title' => 'Kontak', 'url' => '/kontak', 'sort_order' => 6],
        ];

        foreach ($primaryMenuItems as $item) {
            MenuItem::create([
                'menu_id' => $primaryMenu->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'sort_order' => $item['sort_order'],
                'is_active' => true,
            ]);
        }

        // Create Footer Menu
        $footerMenu = Menu::create([
            'name' => 'Footer Menu',
            'slug' => 'footer',
            'location' => 'footer',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $footerMenuItems = [
            ['title' => 'Kebijakan Privasi', 'url' => '/profil/kebijakan-privasi', 'sort_order' => 1],
            ['title' => 'Syarat & Ketentuan', 'url' => '/profil/syarat-ketentuan', 'sort_order' => 2],
            ['title' => 'Sitemap', 'url' => '/sitemap', 'sort_order' => 3],
        ];

        foreach ($footerMenuItems as $item) {
            MenuItem::create([
                'menu_id' => $footerMenu->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'sort_order' => $item['sort_order'],
                'is_active' => true,
            ]);
        }

        // Create Homepage Template
        $template = Template::create([
            'name' => 'Default School Homepage',
            'slug' => 'homepage',
            'description' => 'Default homepage template for school websites',
            'active' => true,
        ]);

        // Hero Section
        $heroSection = Section::create([
            'template_id' => $template->id,
            'name' => 'Hero Section',
            'key' => 'hero',
            'order' => 1,
            'active' => true,
        ]);

        Block::create([
            'section_id' => $heroSection->id,
            'type' => 'hero',
            'data' => [
                'title' => 'Selamat Datang di SMA Negeri 1 Contoh',
                'subtitle' => 'Membangun Generasi Cerdas, Kreatif, dan Berkarakter untuk Masa Depan Indonesia',
                'background_color' => 'bg-gradient-to-r from-blue-600 to-blue-800',
                'text_align' => 'text-center',
                'buttons' => [
                    [
                        'text' => 'Tentang Kami',
                        'url' => '/profil',
                        'style' => 'primary'
                    ],
                    [
                        'text' => 'Penerimaan Siswa Baru',
                        'url' => '/profil/penerimaan-siswa-baru',
                        'style' => 'secondary'
                    ]
                ]
            ],
            'order' => 1,
            'active' => true,
        ]);

        // Stats Section
        $statsSection = Section::create([
            'template_id' => $template->id,
            'name' => 'Statistics',
            'key' => 'statistics',
            'order' => 2,
            'active' => true,
        ]);

        Block::create([
            'section_id' => $statsSection->id,
            'type' => 'stats',
            'data' => [
                'title' => 'Prestasi Kami',
                'background_color' => 'bg-blue-900',
                'stats' => [
                    [
                        'number' => '1200+',
                        'label' => 'Siswa Aktif',
                        'description' => 'Siswa yang terdaftar'
                    ],
                    [
                        'number' => '85+',
                        'label' => 'Tenaga Pendidik',
                        'description' => 'Guru berpengalaman'
                    ],
                    [
                        'number' => '50+',
                        'label' => 'Prestasi',
                        'description' => 'Tingkat nasional'
                    ],
                    [
                        'number' => '98%',
                        'label' => 'Kelulusan',
                        'description' => 'Tingkat kelulusan'
                    ]
                ]
            ],
            'order' => 1,
            'active' => true,
        ]);

        // Programs Section
        $programsSection = Section::create([
            'template_id' => $template->id,
            'name' => 'Programs',
            'key' => 'programs',
            'order' => 3,
            'active' => true,
        ]);

        Block::create([
            'section_id' => $programsSection->id,
            'type' => 'card_grid',
            'data' => [
                'title' => 'Program Unggulan',
                'subtitle' => 'Program-program unggulan yang kami tawarkan untuk mengembangkan potensi siswa',
                'background_color' => 'bg-gray-50',
                'columns' => 3,
                'cards' => [
                    [
                        'title' => 'Program IPA',
                        'description' => 'Program Ilmu Pengetahuan Alam dengan laboratorium lengkap dan modern',
                        'image' => '/images/program-ipa.jpg',
                        'link' => [
                            'text' => 'Selengkapnya',
                            'url' => '/profil/program-ipa'
                        ]
                    ],
                    [
                        'title' => 'Program IPS',
                        'description' => 'Program Ilmu Pengetahuan Sosial dengan kurikulum yang komprehensif',
                        'image' => '/images/program-ips.jpg',
                        'link' => [
                            'text' => 'Selengkapnya',
                            'url' => '/profil/program-ips'
                        ]
                    ],
                    [
                        'title' => 'Program Bahasa',
                        'description' => 'Program Bahasa dengan fokus pada penguasaan bahasa asing',
                        'image' => '/images/program-bahasa.jpg',
                        'link' => [
                            'text' => 'Selengkapnya',
                            'url' => '/profil/program-bahasa'
                        ]
                    ]
                ]
            ],
            'order' => 1,
            'active' => true,
        ]);

        // Events Teaser Section
        $eventsSection = Section::create([
            'template_id' => $template->id,
            'name' => 'Upcoming Events',
            'key' => 'events',
            'order' => 4,
            'active' => true,
        ]);

        Block::create([
            'section_id' => $eventsSection->id,
            'type' => 'events_teaser',
            'data' => [
                'title' => 'Agenda Mendatang',
                'background_color' => 'bg-white',
                'limit' => 3,
                'show_more_link' => true
            ],
            'order' => 1,
            'active' => true,
        ]);

        // CTA Section
        $ctaSection = Section::create([
            'template_id' => $template->id,
            'name' => 'Call to Action',
            'key' => 'cta',
            'order' => 5,
            'active' => true,
        ]);

        Block::create([
            'section_id' => $ctaSection->id,
            'type' => 'cta_banner',
            'data' => [
                'title' => 'Bergabunglah dengan Kami',
                'subtitle' => 'Daftarkan diri Anda dan menjadi bagian dari keluarga besar SMA Negeri 1 Contoh',
                'background_color' => 'bg-gradient-to-r from-blue-600 to-blue-800',
                'button' => [
                    'text' => 'Daftar Sekarang',
                    'url' => '/profil/penerimaan-siswa-baru'
                ]
            ],
            'order' => 1,
            'active' => true,
        ]);

        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@school.local',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'role' => 'admin',
        ]);

        echo "Default theme seeded successfully!\n";
    }
}
