<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;

class TemplateGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create template categories
        $categories = [
            [
                'name' => 'Sekolah Dasar',
                'slug' => 'sekolah-dasar',
                'description' => 'Template khusus untuk website Sekolah Dasar dengan tampilan yang ceria dan ramah anak',
                'color' => '#10B981',
                'icon' => 'school-icon',
                'sort_order' => 1,
            ],
            [
                'name' => 'Sekolah Menengah',
                'slug' => 'sekolah-menengah',
                'description' => 'Template untuk SMP dan SMA dengan desain modern dan profesional',
                'color' => '#3B82F6',
                'icon' => 'graduation-icon',
                'sort_order' => 2,
            ],
            [
                'name' => 'Perguruan Tinggi',
                'slug' => 'perguruan-tinggi',
                'description' => 'Template untuk universitas dan akademi dengan tampilan akademis yang elegan',
                'color' => '#8B5CF6',
                'icon' => 'university-icon',
                'sort_order' => 3,
            ],
            [
                'name' => 'Pesantren',
                'slug' => 'pesantren',
                'description' => 'Template khusus untuk pondok pesantren dengan nuansa islami',
                'color' => '#059669',
                'icon' => 'mosque-icon',
                'sort_order' => 4,
            ],
            [
                'name' => 'Sekolah Kejuruan',
                'slug' => 'sekolah-kejuruan',
                'description' => 'Template untuk SMK dan lembaga pelatihan teknis',
                'color' => '#DC2626',
                'icon' => 'tools-icon',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $categoryData) {
            TemplateCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Get categories for templates
        $sdCategory = TemplateCategory::where('slug', 'sekolah-dasar')->first();
        $smpSmaCategory = TemplateCategory::where('slug', 'sekolah-menengah')->first();
        $ptCategory = TemplateCategory::where('slug', 'perguruan-tinggi')->first();
        $pesantrenCategory = TemplateCategory::where('slug', 'pesantren')->first();
        $smkCategory = TemplateCategory::where('slug', 'sekolah-kejuruan')->first();

        // Create gallery templates
        $templates = [
            // SD Templates
            [
                'name' => 'Rainbow Kids',
                'slug' => 'rainbow-kids',
                'description' => 'Template ceria dengan warna-warna cerah untuk SD, dilengkapi dengan animasi menarik dan layout yang ramah anak',
                'category_id' => $sdCategory->id,
                'preview_image' => 'templates/previews/rainbow-kids.jpg',
                'template_data' => $this->getRainbowKidsTemplate(),
                'author' => 'School CMS Team',
                'version' => '1.0.0',
                'features' => ['Hero Carousel', 'Program Cards', 'Guru & Staff', 'Galeri Kegiatan', 'Kontak'],
                'color_schemes' => [
                    'default' => ['primary' => '#F59E0B', 'secondary' => '#10B981', 'accent' => '#EF4444'],
                    'blue' => ['primary' => '#3B82F6', 'secondary' => '#06B6D4', 'accent' => '#8B5CF6'],
                    'green' => ['primary' => '#10B981', 'secondary' => '#059669', 'accent' => '#F59E0B'],
                ],
                'featured' => true,
                'rating' => 4.8,
                'downloads' => 234,
            ],
            [
                'name' => 'Little Scholars',
                'slug' => 'little-scholars',
                'description' => 'Template minimalis dan modern untuk SD dengan fokus pada pendidikan karakter dan prestasi siswa',
                'category_id' => $sdCategory->id,
                'preview_image' => 'templates/previews/little-scholars.jpg',
                'template_data' => $this->getLittleScholarsTemplate(),
                'author' => 'School CMS Team',
                'version' => '1.0.0',
                'features' => ['Clean Layout', 'Achievement Showcase', 'Event Calendar', 'News & Updates'],
                'featured' => false,
                'rating' => 4.6,
                'downloads' => 156,
            ],

            // SMP/SMA Templates
            [
                'name' => 'Academic Excellence',
                'slug' => 'academic-excellence',
                'description' => 'Template profesional untuk SMP/SMA dengan fokus pada prestasi akademik dan ekskul',
                'category_id' => $smpSmaCategory->id,
                'preview_image' => 'templates/previews/academic-excellence.jpg',
                'template_data' => $this->getAcademicExcellenceTemplate(),
                'author' => 'School CMS Team',
                'version' => '1.2.0',
                'features' => ['PPDB Online', 'Portal Akademik', 'Ekstrakurikuler', 'Alumni Network'],
                'featured' => true,
                'rating' => 4.9,
                'downloads' => 445,
            ],
            [
                'name' => 'Modern School',
                'slug' => 'modern-school',
                'description' => 'Template kontemporer dengan desain flat dan clean untuk sekolah menengah modern',
                'category_id' => $smpSmaCategory->id,
                'preview_image' => 'templates/previews/modern-school.jpg',
                'template_data' => $this->getModernSchoolTemplate(),
                'author' => 'School CMS Team',
                'version' => '1.1.0',
                'features' => ['Responsive Design', 'Student Portal', 'Online Library', 'Digital Assessment'],
                'featured' => false,
                'rating' => 4.5,
                'downloads' => 289,
            ],

            // Perguruan Tinggi Templates
            [
                'name' => 'University Pro',
                'slug' => 'university-pro',
                'description' => 'Template premium untuk universitas dengan fitur lengkap untuk akademik dan penelitian',
                'category_id' => $ptCategory->id,
                'preview_image' => 'templates/previews/university-pro.jpg',
                'template_data' => $this->getUniversityProTemplate(),
                'author' => 'School CMS Team',
                'version' => '2.0.0',
                'features' => ['Research Portal', 'Faculty Directory', 'Student Information System', 'E-Learning Integration'],
                'premium' => true,
                'featured' => true,
                'rating' => 4.9,
                'downloads' => 178,
            ],

            // Pesantren Templates
            [
                'name' => 'Islamic Heritage',
                'slug' => 'islamic-heritage',
                'description' => 'Template dengan nuansa islami untuk pondok pesantren dan sekolah islam',
                'category_id' => $pesantrenCategory->id,
                'preview_image' => 'templates/previews/islamic-heritage.jpg',
                'template_data' => $this->getIslamicHeritageTemplate(),
                'author' => 'School CMS Team',
                'version' => '1.0.0',
                'features' => ['Jadwal Sholat', 'Kajian Online', 'Santri Portal', 'Islamic Calendar'],
                'featured' => true,
                'rating' => 4.7,
                'downloads' => 134,
            ],

            // SMK Templates
            [
                'name' => 'Vocational Skills',
                'slug' => 'vocational-skills',
                'description' => 'Template khusus untuk SMK dengan showcase program keahlian dan industri partner',
                'category_id' => $smkCategory->id,
                'preview_image' => 'templates/previews/vocational-skills.jpg',
                'template_data' => $this->getVocationalSkillsTemplate(),
                'author' => 'School CMS Team',
                'version' => '1.0.0',
                'features' => ['Industry Partnership', 'Skill Certification', 'Job Placement', 'Workshop Gallery'],
                'featured' => false,
                'rating' => 4.4,
                'downloads' => 98,
            ],
        ];

        foreach ($templates as $templateData) {
            TemplateGallery::updateOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
        }
    }

    private function getRainbowKidsTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Rainbow Kids',
                    'slug' => 'homepage',
                    'description' => 'Halaman utama dengan tampilan ceria untuk SD',
                    'type' => 'page',
                    'active' => true,
                    'is_global' => false,
                    'sort_order' => 0,
                    'layout_settings' => [
                        'container_width' => 'full',
                        'sidebar' => false,
                    ],
                    'sections' => [
                        [
                            'name' => 'Hero Section',
                            'order' => 0,
                            'settings' => ['background' => 'gradient-rainbow'],
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Main Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Selamat Datang di SD Rainbow Kids',
                                        'subtitle' => 'Tempat terbaik untuk mengembangkan potensi anak dengan pembelajaran yang menyenangkan dan berkarakter',
                                        'button_text' => 'Daftar Sekarang',
                                        'button_url' => '/ppdb',
                                        'background_image' => 'hero/rainbow-kids-hero.jpg',
                                    ],
                                    'settings' => ['text_color' => 'white', 'overlay' => true],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Program Unggulan',
                            'order' => 1,
                            'settings' => ['background' => 'light'],
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Program Cards',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Program Unggulan Kami',
                                        'cards' => [
                                            [
                                                'title' => 'Pembelajaran Kreatif',
                                                'description' => 'Metode pembelajaran yang mengutamakan kreativitas dan inovasi',
                                                'image' => 'programs/creative-learning.jpg',
                                                'url' => '/program/kreatif',
                                            ],
                                            [
                                                'title' => 'Karakter Building',
                                                'description' => 'Pembentukan karakter islami dan nasionalis',
                                                'image' => 'programs/character-building.jpg',
                                                'url' => '/program/karakter',
                                            ],
                                            [
                                                'title' => 'STEM Education',
                                                'description' => 'Science, Technology, Engineering & Math untuk masa depan',
                                                'image' => 'programs/stem.jpg',
                                                'url' => '/program/stem',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Statistik Sekolah',
                            'order' => 2,
                            'settings' => ['background' => 'primary'],
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'School Stats',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SD Rainbow Kids in Numbers',
                                        'stats' => [
                                            ['number' => 450, 'label' => 'Siswa Aktif', 'description' => 'Dari kelas 1-6'],
                                            ['number' => 24, 'label' => 'Guru Berkualitas', 'description' => 'Berpengalaman & tersertifikasi'],
                                            ['number' => 12, 'label' => 'Kelas', 'description' => '2 kelas per tingkat'],
                                            ['number' => 15, 'label' => 'Tahun Pengalaman', 'description' => 'Sejak 2009'],
                                        ],
                                    ],
                                    'settings' => ['text_color' => 'white'],
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

    private function getLittleScholarsTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Little Scholars',
                    'slug' => 'homepage',
                    'description' => 'Homepage dengan desain minimalis untuk SD',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Clean Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Welcome Hero',
                                    'content' => [
                                        'title' => 'SD Little Scholars',
                                        'subtitle' => 'Mengembangkan potensi anak dengan pendekatan yang tepat',
                                        'button_text' => 'Pelajari Lebih Lanjut',
                                        'button_url' => '/tentang',
                                    ],
                                    'order' => 0,
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                    'assignments' => [
                        ['route_pattern' => 'home', 'priority' => 10, 'active' => true],
                    ],
                ],
            ],
        ];
    }

    private function getAcademicExcellenceTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Academic Excellence',
                    'slug' => 'homepage',
                    'description' => 'Homepage profesional untuk SMP/SMA',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Professional Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Academic Hero',
                                    'content' => [
                                        'title' => 'SMA Academic Excellence',
                                        'subtitle' => 'Mempersiapkan generasi unggul untuk masa depan gemilang',
                                        'button_text' => 'PPDB 2024/2025',
                                        'button_url' => '/ppdb',
                                    ],
                                    'order' => 0,
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                    'assignments' => [
                        ['route_pattern' => 'home', 'priority' => 10, 'active' => true],
                    ],
                ],
            ],
        ];
    }

    private function getModernSchoolTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Modern School',
                    'slug' => 'homepage',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Modern Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Modern Hero',
                                    'content' => [
                                        'title' => 'Modern School',
                                        'subtitle' => 'Pendidikan berkualitas dengan teknologi terdepan',
                                    ],
                                    'order' => 0,
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                    'assignments' => [
                        ['route_pattern' => 'home', 'priority' => 10, 'active' => true],
                    ],
                ],
            ],
        ];
    }

    private function getUniversityProTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage University Pro',
                    'slug' => 'homepage',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'University Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'University Hero',
                                    'content' => [
                                        'title' => 'Universitas Excellence',
                                        'subtitle' => 'Pusat penelitian dan pendidikan tinggi terdepan',
                                    ],
                                    'order' => 0,
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                    'assignments' => [
                        ['route_pattern' => 'home', 'priority' => 10, 'active' => true],
                    ],
                ],
            ],
        ];
    }

    private function getIslamicHeritageTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Islamic Heritage',
                    'slug' => 'homepage',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Islamic Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Islamic Hero',
                                    'content' => [
                                        'title' => 'Pondok Pesantren Al-Hikmah',
                                        'subtitle' => 'Mendidik generasi Qur\'ani dengan akhlaqul karimah',
                                    ],
                                    'order' => 0,
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                    'assignments' => [
                        ['route_pattern' => 'home', 'priority' => 10, 'active' => true],
                    ],
                ],
            ],
        ];
    }

    private function getVocationalSkillsTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage Vocational Skills',
                    'slug' => 'homepage',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Vocational Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Vocational Hero',
                                    'content' => [
                                        'title' => 'SMK Vocational Skills',
                                        'subtitle' => 'Mempersiapkan tenaga kerja terampil dan siap industri',
                                    ],
                                    'order' => 0,
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                    'assignments' => [
                        ['route_pattern' => 'home', 'priority' => 10, 'active' => true],
                    ],
                ],
            ],
        ];
    }
}
