<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;

class SMAMadrasahTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create categories
        $pesantrenCategory = TemplateCategory::firstOrCreate([
            'slug' => 'pesantren'
        ], [
            'name' => 'Pesantren',
            'description' => 'Template khusus untuk pondok pesantren dan sekolah islam',
            'color' => '#059669',
            'icon' => 'mosque-icon',
            'sort_order' => 4,
        ]);

        $smpSmaCategory = TemplateCategory::where('slug', 'sekolah-menengah')->first();
        if (!$smpSmaCategory) {
            $smpSmaCategory = TemplateCategory::create([
                'name' => 'Sekolah Menengah',
                'slug' => 'sekolah-menengah',
                'description' => 'Template untuk SMP dan SMA',
                'color' => '#3B82F6',
                'icon' => 'graduation-icon',
                'sort_order' => 2,
            ]);
        }

        // Create 3 templates inspired by SMA Madrasah Aliyah
        $templates = [
            // Template 1: SMA Madrasah Aliyah Classic
            [
                'name' => 'SMA Madrasah Aliyah Classic',
                'slug' => 'sma-madrasah-classic',
                'description' => 'Template klasik untuk SMA Madrasah Aliyah dengan desain elegan dan nuansa islami tradisional, dilengkapi fitur akademik dan keagamaan yang lengkap',
                'category_id' => $pesantrenCategory->id,
                'preview_image' => 'templates/previews/sma-madrasah-classic.jpg',
                'template_data' => $this->getClassicMadrasahTemplate(),
                'author' => 'School CMS Islamic Team',
                'version' => '1.0.0',
                'features' => [
                    'Islamic Heritage Design',
                    'Jadwal Sholat Terintegrasi',
                    'Portal Santri',
                    'Sistem Tahfidz',
                    'Kajian Kitab Kuning',
                    'Islamic Calendar',
                    'Prestasi Akademik',
                    'Gallery Kegiatan'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#065F46', 'secondary' => '#047857', 'accent' => '#D97706'],
                    'gold' => ['primary' => '#92400E', 'secondary' => '#B45309', 'accent' => '#059669'],
                ],
                'featured' => true,
                'premium' => false,
                'rating' => 4.8,
                'downloads' => 0,
            ],

            // Template 2: SMA Madrasah Modern Integrated
            [
                'name' => 'SMA Madrasah Modern Integrated',
                'slug' => 'sma-madrasah-modern',
                'description' => 'Template modern terintegrasi untuk SMA Madrasah dengan teknologi terkini, sistem pembelajaran digital, dan interface user-friendly untuk generasi digital native',
                'category_id' => $pesantrenCategory->id,
                'preview_image' => 'templates/previews/sma-madrasah-modern.jpg',
                'template_data' => $this->getModernMadrasahTemplate(),
                'author' => 'School CMS Digital Team',
                'version' => '1.0.0',
                'features' => [
                    'Digital Learning Platform',
                    'E-Hafalan System',
                    'Online Assessment',
                    'Virtual Class Integration',
                    'Smart Dashboard',
                    'Mobile Responsive',
                    'Parent Portal',
                    'Digital Library'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#10B981', 'secondary' => '#059669', 'accent' => '#F59E0B'],
                    'tech' => ['primary' => '#3B82F6', 'secondary' => '#1D4ED8', 'accent' => '#10B981'],
                ],
                'featured' => true,
                'premium' => false,
                'rating' => 4.9,
                'downloads' => 0,
            ],

            // Template 3: SMA Madrasah Excellence
            [
                'name' => 'SMA Madrasah Excellence',
                'slug' => 'sma-madrasah-excellence',
                'description' => 'Template premium untuk SMA Madrasah unggulan dengan fokus prestasi akademik dan spiritual, dilengkapi sistem manajemen komprehensif dan fitur analitik tingkat lanjut',
                'category_id' => $pesantrenCategory->id,
                'preview_image' => 'templates/previews/sma-madrasah-excellence.jpg',
                'template_data' => $this->getExcellenceMadrasahTemplate(),
                'author' => 'School CMS Excellence Team',
                'version' => '1.0.0',
                'features' => [
                    'Excellence Dashboard',
                    'Achievement Tracking',
                    'Advanced Analytics',
                    'Multi-language Support',
                    'Alumni Network',
                    'Scholarship Management',
                    'International Program',
                    'Research Portal'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#7C2D12', 'secondary' => '#92400E', 'accent' => '#059669'],
                    'royal' => ['primary' => '#581C87', 'secondary' => '#7C2D12', 'accent' => '#CA8A04'],
                ],
                'featured' => true,
                'premium' => true,
                'rating' => 5.0,
                'downloads' => 0,
            ],
        ];

        foreach ($templates as $templateData) {
            TemplateGallery::updateOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
        }

        $this->command->info('Created 3 SMA Madrasah Aliyah templates successfully!');
    }

    /**
     * Classic Madrasah Template Structure
     */
    private function getClassicMadrasahTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMA Madrasah Classic',
                    'slug' => 'homepage',
                    'description' => 'Halaman utama dengan desain klasik islami',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Islamic Hero Classic',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Classical Islamic Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Madrasah Aliyah Al-Hikmah',
                                        'subtitle' => 'Menggabungkan Tradisi Islami dengan Pendidikan Modern untuk Mencetak Generasi Rabbani yang Berakhlak Mulia dan Berprestasi',
                                        'button_text' => 'Explore Madrasah',
                                        'button_url' => '/tentang',
                                        'background_image' => 'hero/classic-madrasah.jpg',
                                        'islamic_calligraphy' => 'وَمَا أُوتِيتُم مِّنَ الْعِلْمِ إِلَّا قَلِيلًا',
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Program Tradisional',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Traditional Programs',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Program Unggulan Tradisional',
                                        'subtitle' => 'Mempertahankan warisan ilmu dengan metode pembelajaran klasik',
                                        'cards' => [
                                            [
                                                'title' => 'Tahfidz 30 Juz',
                                                'description' => 'Program hafalan Al-Quran lengkap dengan metode talaqqi dan muraja\'ah intensif',
                                                'icon' => 'quran-classic',
                                            ],
                                            [
                                                'title' => 'Kitab Kuning',
                                                'description' => 'Pembelajaran kitab-kitab klasik dengan metode bandongan dan sorogan',
                                                'icon' => 'book-arabic',
                                            ],
                                            [
                                                'title' => 'Bahasa Arab & Inggris',
                                                'description' => 'Penguasaan dua bahasa internasional untuk persiapan masa depan',
                                                'icon' => 'languages',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Statistik Madrasah',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'Madrasah Statistics',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Prestasi Madrasah',
                                        'stats' => [
                                            ['number' => 750, 'label' => 'Total Santri', 'icon' => 'users'],
                                            ['number' => 35, 'label' => 'Ustadz Muallim', 'icon' => 'teacher'],
                                            ['number' => 120, 'label' => 'Hafidz Alumni', 'icon' => 'book'],
                                            ['number' => 98, 'label' => 'Tingkat Lulus', 'suffix' => '%', 'icon' => 'graduation'],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Modern Madrasah Template Structure
     */
    private function getModernMadrasahTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMA Madrasah Modern',
                    'slug' => 'homepage',
                    'description' => 'Halaman utama dengan desain modern dan teknologi',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Modern Islamic Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Modern Digital Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Madrasah Digital Innovation',
                                        'subtitle' => 'Mengintegrasikan Teknologi Modern dengan Nilai-nilai Islam untuk Mempersiapkan Pemimpin Masa Depan',
                                        'button_text' => 'Virtual Tour',
                                        'button_url' => '/virtual-tour',
                                        'secondary_button_text' => 'E-Learning Portal',
                                        'secondary_button_url' => '/elearning',
                                        'background_image' => 'hero/modern-madrasah.jpg',
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Digital Programs',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Digital Learning',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Program Digital Learning',
                                        'subtitle' => 'Pembelajaran abad 21 dengan teknologi terdepan',
                                        'cards' => [
                                            [
                                                'title' => 'E-Hafalan System',
                                                'description' => 'Sistem digital untuk tracking progress hafalan dengan AI voice recognition',
                                                'icon' => 'microphone',
                                            ],
                                            [
                                                'title' => 'Virtual Classroom',
                                                'description' => 'Kelas virtual interaktif dengan teknologi VR dan AR',
                                                'icon' => 'vr-headset',
                                            ],
                                            [
                                                'title' => 'Smart Assessment',
                                                'description' => 'Penilaian otomatis dengan analisis learning behavior',
                                                'icon' => 'chart-analytics',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Innovation Stats',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'Digital Innovation',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Digital Innovation Impact',
                                        'stats' => [
                                            ['number' => 95, 'label' => 'Digital Literacy', 'suffix' => '%', 'icon' => 'laptop'],
                                            ['number' => 850, 'label' => 'Online Students', 'icon' => 'users-online'],
                                            ['number' => 24, 'label' => 'Smart Classes', 'icon' => 'classroom'],
                                            ['number' => 100, 'label' => 'E-Books Available', 'icon' => 'books'],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Excellence Madrasah Template Structure
     */
    private function getExcellenceMadrasahTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMA Madrasah Excellence',
                    'slug' => 'homepage',
                    'description' => 'Halaman utama untuk madrasah unggulan',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Excellence Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Excellence Achievement Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Madrasah Aliyah Excellence',
                                        'subtitle' => 'Center of Islamic Academic Excellence - Membangun Pemimpin Global dengan Fondasi Keimanan yang Kuat',
                                        'button_text' => 'Excellence Program',
                                        'button_url' => '/excellence',
                                        'secondary_button_text' => 'International Class',
                                        'secondary_button_url' => '/international',
                                        'background_image' => 'hero/excellence-madrasah.jpg',
                                        'achievements' => ['ISO 9001:2015', 'Cambridge Certified', 'UNESCO Partner'],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Excellence Programs',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Excellence Features',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Excellence Programs',
                                        'subtitle' => 'Program unggulan untuk mencapai prestasi tertinggi',
                                        'cards' => [
                                            [
                                                'title' => 'International Class',
                                                'description' => 'Program kelas internasional dengan kurikulum Cambridge dan Oxford',
                                                'icon' => 'globe',
                                            ],
                                            [
                                                'title' => 'Research Center',
                                                'description' => 'Pusat penelitian siswa dengan mentor dari universitas terkemuka',
                                                'icon' => 'microscope',
                                            ],
                                            [
                                                'title' => 'Leadership Academy',
                                                'description' => 'Akademi kepemimpinan untuk mempersiapkan future leaders',
                                                'icon' => 'crown',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Excellence Achievement',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'World Class Achievement',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'World-Class Achievements',
                                        'stats' => [
                                            ['number' => 450, 'label' => 'Elite Students', 'icon' => 'star'],
                                            ['number' => 98, 'label' => 'University Acceptance', 'suffix' => '%', 'icon' => 'university'],
                                            ['number' => 15, 'label' => 'International Awards', 'icon' => 'trophy'],
                                            ['number' => 25, 'label' => 'PhD Alumni', 'icon' => 'graduation-cap'],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
