<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateGallery;
use App\Models\TemplateCategory;

class SmartImportTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create "Ready-to-Use" category for pre-configured templates
        $readyCategory = TemplateCategory::firstOrCreate(
            ['slug' => 'ready-to-use'],
            [
                'name' => 'Ready to Use',
                'description' => 'Pre-configured school templates ready for immediate use',
                'color' => '#10B981',
                'sort_order' => 1
            ]
        );

        // Create sample ready-to-use templates with Indonesian content
        $this->createSchoolTemplates($readyCategory);
    }

    /**
     * Create ready-to-use school templates
     */
    protected function createSchoolTemplates(TemplateCategory $category): void
    {
        $templates = [
            [
                'name' => 'Sekolah Modern',
                'slug' => 'sekolah-modern',
                'description' => 'Template modern untuk sekolah dengan tampilan professional dan fitur lengkap',
                'template_data' => $this->getModernSchoolTemplate(),
                'features' => ['Responsive Design', 'SEO Optimized', 'Multi Language', 'Fast Loading', 'Modern UI'],
                'preview_image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&h=600&fit=crop&crop=center&auto=format&q=60'
            ],
            [
                'name' => 'Universitas Berkelas',
                'slug' => 'universitas-berkelas',
                'description' => 'Template elegante untuk universitas dengan layout akademik yang sophisticated',
                'template_data' => $this->getUniversityTemplate(),
                'features' => ['Academic Layout', 'Student Portal', 'Research Focus', 'Faculty Showcase', 'Alumni Network'],
                'preview_image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop&crop=center&auto=format&q=60'
            ],
            [
                'name' => 'SD Ceria',
                'slug' => 'sd-ceria',
                'description' => 'Template ceria dan colorful untuk sekolah dasar dengan elemen visual yang menyenangkan',
                'template_data' => $this->getElementarySchoolTemplate(),
                'features' => ['Kid Friendly', 'Colorful Design', 'Interactive Elements', 'Parent Portal', 'Activity Gallery'],
                'preview_image' => 'https://images.unsplash.com/photo-1497486751825-1233686d5d80?w=800&h=600&fit=crop&crop=center&auto=format&q=60'
            ],
            [
                'name' => 'SMK Teknologi',
                'slug' => 'smk-teknologi',
                'description' => 'Template futuristic untuk SMK dengan fokus pada teknologi dan industry 4.0',
                'template_data' => $this->getVocationalSchoolTemplate(),
                'features' => ['Tech Focused', 'Industry Ready', 'Skills Showcase', 'Partnership Display', 'Career Center'],
                'preview_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&h=600&fit=crop&crop=center&auto=format&q=60'
            ]
        ];

        foreach ($templates as $templateData) {
            TemplateGallery::create([
                'name' => $templateData['name'],
                'slug' => $templateData['slug'],
                'description' => $templateData['description'],
                'category_id' => $category->id,
                'preview_image' => $templateData['preview_image'],
                'template_data' => $templateData['template_data'],
                'author' => 'Smart Import System',
                'version' => '1.0.0',
                'features' => $templateData['features'],
                'rating' => 4.8,
                'featured' => true,
                'premium' => false,
                'active' => true
            ]);
        }
    }

    /**
     * Modern School Template
     */
    protected function getModernSchoolTemplate(): array
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Sekolah Modern',
                    'slug' => 'home',
                    'description' => 'Halaman utama sekolah modern dengan desain professional',
                    'active' => true,
                    'type' => 'page',
                    'sections' => [
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Hero Utama',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Selamat Datang di Sekolah Modern',
                                        'subtitle' => 'Membangun generasi unggul dengan pendidikan berkualitas dan teknologi terdepan',
                                        'button_text' => 'Daftar Sekarang',
                                        'button_url' => '/pendaftaran',
                                        'background_image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1200&h=800',
                                        'background_color' => 'bg-gradient-to-r from-blue-600 to-blue-800'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Keunggulan Section',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Keunggulan Sekolah',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Mengapa Memilih Sekolah Kami?',
                                        'subtitle' => 'Keunggulan yang membuat kami berbeda',
                                        'cards' => [
                                            [
                                                'title' => 'Fasilitas Modern',
                                                'description' => 'Laboratorium lengkap, ruang kelas ber-AC, perpustakaan digital',
                                                'icon' => '🏫',
                                                'image' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Guru Berpengalaman',
                                                'description' => 'Tim pengajar profesional dengan kualifikasi S1 dan S2',
                                                'icon' => '👨‍🏫',
                                                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Kurikulum Terintegrasi',
                                                'description' => 'Menggabungkan kurikulum nasional dengan program internasional',
                                                'icon' => '📚',
                                                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=300'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Statistik Section',
                            'order' => 3,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'Statistik Sekolah',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Pencapaian Kami',
                                        'stats' => [
                                            ['number' => '1200+', 'label' => 'Siswa Aktif'],
                                            ['number' => '85+', 'label' => 'Guru & Staff'],
                                            ['number' => '95%', 'label' => 'Tingkat Kelulusan'],
                                            ['number' => '50+', 'label' => 'Prestasi Nasional']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'CTA Section',
                            'order' => 4,
                            'blocks' => [
                                [
                                    'type' => 'cta_banner',
                                    'name' => 'Call to Action',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Bergabunglah Dengan Sekolah Modern',
                                        'description' => 'Wujudkan impian pendidikan terbaik untuk masa depan yang cerah',
                                        'button_text' => 'Hubungi Kami',
                                        'button_url' => '/kontak',
                                        'background_color' => 'bg-blue-600'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'assignments' => [
                        ['route_pattern' => 'home', 'priority' => 100],
                        ['route_pattern' => '/', 'priority' => 100]
                    ]
                ]
            ]
        ];
    }

    /**
     * University Template
     */
    protected function getUniversityTemplate(): array
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Universitas',
                    'slug' => 'home',
                    'description' => 'Halaman utama universitas dengan tampilan akademik professional',
                    'active' => true,
                    'type' => 'page',
                    'sections' => [
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Hero Universitas',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Universitas Terdepan dalam Inovasi',
                                        'subtitle' => 'Menghasilkan lulusan berkualitas dengan penelitian dan pengabdian masyarakat yang unggul',
                                        'button_text' => 'Jelajahi Program',
                                        'button_url' => '/program-studi',
                                        'background_image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&h=800',
                                        'background_color' => 'bg-gradient-to-r from-emerald-600 to-emerald-800'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Fakultas Section',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Fakultas Unggulan',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Fakultas & Program Studi',
                                        'subtitle' => 'Pilihan program studi terbaik untuk masa depan cemerlang',
                                        'cards' => [
                                            [
                                                'title' => 'Fakultas Teknik',
                                                'description' => 'Program studi teknik terdepan dengan laboratorium modern',
                                                'icon' => '⚙️',
                                                'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Fakultas Ekonomi',
                                                'description' => 'Mempersiapkan leader masa depan di bidang bisnis dan ekonomi',
                                                'icon' => '💼',
                                                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Fakultas Kedokteran',
                                                'description' => 'Program kedokteran dengan standar internasional',
                                                'icon' => '🏥',
                                                'image' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Elementary School Template
     */
    protected function getElementarySchoolTemplate(): array
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SD Ceria',
                    'slug' => 'home',
                    'description' => 'Halaman utama SD dengan desain ceria dan ramah anak',
                    'active' => true,
                    'type' => 'page',
                    'sections' => [
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Hero SD Ceria',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Selamat Datang di SD Ceria! 🌟',
                                        'subtitle' => 'Tempat belajar yang menyenangkan untuk putra-putri tercinta dengan lingkungan yang aman dan mendukung',
                                        'button_text' => 'Lihat Kegiatan',
                                        'button_url' => '/kegiatan',
                                        'background_image' => 'https://images.unsplash.com/photo-1497486751825-1233686d5d80?w=1200&h=800',
                                        'background_color' => 'bg-gradient-to-r from-pink-400 to-purple-500'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Program Section',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Program Unggulan',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Program Belajar Menyenangkan 🎈',
                                        'subtitle' => 'Belajar sambil bermain dengan metode yang interaktif',
                                        'cards' => [
                                            [
                                                'title' => 'Belajar Sambil Bermain',
                                                'description' => 'Metode pembelajaran yang menyenangkan dan interaktif',
                                                'icon' => '🎮',
                                                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Ekstrakurikuler Beragam',
                                                'description' => 'Seni, olahraga, dan teknologi untuk mengembangkan bakat',
                                                'icon' => '🎨',
                                                'image' => 'https://images.unsplash.com/photo-1544717297-fa95b6ee9643?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Lingkungan Aman',
                                                'description' => 'Area bermain yang aman dengan pengawasan 24 jam',
                                                'icon' => '🛡️',
                                                'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=400&h=300'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Vocational School Template
     */
    protected function getVocationalSchoolTemplate(): array
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMK Teknologi',
                    'slug' => 'home',
                    'description' => 'Halaman utama SMK dengan fokus teknologi dan industri',
                    'active' => true,
                    'type' => 'page',
                    'sections' => [
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Hero SMK Teknologi',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'SMK Teknologi - Ready for Industry 4.0',
                                        'subtitle' => 'Mempersiapkan tenaga kerja terampil dengan kompetensi teknologi terdepan sesuai kebutuhan industri',
                                        'button_text' => 'Lihat Jurusan',
                                        'button_url' => '/jurusan',
                                        'background_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1200&h=800',
                                        'background_color' => 'bg-gradient-to-r from-cyan-600 to-blue-700'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name' => 'Jurusan Section',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Jurusan Unggulan',
                                    'order' => 1,
                                    'content' => [
                                        'title' => 'Jurusan Teknologi Terdepan',
                                        'subtitle' => 'Program keahlian yang sesuai dengan perkembangan industri modern',
                                        'cards' => [
                                            [
                                                'title' => 'Teknik Komputer & Jaringan',
                                                'description' => 'Ahli dalam bidang networking, cybersecurity, dan sistem komputer',
                                                'icon' => '💻',
                                                'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Rekayasa Perangkat Lunak',
                                                'description' => 'Developer handal untuk aplikasi web dan mobile',
                                                'icon' => '📱',
                                                'image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=300'
                                            ],
                                            [
                                                'title' => 'Teknik Otomotif',
                                                'description' => 'Teknisi otomotif modern dengan teknologi hybrid dan electric',
                                                'icon' => '🚗',
                                                'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&h=300'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
