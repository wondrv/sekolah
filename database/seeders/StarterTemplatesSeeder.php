<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateGallery;
use Illuminate\Support\Str;

class StarterTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        // Only add if gallery relatively empty
        if (TemplateGallery::count() > 0) {
            return; // Avoid duplicating in existing populated environments
        }

        $templates = [
            [
                'name' => 'Modern School',
                'slug' => 'modern-school',
                'description' => 'Landing page modern dengan hero besar, statistik siswa & program unggulan.',
                'author' => 'System',
                'version' => '1.0.0',
                'features' => ['Hero Headline', 'Program Grid', 'Stats Section', 'CTA Banner'],
                'rating' => 4.8,
                'downloads' => 0,
                'featured' => true,
                'premium' => false,
                'category_id' => null,
                'template_data' => [
                    'templates' => [
                        [
                            'name' => 'Homepage',
                            'type' => 'homepage',
                            'sections' => [
                                [
                                    'name' => 'Hero',
                                    'order' => 1,
                                    'blocks' => [
                                        [
                                            'type' => 'hero',
                                            'order' => 1,
                                            'data' => [
                                                'title' => 'Selamat Datang di Sekolah Modern',
                                                'subtitle' => 'Membangun generasi cerdas berkarakter.',
                                                'button_text' => 'Daftar Sekarang',
                                                'button_url' => '/ppdb'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'name' => 'Programs',
                                    'order' => 2,
                                    'blocks' => [
                                        [
                                            'type' => 'card-grid',
                                            'order' => 1,
                                            'data' => [
                                                'title' => 'Program Unggulan',
                                                'items' => [
                                                    ['title' => 'Sains Terapan', 'description' => 'Eksperimen & riset'],
                                                    ['title' => 'Bahasa Asing', 'description' => 'Mandarin & Inggris'],
                                                    ['title' => 'Teknologi', 'description' => 'Coding & robotik'],
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'name' => 'Stats',
                                    'order' => 3,
                                    'blocks' => [
                                        [
                                            'type' => 'stats',
                                            'order' => 1,
                                            'data' => [
                                                'items' => [
                                                    ['label' => 'Siswa Aktif', 'value' => 850],
                                                    ['label' => 'Guru', 'value' => 65],
                                                    ['label' => 'Alumni', 'value' => 5400],
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'name' => 'CTA',
                                    'order' => 4,
                                    'blocks' => [
                                        [
                                            'type' => 'cta-banner',
                                            'order' => 1,
                                            'data' => [
                                                'title' => 'Mulai Perjalanan Pendidikan Terbaik',
                                                'button_text' => 'Hubungi Kami',
                                                'button_url' => '/kontak'
                                            ]
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Simple Clean',
                'slug' => 'simple-clean',
                'description' => 'Tema ringan dengan fokus konten & keterbacaan.',
                'author' => 'System',
                'version' => '1.0.0',
                'features' => ['Simple Hero', 'Content Focus', 'Fast Loading'],
                'rating' => 4.6,
                'downloads' => 0,
                'featured' => false,
                'premium' => false,
                'category_id' => null,
                'template_data' => [
                    'templates' => [
                        [
                            'name' => 'Homepage',
                            'type' => 'homepage',
                            'sections' => [
                                [
                                    'name' => 'Hero',
                                    'order' => 1,
                                    'blocks' => [
                                        [
                                            'type' => 'hero',
                                            'order' => 1,
                                            'data' => [
                                                'title' => 'Sekolah Fokus Prestasi',
                                                'subtitle' => 'Belajar tenang, hasil gemilang.',
                                                'button_text' => 'Pelajari',
                                                'button_url' => '/tentang-kami'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'name' => 'Content',
                                    'order' => 2,
                                    'blocks' => [
                                        [
                                            'type' => 'rich-text',
                                            'order' => 1,
                                            'data' => [
                                                'html' => '<p>Kami berkomitmen pada pembelajaran bermakna dan karakter kuat.</p>'
                                            ]
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Islamic Serenity',
                'slug' => 'islamic-serenity',
                'description' => 'Nuansa islami dengan highlight jadwal sholat & hadis motivasi.',
                'author' => 'System',
                'version' => '1.0.0',
                'features' => ['Prayer Times Placeholder', 'Quote Section', 'Programs', 'CTA PPDB'],
                'rating' => 4.7,
                'downloads' => 0,
                'featured' => true,
                'premium' => false,
                'category_id' => null,
                'template_data' => [
                    'templates' => [[
                        'name' => 'Homepage',
                        'type' => 'homepage',
                        'sections' => [
                            [
                                'name' => 'Hero',
                                'order' => 1,
                                'blocks' => [[
                                    'type' => 'hero',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Mencetak Generasi Qur\'ani',
                                        'subtitle' => 'Ilmu, Akhlak, dan Amal Shalih.',
                                        'button_text' => 'PPDB 2025',
                                        'button_url' => '/ppdb'
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'Quote',
                                'order' => 2,
                                'blocks' => [[
                                    'type' => 'rich-text',
                                    'order' => 1,
                                    'data' => [
                                        'html' => '<blockquote class="border-l-4 pl-4 italic text-emerald-700">“Sebaik-baik kalian adalah yang belajar Al-Qur\'an dan mengajarkannya.”</blockquote>'
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'Programs',
                                'order' => 3,
                                'blocks' => [[
                                    'type' => 'card-grid',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Program Pembinaan',
                                        'items' => [
                                            ['title' => 'Tahfidz', 'description' => 'Target hafalan bertahap'],
                                            ['title' => 'Bahasa Arab', 'description' => 'Dasar & percakapan'],
                                            ['title' => 'Adab Islam', 'description' => 'Pembiasaan harian'],
                                        ]
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'CTA',
                                'order' => 4,
                                'blocks' => [[
                                    'type' => 'cta-banner',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Daftar & Mulai Hafalan Pertamamu',
                                        'button_text' => 'Daftar Sekarang',
                                        'button_url' => '/ppdb'
                                    ]
                                ]]
                            ]
                        ]
                    ]]
                ],
            ],
            [
                'name' => 'Vocational Tech',
                'slug' => 'vocational-tech',
                'description' => 'Fokus kompetensi bidang teknik & teknologi.',
                'author' => 'System',
                'version' => '1.0.0',
                'features' => ['Technology Focus', 'Program Grid', 'Lab Highlight', 'Enrollment CTA'],
                'rating' => 4.5,
                'downloads' => 0,
                'featured' => false,
                'premium' => false,
                'category_id' => null,
                'template_data' => [
                    'templates' => [[
                        'name' => 'Homepage',
                        'type' => 'homepage',
                        'sections' => [
                            [
                                'name' => 'Hero',
                                'order' => 1,
                                'blocks' => [[
                                    'type' => 'hero',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Sekolah Kejuruan Berbasis Industri',
                                        'subtitle' => 'Siap kerja. Siap wirausaha. Siap inovasi.',
                                        'button_text' => 'Lihat Jurusan',
                                        'button_url' => '/program'
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'Departments',
                                'order' => 2,
                                'blocks' => [[
                                    'type' => 'card-grid',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Kompetensi Keahlian',
                                        'items' => [
                                            ['title' => 'Teknik Otomotif', 'description' => 'Mesin & sistem modern'],
                                            ['title' => 'Rekayasa Perangkat Lunak', 'description' => 'Pengembangan aplikasi'],
                                            ['title' => 'Desain Komunikasi Visual', 'description' => 'Brand & media kreatif'],
                                        ]
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'Lab',
                                'order' => 3,
                                'blocks' => [[
                                    'type' => 'rich-text',
                                    'order' => 1,
                                    'data' => [
                                        'html' => '<p>Kami memiliki 7 laboratorium industri-ready untuk praktik intensif.</p>'
                                    ]
                                ]]
                            ],
                        ]
                    ]]
                ],
            ],
            [
                'name' => 'Dark Academic',
                'slug' => 'dark-academic',
                'description' => 'Tema gelap formal dengan penekanan akademik klasik.',
                'author' => 'System',
                'version' => '1.0.0',
                'features' => ['Dark Tone', 'Serif Typography', 'Quote Section', 'Stats'],
                'rating' => 4.9,
                'downloads' => 0,
                'featured' => true,
                'premium' => false,
                'category_id' => null,
                'template_data' => [
                    'templates' => [[
                        'name' => 'Homepage',
                        'type' => 'homepage',
                        'sections' => [
                            [
                                'name' => 'Hero',
                                'order' => 1,
                                'blocks' => [[
                                    'type' => 'hero',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Tradisi Ilmu. Masa Depan Cemerlang.',
                                        'subtitle' => 'Integritas, Riset, Ekselensi.',
                                        'button_text' => 'Akademik',
                                        'button_url' => '/tentang-kami'
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'Narrative',
                                'order' => 2,
                                'blocks' => [[
                                    'type' => 'rich-text',
                                    'order' => 1,
                                    'data' => [
                                        'html' => '<p>Berfokus pada pengembangan intelektual mendalam dan budaya literasi.</p>'
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'Stats',
                                'order' => 3,
                                'blocks' => [[
                                    'type' => 'stats',
                                    'order' => 1,
                                    'data' => [
                                        'items' => [
                                            ['label' => 'Karya Ilmiah', 'value' => 320],
                                            ['label' => 'Kolaborasi Kampus', 'value' => 14],
                                            ['label' => 'Perpustakaan Digital', 'value' => 12000],
                                        ]
                                    ]
                                ]]
                            ],
                        ]
                    ]]
                ],
            ],
            [
                'name' => 'Creative Arts',
                'slug' => 'creative-arts',
                'description' => 'Berwarna & ekspresif untuk sekolah seni / kreatif.',
                'author' => 'System',
                'version' => '1.0.0',
                'features' => ['Vibrant Palette', 'Program Showcase', 'Event Teaser'],
                'rating' => 4.4,
                'downloads' => 0,
                'featured' => false,
                'premium' => false,
                'category_id' => null,
                'template_data' => [
                    'templates' => [[
                        'name' => 'Homepage',
                        'type' => 'homepage',
                        'sections' => [
                            [
                                'name' => 'Hero',
                                'order' => 1,
                                'blocks' => [[
                                    'type' => 'hero',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Ekspresikan Kreativitasmu',
                                        'subtitle' => 'Desain • Musik • Teater • Visual',
                                        'button_text' => 'Lihat Program',
                                        'button_url' => '/program'
                                    ]
                                ]]
                            ],
                            [
                                'name' => 'Showcase',
                                'order' => 2,
                                'blocks' => [[
                                    'type' => 'card-grid',
                                    'order' => 1,
                                    'data' => [
                                        'title' => 'Bidang Seni',
                                        'items' => [
                                            ['title' => 'Desain Grafis', 'description' => 'Branding & ilustrasi'],
                                            ['title' => 'Musik Modern', 'description' => 'Ensemble & produksi'],
                                            ['title' => 'Seni Pertunjukan', 'description' => 'Drama & tari'],
                                        ]
                                    ]
                                ]]
                            ],
                        ]
                    ]]
                ],
            ],
        ];

        foreach ($templates as $tpl) {
            TemplateGallery::create([
                'name' => $tpl['name'],
                'slug' => $tpl['slug'],
                'description' => $tpl['description'],
                'author' => $tpl['author'],
                'version' => $tpl['version'],
                'features' => $tpl['features'],
                'rating' => $tpl['rating'],
                'downloads' => $tpl['downloads'],
                'featured' => $tpl['featured'],
                'premium' => $tpl['premium'],
                'category_id' => $tpl['category_id'],
                'template_data' => $tpl['template_data'],
                'preview_image' => null,
                'active' => true,
            ]);
        }
    }
}
