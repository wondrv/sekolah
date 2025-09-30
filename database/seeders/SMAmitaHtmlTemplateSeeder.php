<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;
use App\Models\UserTemplate;
use App\Models\User;

class SMAmitaHtmlTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category
        $smpSmaCategory = TemplateCategory::where('slug', 'sekolah-menengah')->first();
        if (!$smpSmaCategory) {
            $smpSmaCategory = TemplateCategory::create([
                'name' => 'Sekolah Menengah',
                'slug' => 'sekolah-menengah',
                'description' => 'Template untuk SMP dan SMA'
            ]);
        }

        // Create template gallery entry
        $templateGallery = TemplateGallery::updateOrCreate(
            ['slug' => 'smamita-html-template'],
            [
                'name' => 'SMAMITA HTML Template',
                'slug' => 'smamita-html-template',
                'description' => 'Template berdasarkan design HTML SMAMITA yang elegan dan modern',
                'category_id' => $smpSmaCategory->id,
                'preview_image' => 'templates/previews/smamita-html.jpg',
                'template_data' => $this->getTemplateData(),
                'author' => 'SMAMITA Team',
                'version' => '1.0.0',
                'features' => [
                    'Modern HTML5 Design',
                    'Responsive Layout',
                    'Custom CSS Styling',
                    'Interactive Navigation',
                    'News Section',
                    'Program Showcase',
                    'Contact Information',
                    'Mobile Optimized'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#005A9C', 'secondary' => '#FDB813', 'accent' => '#333'],
                ],
                'featured' => true,
                'premium' => false,
                'rating' => 5.0,
                'downloads' => 0,
            ]
        );

        // Create UserTemplate for admin if exists
        $adminUser = User::where('email', 'admin@school.local')->first();

        if ($adminUser) {
            UserTemplate::updateOrCreate(
                ['slug' => 'smamita-html-active'],
                [
                    'user_id' => $adminUser->id,
                    'name' => 'SMAMITA HTML Active',
                    'slug' => 'smamita-html-active',
                    'description' => 'Active SMAMITA template ready to use',
                    'template_data' => $this->getTemplateData(),
                    'gallery_template_id' => $templateGallery->id,
                    'source' => 'gallery',
                    'customizations' => [
                        'css' => $this->getCustomCSS(),
                        'javascript' => $this->getCustomJS()
                    ],
                ]
            );
        }

        $this->command->info('SMAMITA HTML Template created successfully!');
        $this->command->info('You can now activate it from My Templates section.');
    }

    private function getTemplateData()
    {
        return [
            'templates' => [
                [
                    'name' => 'SMAMITA Homepage',
                    'slug' => 'homepage',
                    'description' => 'Homepage dengan design HTML SMAMITA yang elegan',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        // Header Navigation
                        [
                            'name' => 'Header Navigation',
                            'order' => 0,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Main Navigation',
                                    'order' => 0,
                                    'content' => [ 'html' => $this->getNavigationHTML() ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Hero Section
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Main Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Muhammadiyah 1 Taman',
                                        'subtitle' => 'Sekolah Para Pemimpin & Entrepreneur Muda Berakhlak Mulia',
                                        'background_image' => 'https://smam1ta.sch.id/wp-content/uploads/2023/10/gedung-smamita-scaled.jpg',
                                        'text_align' => 'text-left',
                                        'background_color' => 'bg-gradient-to-r from-blue-900 to-blue-700',
                                        'buttons' => [
                                            ['text' => 'PPDB 2025', 'url' => '/ppdb', 'style' => 'secondary'],
                                            ['text' => 'Jelajahi Sekolah', 'url' => '#about', 'style' => 'primary']
                                        ]
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Key Stats Section
                        [
                            'name' => 'Key Statistics',
                            'order' => 2,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'School Stats',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Pencapaian SMAMITA',
                                        'stats' => [
                                            ['number' => '40+', 'label' => 'Tahun Pengalaman'],
                                            ['number' => '1200+', 'label' => 'Siswa Aktif'],
                                            ['number' => '95%', 'label' => 'Kelulusan'],
                                            ['number' => '150+', 'label' => 'Prestasi / Tahun'],
                                        ],
                                        'background_color' => 'bg-blue-900'
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // About Section
                        [
                            'name' => 'About Section',
                            'order' => 3,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'About Content',
                                    'order' => 0,
                                    'content' => [ 'html' => $this->getAboutHTML() ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // News Section
                        [
                            'name' => 'News Section',
                            'order' => 4,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'News Content',
                                    'order' => 0,
                                    'content' => [ 'html' => $this->getNewsHTML() ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Program Highlight Section (Card Grid)
                        [
                            'name' => 'Program Section',
                            'order' => 5,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Program Grid',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Program Unggulan',
                                        'subtitle' => 'Beragam jalur pengembangan potensi siswa menuju prestasi dan karakter unggul.',
                                        'background_color' => 'bg-gray-50',
                                        'columns' => 4,
                                        'cards' => [
                                            ['title' => 'Kelas Internasional', 'description' => 'Pembelajaran bilingual & kolaborasi global.', 'image' => 'https://smam1ta.sch.id/wp-content/uploads/2023/10/gedung-smamita-scaled.jpg'],
                                            ['title' => 'Entrepreneur', 'description' => 'Membentuk wirausaha muda visioner.', 'image' => 'https://smam1ta.sch.id/wp-content/uploads/2024/07/Penutupan-FORTASI-2024.jpeg'],
                                            ['title' => 'Olimpiade Sains', 'description' => 'Pembinaan intensif kompetisi akademik.', 'image' => 'https://smam1ta.sch.id/wp-content/uploads/2024/06/IMG_20240614_073321-scaled.jpg'],
                                            ['title' => 'Tahfidz Qur\'an', 'description' => 'Program hafalan terstruktur & pembinaan karakter Qur\'ani.', 'image' => 'https://smam1ta.sch.id/wp-content/uploads/2024/05/purnawiyata.jpg'],
                                        ]
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // CTA Banner Section
                        [
                            'name' => 'PPDB CTA Section',
                            'order' => 6,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'cta_banner',
                                    'name' => 'PPDB CTA',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Penerimaan Peserta Didik Baru 2025',
                                        'subtitle' => 'Daftar sekarang dan bergabung bersama komunitas pembelajar unggul SMAMITA.',
                                        'button_text' => 'Daftar PPDB',
                                        'button_url' => '/ppdb',
                                        'background_style' => 'gradient-blue'
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Events Teaser Section
                        [
                            'name' => 'Events Section',
                            'order' => 7,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'events_teaser',
                                    'name' => 'Upcoming Events',
                                    'order' => 0,
                                    'content' => [ 'title' => 'Agenda Kegiatan', 'limit' => 3 ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Latest Posts Teaser Section
                        [
                            'name' => 'Posts Section',
                            'order' => 8,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'posts_teaser',
                                    'name' => 'Latest Posts',
                                    'order' => 0,
                                    'content' => [ 'title' => 'Artikel & Berita Terbaru', 'limit' => 3, 'show_read_more' => true ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Footer
                        [
                            'name' => 'Footer',
                            'order' => 9,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Footer Content',
                                    'order' => 0,
                                    'content' => [ 'html' => $this->getFooterHTML() ],
                                    'active' => true
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }

    private function getNavigationHTML()
    {
        return '
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm" style="z-index: 1000;">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="https://smam1ta.sch.id/wp-content/uploads/2023/07/logo-smamita-2023-300x296.png" alt="Logo SMAMITA" height="50" class="me-2">
                    <span class="fw-bold text-primary fs-4">SMAMITA</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link fw-semibold" href="#hero">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="#about">Tentang Kami</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="#news">Berita</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="#program">Program</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="#footer">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </nav>';
    }

    private function getAboutHTML()
    {
        return '
        <div class="container py-5" id="about">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold text-primary mb-3">Tentang <span class="text-dark">Sekolah</span></h2>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                    <img src="https://smam1ta.sch.id/wp-content/uploads/2024/02/kepsek-smamita-1024x1024.png" alt="Kepala Sekolah" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h3 class="h2 fw-bold mb-3">Sambutan Kepala Sekolah</h3>
                    <p class="mb-3"><em>Assalamu\'alaikum Warahmatullahi Wabarakatuh.</em></p>
                    <p class="mb-3">Selamat datang di website resmi SMA Muhammadiyah 1 Taman, Sidoarjo. Kami bangga mempersembahkan sebuah platform digital yang menjadi jendela informasi bagi seluruh civitas akademika dan masyarakat luas.</p>
                    <p class="mb-3">Sekolah kami, yang dikenal dengan sebutan SMAMITA, memiliki visi untuk menjadi sekolah unggul yang melahirkan generasi pemimpin dan entrepreneur muda berakhlak mulia.</p>
                    <p class="mb-4">Kami mengintegrasikan kurikulum nasional dengan program-program unggulan berbasis kewirausahaan, teknologi, dan keislaman.</p>
                    <a href="#" class="btn btn-primary btn-lg">Selengkapnya</a>
                </div>
            </div>
        </div>';
    }

    private function getNewsHTML()
    {
        return '
        <div class="py-5 bg-light" id="news">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8 text-center">
                        <h2 class="display-5 fw-bold text-primary mb-3">Berita <span class="text-dark">Terkini</span></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="https://smam1ta.sch.id/wp-content/uploads/2024/07/Penutupan-FORTASI-2024.jpeg" class="card-img-top" alt="FORTASI 2024" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <small class="text-muted">30 Juli 2024</small>
                                <h5 class="card-title">SMAMITA Resmi Tutup FORTASI 2024 dengan Semangat Kebersamaan</h5>
                                <p class="card-text">Kegiatan FORTASI 2024 ditutup dengan meriah, meninggalkan kesan mendalam bagi seluruh siswa baru...</p>
                                <a href="#" class="text-primary fw-semibold">Baca Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="https://smam1ta.sch.id/wp-content/uploads/2024/06/IMG_20240614_073321-scaled.jpg" class="card-img-top" alt="Masta PM" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <small class="text-muted">15 Juni 2024</small>
                                <h5 class="card-title">Masta PM dan Hizbul Wathan: Kuatkan Ideologi Kader Muhammadiyah</h5>
                                <p class="card-text">Program penguatan ideologi bagi kader Muhammadiyah melalui kegiatan Masta PM dan Hizbul Wathan sukses...</p>
                                <a href="#" class="text-primary fw-semibold">Baca Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="https://smam1ta.sch.id/wp-content/uploads/2024/05/purnawiyata.jpg" class="card-img-top" alt="Purnawiyata" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <small class="text-muted">28 Mei 2024</small>
                                <h5 class="card-title">Purnawiyata ke-43 SMAMITA: Wujudkan Generasi Unggul dan Berkarakter</h5>
                                <p class="card-text">SMAMITA menggelar acara purnawiyata angkatan ke-43 dengan khidmat dan penuh harapan untuk masa depan...</p>
                                <a href="#" class="text-primary fw-semibold">Baca Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    private function getProgramHTML()
    {
        return '
        <div class="container py-5" id="program">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold text-primary mb-3">Program <span class="text-dark">Unggulan</span></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="fas fa-rocket fa-3x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Kelas Internasional</h5>
                            <p class="card-text">Program kelas dengan kurikulum terintegrasi untuk mempersiapkan siswa bersaing di kancah global.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="fas fa-lightbulb fa-3x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Kelas Entrepreneur</h5>
                            <p class="card-text">Mencetak wirausahawan muda yang kreatif, inovatif, dan mandiri sejak dini melalui praktek bisnis nyata.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="fas fa-atom fa-3x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Kelas Olimpiade</h5>
                            <p class="card-text">Pembinaan intensif bagi siswa berprestasi untuk mengikuti berbagai kompetisi sains tingkat nasional dan internasional.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="fas fa-book-quran fa-3x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Tahfidz Al-Qur\'an</h5>
                            <p class="card-text">Program menghafal Al-Qur\'an yang terstruktur untuk membentuk generasi Qur\'ani yang berakhlak mulia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    private function getFooterHTML()
    {
        return '
        <footer class="bg-dark text-white py-5" id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <h5 class="text-warning mb-3">Tentang SMAMITA</h5>
                        <p>SMA Muhammadiyah 1 Taman (SMAMITA) adalah sekolah menengah atas swasta yang berlokasi di Sidoarjo, Jawa Timur, berkomitmen mencetak generasi unggul.</p>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <h5 class="text-warning mb-3">Tautan Cepat</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#hero" class="text-light text-decoration-none">Beranda</a></li>
                            <li class="mb-2"><a href="#about" class="text-light text-decoration-none">Tentang Kami</a></li>
                            <li class="mb-2"><a href="#news" class="text-light text-decoration-none">Berita</a></li>
                            <li class="mb-2"><a href="/ppdb" class="text-light text-decoration-none">PPDB Online</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <h5 class="text-warning mb-3">Hubungi Kami</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Jl. Raya Ketegan No. 35, Taman, Sidoarjo</li>
                            <li class="mb-2"><i class="fas fa-phone me-2"></i> (031) 7881538</li>
                            <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@smam1ta.sch.id</li>
                        </ul>
                    </div>
                </div>
                <hr class="my-4">
                <div class="text-center">
                    <p class="mb-0">&copy; 2024 SMA Muhammadiyah 1 Taman. All Rights Reserved.</p>
                </div>
            </div>
        </footer>';
    }

    private function getCustomCSS()
    {
        return '
        :root {
            --primary-color: #005A9C;
            --secondary-color: #FDB813;
            --dark-color: #333;
            --light-color: #f4f4f4;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #004080;
            border-color: #004080;
        }

        .text-warning {
            color: var(--secondary-color) !important;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }

        .card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }

        body {
            padding-top: 76px;
        }
        ';
    }

    private function getCustomJS()
    {
        return '
        // Smooth scrolling for navigation links
        document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
            anchor.addEventListener(\'click\', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute(\'href\')).scrollIntoView({
                    behavior: \'smooth\'
                });
            });
        });
        ';
    }
}
