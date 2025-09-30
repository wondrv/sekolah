<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;

class MadrasahTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get pesantren category
        $pesantrenCategory = TemplateCategory::where('slug', 'pesantren')->first();
        if (!$pesantrenCategory) {
            $pesantrenCategory = TemplateCategory::create([
                'name' => 'Pesantren',
                'slug' => 'pesantren',
                'description' => 'Template khusus untuk pondok pesantren dan sekolah islam',
                'color' => '#059669',
                'icon' => 'mosque-icon',
                'sort_order' => 4,
            ]);
        }

        // Create SMA Madrasah Aliyah Template
        $template = [
            'name' => 'SMA Madrasah Aliyah Modern',
            'slug' => 'sma-madrasah-aliyah-modern',
            'description' => 'Template modern untuk SMA Madrasah Aliyah dengan nuansa islami yang elegan, dilengkapi dengan sistem informasi akademik, portal santri, dan fitur keagamaan terintegrasi',
            'category_id' => $pesantrenCategory->id,
            'preview_image' => 'templates/previews/sma-madrasah-aliyah.jpg',
            'template_data' => $this->getSMAMadrasahAliyahTemplate(),
            'author' => 'School CMS Islamic Team',
            'version' => '1.0.0',
            'features' => [
                'Portal Santri Terintegrasi',
                'Jadwal Sholat Otomatis',
                'Sistem Hafalan Al-Quran',
                'Kajian Online',
                'Islamic Calendar',
                'Prestasi Tahfidz',
                'Program Keagamaan',
                'Alumni Network Islami'
            ],
            'color_schemes' => [
                'default' => ['primary' => '#059669', 'secondary' => '#047857', 'accent' => '#D97706'],
                'classic' => ['primary' => '#065F46', 'secondary' => '#047857', 'accent' => '#92400E'],
                'modern' => ['primary' => '#10B981', 'secondary' => '#059669', 'accent' => '#F59E0B'],
            ],
            'featured' => true,
            'premium' => false,
            'rating' => 4.9,
            'downloads' => 0,
        ];

        TemplateGallery::updateOrCreate(
            ['slug' => $template['slug']],
            $template
        );

        $this->command->info('Created SMA Madrasah Aliyah template successfully!');
    }

    /**
     * SMA Madrasah Aliyah Modern Template Structure
     */
    private function getSMAMadrasahAliyahTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMA Madrasah Aliyah Modern',
                    'slug' => 'homepage',
                    'description' => 'Halaman utama SMA Madrasah Aliyah dengan nuansa islami modern',
                    'type' => 'page',
                    'active' => true,
                    'is_global' => false,
                    'sort_order' => 0,
                    'layout_settings' => [
                        'container_width' => 'full',
                        'sidebar' => false,
                        'header_style' => 'islamic',
                        'footer_style' => 'madrasah',
                    ],
                    'sections' => [
                        // Islamic Hero Section
                        [
                            'name' => 'Islamic Hero Section',
                            'order' => 0,
                            'settings' => [
                                'background' => 'islamic-gradient',
                                'animation' => 'bismillah-calligraphy',
                                'overlay' => 'green-islamic'
                            ],
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Islamic Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Madrasah Aliyah Modern',
                                        'subtitle' => 'Membangun Generasi Qur\'ani yang Berakhlak Mulia dan Berprestasi. Menggabungkan pendidikan agama yang kuat dengan kurikulum nasional yang berkualitas untuk menghasilkan lulusan yang siap menghadapi tantangan masa depan',
                                        'button_text' => 'Pelajari Program Kami',
                                        'button_url' => '/program-studi',
                                        'secondary_button_text' => 'Virtual Tour Madrasah',
                                        'secondary_button_url' => '/virtual-tour',
                                        'background_image' => 'hero/madrasah-aliyah-hero.jpg',
                                        'islamic_greeting' => 'بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم',
                                        'badges' => ['Akreditasi A', 'ISO 9001', 'Madrasah Unggulan'],
                                    ],
                                    'settings' => [
                                        'text_color' => 'white',
                                        'overlay' => true,
                                        'animation' => 'fade-in',
                                        'has_badges' => true,
                                        'hero_style' => 'islamic',
                                        'show_islamic_greeting' => true
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],

                        // Program Unggulan
                        [
                            'name' => 'Program Unggulan Madrasah',
                            'order' => 1,
                            'settings' => [
                                'background' => 'light-cream',
                                'padding' => 'large'
                            ],
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Islamic Programs',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Program Unggulan Madrasah',
                                        'subtitle' => 'Kurikulum Terintegrasi Agama dan Sains untuk Generasi Rabbani',
                                        'cards' => [
                                            [
                                                'title' => 'Tahfidz Al-Quran',
                                                'description' => 'Program hafalan Al-Quran dengan metode modern dan sistem tracking digital untuk memantau progress hafalan setiap santri',
                                                'image' => 'programs/tahfidz-quran.jpg',
                                                'icon' => 'quran',
                                                'url' => '/program/tahfidz',
                                                'badge' => '30 JUZ',
                                            ],
                                            [
                                                'title' => 'Bahasa Arab Intensif',
                                                'description' => 'Pembelajaran bahasa Arab dengan metode conversation dan pendalaman kitab kuning untuk persiapan studi lanjut',
                                                'image' => 'programs/arabic-intensive.jpg',
                                                'icon' => 'book-arabic',
                                                'url' => '/program/bahasa-arab',
                                                'badge' => 'INTENSIVE',
                                            ],
                                            [
                                                'title' => 'Sains Islami',
                                                'description' => 'Integrasi sains modern dengan perspektif Islam untuk memahami kebesaran Allah melalui ilmu pengetahuan',
                                                'image' => 'programs/islamic-science.jpg',
                                                'icon' => 'microscope',
                                                'url' => '/program/sains-islami',
                                                'badge' => 'INTEGRATED',
                                            ],
                                            [
                                                'title' => 'Leadership Islami',
                                                'description' => 'Pembentukan karakter kepemimpinan dengan nilai-nilai Islam untuk mencetak pemimpin masa depan yang amanah',
                                                'image' => 'programs/islamic-leadership.jpg',
                                                'icon' => 'crown',
                                                'url' => '/program/leadership',
                                                'badge' => 'LEADER',
                                            ],
                                        ],
                                    ],
                                    'settings' => [
                                        'columns' => 3,
                                        'card_style' => 'islamic',
                                        'hover_effect' => 'glow',
                                        'show_badges' => true,
                                        'badge_style' => 'green-islamic'
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],

                        // Prestasi & Alumni
                        [
                            'name' => 'Prestasi & Alumni',
                            'order' => 2,
                            'settings' => [
                                'background' => 'islamic-pattern-light'
                            ],
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'Islamic Achievement Stats',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Prestasi & Pencapaian Madrasah',
                                        'subtitle' => 'Dengan izin Allah, madrasah kami terus menghasilkan prestasi terbaik',
                                        'stats' => [
                                            [
                                                'number' => 850,
                                                'label' => 'Santri Aktif',
                                                'description' => 'Putra dan Putri',
                                                'icon' => 'users',
                                                'color' => 'green',
                                            ],
                                            [
                                                'number' => 45,
                                                'label' => 'Ustadz Berkualitas',
                                                'description' => 'Lulusan pesantren ternama',
                                                'icon' => 'user-tie',
                                                'color' => 'blue',
                                            ],
                                            [
                                                'number' => 150,
                                                'label' => 'Hafidz Al-Quran',
                                                'description' => 'Alumni penghafal 30 Juz',
                                                'icon' => 'book',
                                                'color' => 'purple',
                                            ],
                                            [
                                                'number' => 95,
                                                'label' => 'Tingkat Kelulusan',
                                                'description' => 'Persentase ke PTN/PTS',
                                                'icon' => 'graduation-cap',
                                                'color' => 'yellow',
                                                'suffix' => '%',
                                            ],
                                        ],
                                    ],
                                    'settings' => [
                                        'text_color' => 'dark',
                                        'animation' => 'counter',
                                        'layout' => 'islamic',
                                        'show_arabic' => true
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],

                        // CTA PPDB
                        [
                            'name' => 'Pendaftaran Santri Baru',
                            'order' => 3,
                            'settings' => [
                                'background' => 'islamic-cta-gradient'
                            ],
                            'blocks' => [
                                [
                                    'type' => 'cta-banner',
                                    'name' => 'Islamic Enrollment CTA',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Bergabunglah dengan Keluarga Besar Madrasah',
                                        'description' => 'Wujudkan cita-cita menjadi generasi Qur\'ani yang berakhlak mulia dan berprestasi. Daftarkan putra-putri Anda di madrasah kami dan rasakan pendidikan Islam yang berkualitas.',
                                        'button_text' => 'Daftar Santri Baru',
                                        'button_url' => '/ppdb',
                                        'secondary_button_text' => 'Download Brosur',
                                        'secondary_button_url' => '/brosur-ppdb',
                                        'background_image' => 'cta/madrasah-ppdb.jpg',
                                    ],
                                    'settings' => [
                                        'style' => 'islamic',
                                        'text_align' => 'center',
                                        'button_style' => 'islamic-rounded',
                                        'show_quote' => true
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                    'assignments' => [
                        [
                            'route_pattern' => 'home',
                            'priority' => 10,
                            'active' => true,
                        ],
                    ],
                ],
            ],
        ];
    }
}
