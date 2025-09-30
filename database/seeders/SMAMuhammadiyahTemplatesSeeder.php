<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;

class SMAMuhammadiyahTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $pesantrenCategory = TemplateCategory::where('slug', 'pesantren')->first();
        $smpSmaCategory = TemplateCategory::where('slug', 'sekolah-menengah')->first();

        // Create templates inspired by SMA Muhammadiyah 1 Taman
        $templates = [
            // Template 1: SMA Muhammadiyah Excellent School
            [
                'name' => 'SMA Muhammadiyah Excellent School',
                'slug' => 'sma-muhammadiyah-excellent',
                'description' => 'Template berdasarkan SMA Muhammadiyah 1 Taman dengan motto "The Excellent School", menggabungkan nilai-nilai islami Muhammadiyah dengan prestasi akademik unggul',
                'category_id' => $pesantrenCategory->id,
                'preview_image' => 'templates/previews/sma-muhammadiyah-excellent.jpg',
                'template_data' => $this->getMuhammadiyahExcellentTemplate(),
                'author' => 'School CMS Muhammadiyah Team',
                'version' => '1.0.0',
                'features' => [
                    'The Excellent School Design',
                    'ISMUBA Integration',
                    'PPDB Online System',
                    'Core Values Display',
                    'Program Unggulan',
                    'Gallery & Testimonial',
                    'Management Structure',
                    'Resource Center'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#1565C0', 'secondary' => '#0D47A1', 'accent' => '#FF9800'],
                    'muhammadiyah' => ['primary' => '#1A237E', 'secondary' => '#3F51B5', 'accent' => '#FFC107'],
                ],
                'featured' => true,
                'premium' => false,
                'rating' => 4.9,
                'downloads' => 0,
            ],

            // Template 2: SMA Modern Islamic
            [
                'name' => 'SMA Modern Islamic',
                'slug' => 'sma-modern-islamic',
                'description' => 'Template modern untuk SMA Islam dengan fokus pada karakter islami, prestasi akademik, dan persiapan daya saing global sesuai visi misi sekolah islam modern',
                'category_id' => $pesantrenCategory->id,
                'preview_image' => 'templates/previews/sma-modern-islamic.jpg',
                'template_data' => $this->getModernIslamicTemplate(),
                'author' => 'School CMS Islamic Team',
                'version' => '1.0.0',
                'features' => [
                    'Modern Islamic Design',
                    'Character Building Focus',
                    'Academic Excellence',
                    'Global Competition Ready',
                    'Multi-language Support',
                    'Student Portal',
                    'Alumni Network',
                    'Achievement Gallery'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#2E7D32', 'secondary' => '#388E3C', 'accent' => '#FF5722'],
                    'elegant' => ['primary' => '#4A148C', 'secondary' => '#6A1B9A', 'accent' => '#FF9800'],
                ],
                'featured' => true,
                'premium' => false,
                'rating' => 4.8,
                'downloads' => 0,
            ],

            // Template 3: SMA Prestasi Unggul
            [
                'name' => 'SMA Prestasi Unggul',
                'slug' => 'sma-prestasi-unggul',
                'description' => 'Template khusus untuk SMA dengan fokus pencapaian prestasi tinggi, dilengkapi showcase prestasi, program unggulan, dan sistem tracking prestasi siswa',
                'category_id' => $smpSmaCategory->id,
                'preview_image' => 'templates/previews/sma-prestasi-unggul.jpg',
                'template_data' => $this->getPrestasiUnggulTemplate(),
                'author' => 'School CMS Achievement Team',
                'version' => '1.0.0',
                'features' => [
                    'Achievement Showcase',
                    'Performance Dashboard',
                    'Competition Tracker',
                    'Student Progress',
                    'Awards Gallery',
                    'Success Stories',
                    'Excellence Programs',
                    'Alumni Success'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#C62828', 'secondary' => '#D32F2F', 'accent' => '#FF9800'],
                    'champion' => ['primary' => '#F57C00', 'secondary' => '#FF9800', 'accent' => '#4CAF50'],
                ],
                'featured' => true,
                'premium' => false,
                'rating' => 4.7,
                'downloads' => 0,
            ],
        ];

        foreach ($templates as $templateData) {
            TemplateGallery::updateOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
        }

        $this->command->info('Created 3 additional SMA templates based on Muhammadiyah reference!');
    }

    /**
     * Muhammadiyah Excellent School Template
     */
    private function getMuhammadiyahExcellentTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMA Muhammadiyah Excellent',
                    'slug' => 'homepage',
                    'description' => 'Homepage lengkap berdasarkan SMA Muhammadiyah 1 Taman',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        // Navigation Menu
                        [
                            'name' => 'Main Navigation',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Navigation Menu',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '<nav class="navbar navbar-expand-lg bg-white shadow-sm">
                                            <div class="container">
                                                <a class="navbar-brand" href="/"><img src="/images/logo-smam1ta.png" alt="SMA Muhammadiyah 1 Taman" height="40"></a>
                                                <div class="collapse navbar-collapse">
                                                    <ul class="navbar-nav ms-auto">
                                                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Tentang Kami</a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="/profil-sekolah">Profil Sekolah</a></li>
                                                                <li><a class="dropdown-item" href="/visi-misi">Visi & Misi</a></li>
                                                                <li><a class="dropdown-item" href="/struktur-organisasi">Struktur Organisasi</a></li>
                                                                <li><a class="dropdown-item" href="/sejarah">Sejarah</a></li>
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manajemen</a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="/kurikulum">Kurikulum</a></li>
                                                                <li><a class="dropdown-item" href="/kesiswaan">Kesiswaan</a></li>
                                                                <li><a class="dropdown-item" href="/ismuba">ISMUBA</a></li>
                                                                <li><a class="dropdown-item" href="/humas">Humas</a></li>
                                                                <li><a class="dropdown-item" href="/sarpras">Sarpras</a></li>
                                                                <li><a class="dropdown-item" href="/human-resource">Human Resource</a></li>
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Layanan</a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="/ppdb">PPDB</a></li>
                                                                <li><a class="dropdown-item" href="/rapor">Rapor</a></li>
                                                                <li><a class="dropdown-item" href="/skl">SKL</a></li>
                                                                <li><a class="dropdown-item" href="/berita">Berita</a></li>
                                                                <li><a class="dropdown-item" href="/pengumuman">Pengumuman</a></li>
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Resource</a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="/unduh">Unduh</a></li>
                                                                <li><a class="dropdown-item" href="/pusat-bantuan">Pusat Bantuan</a></li>
                                                                <li><a class="dropdown-item" href="/acara">Acara</a></li>
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item"><a class="nav-link" href="/fasilitas">Fasilitas</a></li>
                                                        <li class="nav-item"><a class="nav-link" href="/kontak">Kontak</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </nav>'
                                    ],
                                    'settings' => [
                                        'sticky' => true
                                    ]
                                ]
                            ]
                        ],

                        // Hero Section
                        [
                            'name' => 'Excellent School Hero',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Muhammadiyah Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Muhammadiyah 1 Taman',
                                        'subtitle' => 'The Excellent School - Sholeh Dalam Perilaku, Unggul Dalam Mutu dan Berdaya Saing Global. Bersama SMAMITA, Membangun Karakter untuk Indonesia',
                                        'description' => 'Sekolah berbasis islami unggul prestasi di Sidoarjo, Jawa Timur. Berbekal keahlian kompetensi dengan menerapkan budaya Islam untuk menghadapi tantangan global.',
                                        'button_text' => 'Daftar Sekarang',
                                        'button_url' => 'https://registration.smam1ta.sch.id/',
                                        'secondary_button_text' => 'Lihat Fasilitas',
                                        'secondary_button_url' => '/fasilitas',
                                        'background_image' => 'hero/smam1ta-hero.jpg',
                                        'badges' => ['The Excellent School', 'Akreditasi A', 'ISO 9001:2015']
                                    ],
                                    'settings' => [
                                        'text_color' => 'white',
                                        'overlay' => true,
                                        'animation' => 'fade-in',
                                        'has_badges' => true,
                                        'hero_style' => 'islamic'
                                    ]
                                ]
                            ]
                        ],

                        // Core Values Section
                        [
                            'name' => 'Core Values',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'CORE VALUES',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'CORE VALUE',
                                        'subtitle' => 'Nilai-nilai Inti yang Menjadi Fondasi Pendidikan SMAMITA',
                                        'cards' => [
                                            [
                                                'title' => 'RELIGIUS',
                                                'description' => 'Mengembangkan karakter islami dan spiritual yang kuat berdasarkan Al-Quran dan Sunnah',
                                                'icon' => 'mosque',
                                                'color' => '#1565C0'
                                            ],
                                            [
                                                'title' => 'EXCELLENCE',
                                                'description' => 'Mencapai keunggulan dalam akademik, non-akademik, dan pengembangan diri',
                                                'icon' => 'trophy',
                                                'color' => '#FF9800'
                                            ],
                                            [
                                                'title' => 'INNOVATIVE',
                                                'description' => 'Mengintegrasikan teknologi dan metode pembelajaran inovatif dalam pendidikan',
                                                'icon' => 'lightbulb',
                                                'color' => '#4CAF50'
                                            ],
                                            [
                                                'title' => 'GLOBAL MINDSET',
                                                'description' => 'Mempersiapkan siswa menghadapi tantangan dan kompetisi di era global',
                                                'icon' => 'globe',
                                                'color' => '#9C27B0'
                                            ]
                                        ]
                                    ],
                                    'settings' => [
                                        'columns' => 4,
                                        'style' => 'modern'
                                    ]
                                ]
                            ]
                        ],

                        // Program Unggulan Section
                        [
                            'name' => 'Program Unggulan',
                            'order' => 3,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Program Unggulan',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Program Unggulan',
                                        'subtitle' => 'Program-program terbaik untuk mengembangkan potensi siswa',
                                        'cards' => [
                                            [
                                                'title' => 'ISMUBA (Al-Islam, Kemuhammadiyahan & Bahasa Arab)',
                                                'description' => 'Program unggulan berbasis nilai-nilai Islam dan Kemuhammadiyahan dengan pembelajaran Bahasa Arab intensif',
                                                'image' => 'programs/ismuba.jpg',
                                                'link' => '/ismuba'
                                            ],
                                            [
                                                'title' => 'Program Tahfidz Al-Quran',
                                                'description' => 'Program menghafal Al-Quran dengan metode modern dan bimbingan ustadz berpengalaman',
                                                'image' => 'programs/tahfidz.jpg',
                                                'link' => '/tahfidz'
                                            ],
                                            [
                                                'title' => 'Science & Technology',
                                                'description' => 'Program sains dan teknologi dengan laboratorium modern dan kompetisi olimpiade',
                                                'image' => 'programs/science.jpg',
                                                'link' => '/science-tech'
                                            ],
                                            [
                                                'title' => 'Bahasa Internasional',
                                                'description' => 'Program bahasa Inggris, Arab, dan Mandarin untuk persiapan global',
                                                'image' => 'programs/language.jpg',
                                                'link' => '/language-program'
                                            ],
                                            [
                                                'title' => 'Leadership & Entrepreneurship',
                                                'description' => 'Mengembangkan jiwa kepemimpinan dan kewirausahaan sejak dini',
                                                'image' => 'programs/leadership.jpg',
                                                'link' => '/leadership'
                                            ],
                                            [
                                                'title' => 'Character Building',
                                                'description' => 'Pembentukan karakter unggul dengan nilai-nilai Muhammadiyah',
                                                'image' => 'programs/character.jpg',
                                                'link' => '/character-building'
                                            ]
                                        ]
                                    ],
                                    'settings' => [
                                        'columns' => 3,
                                        'style' => 'card'
                                    ]
                                ]
                            ]
                        ],

                        // Statistics Section
                        [
                            'name' => 'Statistik Prestasi',
                            'order' => 4,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'SMAMITA Statistics',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Prestasi SMAMITA dalam Angka',
                                        'stats' => [
                                            [
                                                'label' => 'Siswa Aktif',
                                                'value' => '1,200+',
                                                'icon' => 'users'
                                            ],
                                            [
                                                'label' => 'Guru & Staff',
                                                'value' => '85+',
                                                'icon' => 'teacher'
                                            ],
                                            [
                                                'label' => 'Prestasi Nasional',
                                                'value' => '150+',
                                                'icon' => 'trophy'
                                            ],
                                            [
                                                'label' => 'Alumni Berprestasi',
                                                'value' => '5,000+',
                                                'icon' => 'graduate'
                                            ]
                                        ]
                                    ],
                                    'settings' => [
                                        'background_color' => '#1565C0',
                                        'text_color' => 'white'
                                    ]
                                ]
                            ]
                        ],

                        // Gallery Section
                        [
                            'name' => 'Gallery',
                            'order' => 5,
                            'blocks' => [
                                [
                                    'type' => 'gallery_teaser',
                                    'name' => 'GALLERY',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'GALLERY',
                                        'subtitle' => 'Dokumentasi kegiatan dan fasilitas SMAMITA',
                                        'images' => [
                                            [
                                                'src' => 'gallery/kegiatan-1.jpg',
                                                'alt' => 'Kegiatan Pembelajaran',
                                                'caption' => 'Pembelajaran Interaktif'
                                            ],
                                            [
                                                'src' => 'gallery/fasilitas-1.jpg',
                                                'alt' => 'Fasilitas Laboratorium',
                                                'caption' => 'Laboratorium Modern'
                                            ],
                                            [
                                                'src' => 'gallery/prestasi-1.jpg',
                                                'alt' => 'Prestasi Siswa',
                                                'caption' => 'Juara Olimpiade'
                                            ],
                                            [
                                                'src' => 'gallery/kegiatan-2.jpg',
                                                'alt' => 'Kegiatan Ekskul',
                                                'caption' => 'Ekstrakurikuler'
                                            ],
                                            [
                                                'src' => 'gallery/fasilitas-2.jpg',
                                                'alt' => 'Perpustakaan',
                                                'caption' => 'Perpustakaan Digital'
                                            ],
                                            [
                                                'src' => 'gallery/kegiatan-3.jpg',
                                                'alt' => 'Event Sekolah',
                                                'caption' => 'Festival Seni'
                                            ]
                                        ],
                                        'view_all_link' => '/gallery'
                                    ]
                                ]
                            ]
                        ],

                        // Testimonial Section
                        [
                            'name' => 'Testimonial',
                            'order' => 6,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'TESTIMONI',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '<section class="py-5 bg-light">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-8 mx-auto text-center mb-5">
                                                        <h2 class="display-5 fw-bold">TESTIMONI</h2>
                                                        <p class="lead">Apa kata mereka tentang SMAMITA</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body">
                                                                <div class="text-warning mb-3">⭐⭐⭐⭐⭐</div>
                                                                <p class="card-text">"SMAMITA memberikan pendidikan terbaik dengan menggabungkan nilai-nilai islami dan akademik yang unggul. Anak saya berkembang sangat baik di sini."</p>
                                                                <div class="mt-3">
                                                                    <strong>Ibu Siti Nurjanah</strong><br>
                                                                    <small class="text-muted">Orang Tua Siswa</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body">
                                                                <div class="text-warning mb-3">⭐⭐⭐⭐⭐</div>
                                                                <p class="card-text">"Fasilitas lengkap, guru berkualitas, dan program ISMUBA yang sangat membantu pembentukan karakter islami. Recommended banget!"</p>
                                                                <div class="mt-3">
                                                                    <strong>Ahmad Fauzan</strong><br>
                                                                    <small class="text-muted">Alumni 2023</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body">
                                                                <div class="text-warning mb-3">⭐⭐⭐⭐⭐</div>
                                                                <p class="card-text">"Program tahfidz dan pembelajaran berbasis teknologi di SMAMITA sangat membantu persiapan masa depan yang lebih baik."</p>
                                                                <div class="mt-3">
                                                                    <strong>Fatimah Az-Zahra</strong><br>
                                                                    <small class="text-muted">Siswa Kelas XII</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>'
                                    ]
                                ]
                            ]
                        ],

                        // CTA Section
                        [
                            'name' => 'Call to Action',
                            'order' => 7,
                            'blocks' => [
                                [
                                    'type' => 'cta_banner',
                                    'name' => 'Dari SMAMITA untuk BANGSA',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Dari SMAMITA untuk BANGSA',
                                        'subtitle' => 'Bersama SMA Muhammadiyah 1 Taman, Membangun Karakter untuk Indonesia',
                                        'description' => 'SMA Muhammadiyah 1 Taman adalah sekolah berbasis islami unggul prestasi. Terletak di Provinsi Jawa Timur, Kabupaten Sidoarjo, Kecamatan Taman. Berbekal keahlian kompetensi dengan menerapkan budaya Islam.',
                                        'button_text' => 'Daftar PPDB 2025',
                                        'button_url' => 'https://registration.smam1ta.sch.id/',
                                        'background_color' => '#1565C0'
                                    ]
                                ]
                            ]
                        ],

                        // Footer Section
                        [
                            'name' => 'Footer',
                            'order' => 8,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Main Footer',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '<footer class="bg-dark text-light py-5">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-4 mb-4">
                                                        <h5>SMA Muhammadiyah 1 Taman</h5>
                                                        <p>Sekolah berbasis islami unggul prestasi dengan motto "The Excellent School" untuk membangun karakter islami generasi Indonesia.</p>
                                                        <div class="social-links">
                                                            <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i> Facebook</a>
                                                            <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i> Twitter</a>
                                                            <a href="https://www.youtube.com/@SMAM1TATV" class="text-light"><i class="fab fa-youtube"></i> Youtube</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 mb-4">
                                                        <h6>Manajemen</h6>
                                                        <ul class="list-unstyled">
                                                            <li><a href="/kurikulum" class="text-light">Kurikulum</a></li>
                                                            <li><a href="/kesiswaan" class="text-light">Kesiswaan</a></li>
                                                            <li><a href="/ismuba" class="text-light">ISMUBA</a></li>
                                                            <li><a href="/humas" class="text-light">Humas</a></li>
                                                            <li><a href="/sarpras" class="text-light">Sarpras</a></li>
                                                            <li><a href="/human-resource" class="text-light">Human Resource</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-2 mb-4">
                                                        <h6>Layanan</h6>
                                                        <ul class="list-unstyled">
                                                            <li><a href="/ppdb" class="text-light">PPDB</a></li>
                                                            <li><a href="/rapor" class="text-light">Rapor</a></li>
                                                            <li><a href="/skl" class="text-light">SKL</a></li>
                                                            <li><a href="/berita" class="text-light">Berita</a></li>
                                                            <li><a href="/pengumuman" class="text-light">Pengumuman</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-2 mb-4">
                                                        <h6>Resource</h6>
                                                        <ul class="list-unstyled">
                                                            <li><a href="/unduh" class="text-light">Unduh</a></li>
                                                            <li><a href="/pusat-bantuan" class="text-light">Pusat Bantuan</a></li>
                                                            <li><a href="/acara" class="text-light">Acara</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-2 mb-4">
                                                        <h6>Kontak</h6>
                                                        <ul class="list-unstyled">
                                                            <li><i class="fas fa-phone"></i> +62 813-1945-7080</li>
                                                            <li><i class="fas fa-envelope"></i> ppdb@smam1ta.sch.id</li>
                                                            <li><i class="fas fa-map-marker-alt"></i> Jl. Raya Ketegan No.35 Taman-Sidoarjo</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <hr class="my-4">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <p class="mb-0">© 2025 SMA Muhammadiyah 1 Taman. All rights reserved.</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <p class="mb-0">Created with ❤️ by School CMS</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </footer>'
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
     * Modern Islamic Template
     */
    private function getModernIslamicTemplate()
    {
                                            [
                                                'title' => 'Berdaya Saing Global',
                                                'description' => 'Mempersiapkan siswa untuk kompetisi di tingkat nasional dan internasional',
                                                'icon' => 'globe',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],

                        // Program Unggulan
                        [
                            'name' => 'Program Unggulan',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Featured Programs',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Program Unggulan',
                                        'subtitle' => 'Program-program terbaik untuk mengembangkan potensi siswa',
                                        'cards' => [
                                            [
                                                'title' => 'ISMUBA (Islam, Muhammadiyah, Bahasa Arab)',
                                                'description' => 'Program khusus penguatan nilai-nilai keislaman dan kemuhammadiyahan',
                                                'icon' => 'mosque',
                                            ],
                                            [
                                                'title' => 'Kelas Unggulan',
                                                'description' => 'Program akselerasi untuk siswa berprestasi dengan kurikulum diperkaya',
                                                'icon' => 'star',
                                            ],
                                            [
                                                'title' => 'English Program',
                                                'description' => 'Program penguasaan bahasa Inggris untuk persiapan global',
                                                'icon' => 'language',
                                            ],
                                            [
                                                'title' => 'Leadership Training',
                                                'description' => 'Pelatihan kepemimpinan dengan nilai-nilai Muhammadiyah',
                                                'icon' => 'users',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],

                        // Struktur Organisasi
                        [
                            'name' => 'Manajemen',
                            'order' => 3,
                            'blocks' => [
                                [
                                    'type' => 'rich-text',
                                    'name' => 'Management Structure',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Struktur Manajemen',
                                        'content' => '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div class="text-center">
                                                <h4 class="font-bold text-lg mb-2">Kurikulum</h4>
                                                <p class="text-gray-600">Pengelolaan kurikulum akademik dan pengembangan pembelajaran</p>
                                            </div>
                                            <div class="text-center">
                                                <h4 class="font-bold text-lg mb-2">Kesiswaan</h4>
                                                <p class="text-gray-600">Pembinaan dan pengembangan karakter siswa</p>
                                            </div>
                                            <div class="text-center">
                                                <h4 class="font-bold text-lg mb-2">ISMUBA</h4>
                                                <p class="text-gray-600">Pendidikan Islam dan Kemuhammadiyahan</p>
                                            </div>
                                        </div>',
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],

                        // Statistics
                        [
                            'name' => 'Prestasi Sekolah',
                            'order' => 4,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'School Achievement',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Dari SMAMITA untuk BANGSA',
                                        'subtitle' => 'Bersama SMA Muhammadiyah 1 Taman, Membangun Karakter untuk Indonesia',
                                        'stats' => [
                                            ['number' => 1200, 'label' => 'Total Siswa', 'icon' => 'users'],
                                            ['number' => 50, 'label' => 'Guru & Staff', 'icon' => 'user-tie'],
                                            ['number' => 25, 'label' => 'Tahun Berdiri', 'suffix' => '+', 'icon' => 'calendar'],
                                            ['number' => 95, 'label' => 'Tingkat Kelulusan', 'suffix' => '%', 'icon' => 'graduation-cap'],
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
     * Modern Islamic Template
     */
    private function getModernIslamicTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMA Modern Islamic',
                    'slug' => 'homepage',
                    'description' => 'Homepage modern dengan nilai-nilai islami',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Modern Islamic Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Islamic Modern Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Modern Islamic Excellence',
                                        'subtitle' => 'Menggabungkan Teknologi Modern dengan Nilai-nilai Islam untuk Menciptakan Generasi Unggul Berakhlak Mulia',
                                        'button_text' => 'Explore Programs',
                                        'button_url' => '/programs',
                                        'background_image' => 'hero/modern-islamic.jpg',
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Character Building',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Islamic Values',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Pembentukan Karakter Islami',
                                        'cards' => [
                                            [
                                                'title' => 'Akhlak Karimah',
                                                'description' => 'Pembentukan akhlak mulia berdasarkan Al-Quran dan Sunnah',
                                                'icon' => 'heart-islamic',
                                            ],
                                            [
                                                'title' => 'Leadership Islam',
                                                'description' => 'Kepemimpinan dengan nilai-nilai Islam untuk masa depan',
                                                'icon' => 'crown-islamic',
                                            ],
                                            [
                                                'title' => 'Global Mindset',
                                                'description' => 'Wawasan global dengan tetap berpegang pada nilai-nilai Islam',
                                                'icon' => 'world-islamic',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Achievement Stats',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'Islamic Achievement',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Excellence in Islamic Education',
                                        'stats' => [
                                            ['number' => 900, 'label' => 'Islamic Students', 'icon' => 'user-islamic'],
                                            ['number' => 40, 'label' => 'Islamic Teachers', 'icon' => 'teacher-islamic'],
                                            ['number' => 100, 'label' => 'Character Programs', 'suffix' => '%', 'icon' => 'program'],
                                            ['number' => 20, 'label' => 'Years Excellence', 'suffix' => '+', 'icon' => 'excellence'],
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
     * Prestasi Unggul Template
     */
    private function getPrestasiUnggulTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Homepage SMA Prestasi Unggul',
                    'slug' => 'homepage',
                    'description' => 'Homepage fokus prestasi dan keunggulan',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        [
                            'name' => 'Achievement Hero',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Prestasi Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Prestasi Unggul',
                                        'subtitle' => 'Membanggakan Prestasi, Mencetak Juara, Mengukir Sejarah Kebanggaan Indonesia di Tingkat Nasional dan Internasional',
                                        'button_text' => 'Lihat Prestasi',
                                        'button_url' => '/prestasi',
                                        'background_image' => 'hero/prestasi-unggul.jpg',
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Achievement Gallery',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'card-grid',
                                    'name' => 'Achievement Types',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Kategori Prestasi',
                                        'cards' => [
                                            [
                                                'title' => 'Akademik',
                                                'description' => 'Olimpiade Sains, Matematika, dan kompetisi akademik lainnya',
                                                'icon' => 'academic-trophy',
                                            ],
                                            [
                                                'title' => 'Olahraga',
                                                'description' => 'Prestasi di berbagai cabang olahraga tingkat nasional',
                                                'icon' => 'sports-trophy',
                                            ],
                                            [
                                                'title' => 'Seni & Budaya',
                                                'description' => 'Juara kompetisi seni, musik, dan budaya nusantara',
                                                'icon' => 'art-trophy',
                                            ],
                                        ],
                                    ],
                                    'active' => true,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Achievement Numbers',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'Prestasi Statistics',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Record Prestasi Gemilang',
                                        'stats' => [
                                            ['number' => 150, 'label' => 'Total Prestasi', 'suffix' => '+', 'icon' => 'trophy'],
                                            ['number' => 50, 'label' => 'Juara Nasional', 'suffix' => '+', 'icon' => 'medal-gold'],
                                            ['number' => 15, 'label' => 'Prestasi Internasional', 'suffix' => '+', 'icon' => 'world-trophy'],
                                            ['number' => 500, 'label' => 'Siswa Berprestasi', 'suffix' => '+', 'icon' => 'student-star'],
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
