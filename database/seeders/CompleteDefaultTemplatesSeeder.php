<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;

class CompleteDefaultTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get default category
        $defaultCategory = TemplateCategory::where('slug', 'umum')->first();
        if (!$defaultCategory) {
            $defaultCategory = TemplateCategory::create([
                'name' => 'Umum',
                'slug' => 'umum',
                'description' => 'Template umum untuk semua jenis sekolah',
                'color' => '#3B82F6',
                'icon' => 'school-icon',
                'sort_order' => 1,
            ]);
        }

        // Create complete default templates
        $templates = [
            [
                'name' => 'SMA Modern Complete',
                'slug' => 'sma-modern-complete',
                'description' => 'Template lengkap untuk SMA modern dengan semua konten siap pakai',
                'category_id' => $defaultCategory->id,
                'preview_image' => 'templates/previews/sma-modern-complete.jpg',
                'template_data' => $this->getCompleteDefaultTemplate(),
                'author' => 'School CMS Team',
                'version' => '2.0.0',
                'features' => [
                    'Complete Content Ready',
                    'Modern Design',
                    'All Navigation Menus',
                    'Contact Information',
                    'Social Media Links',
                    'SEO Optimized',
                    'Mobile Responsive',
                    'Performance Optimized'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#3B82F6', 'secondary' => '#64748B', 'accent' => '#F59E0B'],
                ],
                'featured' => true,
                'premium' => false,
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

        $this->command->info('Created complete default SMA template!');
    }

    /**
     * Complete Default Template
     */
    private function getCompleteDefaultTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'SMA Modern Homepage',
                    'slug' => 'homepage',
                    'description' => 'Homepage lengkap untuk SMA modern',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        // Modern Navigation
                        [
                            'name' => 'Modern Navigation',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Main Navigation',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <header class="sticky-top bg-white shadow-sm">
                                            <div class="container">
                                                <nav class="navbar navbar-expand-lg navbar-light">
                                                    <a class="navbar-brand d-flex align-items-center" href="/">
                                                        <img src="/images/logo-sekolah.png" alt="SMA Modern" height="45" class="me-2">
                                                        <div>
                                                            <div class="fw-bold text-primary">SMA MODERN</div>
                                                            <small class="text-muted">Excellence in Education</small>
                                                        </div>
                                                    </a>

                                                    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                                                        <span class="navbar-toggler-icon"></span>
                                                    </button>

                                                    <div class="collapse navbar-collapse" id="navbarNav">
                                                        <ul class="navbar-nav ms-auto">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" href="/">Beranda</a>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Tentang Sekolah
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/profil-sekolah">Profil Sekolah</a></li>
                                                                    <li><a class="dropdown-item" href="/visi-misi">Visi & Misi</a></li>
                                                                    <li><a class="dropdown-item" href="/sejarah">Sejarah</a></li>
                                                                    <li><a class="dropdown-item" href="/struktur-organisasi">Struktur Organisasi</a></li>
                                                                    <li><a class="dropdown-item" href="/fasilitas">Fasilitas</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Akademik
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/kurikulum">Kurikulum</a></li>
                                                                    <li><a class="dropdown-item" href="/jadwal-pelajaran">Jadwal Pelajaran</a></li>
                                                                    <li><a class="dropdown-item" href="/program-studi">Program Studi</a></li>
                                                                    <li><a class="dropdown-item" href="/evaluasi">Evaluasi & Ujian</a></li>
                                                                    <li><a class="dropdown-item" href="/kalender-akademik">Kalender Akademik</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Kesiswaan
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/ekstrakurikuler">Ekstrakurikuler</a></li>
                                                                    <li><a class="dropdown-item" href="/osis">OSIS</a></li>
                                                                    <li><a class="dropdown-item" href="/prestasi-siswa">Prestasi Siswa</a></li>
                                                                    <li><a class="dropdown-item" href="/beasiswa">Beasiswa</a></li>
                                                                    <li><a class="dropdown-item" href="/alumni">Alumni</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Informasi
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/berita">Berita</a></li>
                                                                    <li><a class="dropdown-item" href="/pengumuman">Pengumuman</a></li>
                                                                    <li><a class="dropdown-item" href="/agenda">Agenda</a></li>
                                                                    <li><a class="dropdown-item" href="/gallery">Gallery</a></li>
                                                                    <li><a class="dropdown-item" href="/download">Download</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="/kontak">Kontak</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="btn btn-primary ms-2" href="/ppdb">PPDB Online</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </nav>
                                            </div>
                                        </header>'
                                    ]
                                ]
                            ]
                        ],

                        // Modern Hero
                        [
                            'name' => 'Modern Hero',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'School Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Modern - Excellence in Education',
                                        'subtitle' => 'Membangun Generasi Cerdas, Berkarakter, dan Siap Menghadapi Masa Depan',
                                        'description' => 'Dengan kurikulum terdepan, fasilitas modern, dan tenaga pengajar berkualitas, kami berkomitmen mencetak lulusan yang unggul dalam akademik dan berkarakter kuat.',
                                        'button_text' => 'Daftar Sekarang',
                                        'button_url' => '/ppdb',
                                        'secondary_button_text' => 'Lihat Fasilitas',
                                        'secondary_button_url' => '/fasilitas',
                                        'background_image' => 'hero/sma-modern-hero.jpg'
                                    ],
                                    'settings' => [
                                        'overlay' => true,
                                        'text_color' => 'white'
                                    ]
                                ]
                            ]
                        ],

                        // School Excellence
                        [
                            'name' => 'Keunggulan Sekolah',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'School Features',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold text-primary">Keunggulan SMA Modern</h2>
                                                    <p class="lead">Mengapa memilih SMA Modern untuk masa depan yang cerah</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-primary mb-3" style="font-size: 3rem;">🎓</div>
                                                                <h5 class="card-title fw-bold">Akademik Unggul</h5>
                                                                <p class="card-text">Kurikulum terdepan dengan metode pembelajaran inovatif dan terintegrasi teknologi</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-primary mb-3" style="font-size: 3rem;">👥</div>
                                                                <h5 class="card-title fw-bold">Guru Berkualitas</h5>
                                                                <p class="card-text">Tenaga pengajar profesional dengan kualifikasi dan pengalaman terbaik</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-primary mb-3" style="font-size: 3rem;">🏢</div>
                                                                <h5 class="card-title fw-bold">Fasilitas Modern</h5>
                                                                <p class="card-text">Laboratorium lengkap, perpustakaan digital, dan sarana olahraga yang memadai</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-primary mb-3" style="font-size: 3rem;">🏆</div>
                                                                <h5 class="card-title fw-bold">Prestasi Gemilang</h5>
                                                                <p class="card-text">Track record prestasi akademik dan non-akademik tingkat nasional dan internasional</p>
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

                        // Program Unggulan
                        [
                            'name' => 'Program Unggulan',
                            'order' => 3,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Featured Programs',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5 bg-light">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold">Program Unggulan</h2>
                                                    <p class="lead">Program-program terbaik untuk mengembangkan potensi siswa secara maksimal</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/science.jpg" class="card-img-top" alt="Science Program">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Program Sains Unggulan</h5>
                                                                <p class="card-text">Program khusus untuk siswa berbakat di bidang sains dengan laboratorium modern dan olimpiade sains.</p>
                                                                <div class="mb-2">
                                                                    <span class="badge bg-primary me-1">Matematika</span>
                                                                    <span class="badge bg-primary me-1">Fisika</span>
                                                                    <span class="badge bg-primary">Kimia</span>
                                                                </div>
                                                                <a href="/program-sains" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/language.jpg" class="card-img-top" alt="Language Program">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Program Bahasa Internasional</h5>
                                                                <p class="card-text">Pembelajaran bahasa Inggris, Mandarin, dan Jepang dengan native speaker dan sertifikasi internasional.</p>
                                                                <div class="mb-2">
                                                                    <span class="badge bg-success me-1">English</span>
                                                                    <span class="badge bg-success me-1">Mandarin</span>
                                                                    <span class="badge bg-success">Japanese</span>
                                                                </div>
                                                                <a href="/program-bahasa" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/technology.jpg" class="card-img-top" alt="Technology Program">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Program Teknologi & Coding</h5>
                                                                <p class="card-text">Pembelajaran programming, robotika, dan AI untuk mempersiapkan siswa di era digital.</p>
                                                                <div class="mb-2">
                                                                    <span class="badge bg-warning me-1">Programming</span>
                                                                    <span class="badge bg-warning me-1">Robotika</span>
                                                                    <span class="badge bg-warning">AI</span>
                                                                </div>
                                                                <a href="/program-teknologi" class="btn btn-primary">Selengkapnya</a>
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

                        // Statistics
                        [
                            'name' => 'Statistik',
                            'order' => 4,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'School Statistics',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Modern dalam Angka',
                                        'stats' => [
                                            [
                                                'label' => 'Siswa Aktif',
                                                'value' => '1,500+',
                                                'icon' => 'users'
                                            ],
                                            [
                                                'label' => 'Guru & Staff',
                                                'value' => '95+',
                                                'icon' => 'teacher'
                                            ],
                                            [
                                                'label' => 'Prestasi Nasional',
                                                'value' => '200+',
                                                'icon' => 'trophy'
                                            ],
                                            [
                                                'label' => 'Alumni Sukses',
                                                'value' => '8,000+',
                                                'icon' => 'graduate'
                                            ]
                                        ]
                                    ],
                                    'settings' => [
                                        'background_color' => '#3B82F6',
                                        'text_color' => 'white'
                                    ]
                                ]
                            ]
                        ],

                        // Berita Terbaru
                        [
                            'name' => 'Berita Terbaru',
                            'order' => 5,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Latest News',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold">Berita Terbaru</h2>
                                                    <p class="lead">Informasi terkini seputar kegiatan dan prestasi sekolah</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/news/news-1.jpg" class="card-img-top" alt="News 1">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="badge bg-primary">Prestasi</span>
                                                                    <small class="text-muted">15 Jan 2025</small>
                                                                </div>
                                                                <h5 class="card-title">Juara 1 Olimpiade Sains Nasional 2025</h5>
                                                                <p class="card-text">Siswa SMA Modern meraih juara 1 pada Olimpiade Sains Nasional bidang Fisika tingkat nasional.</p>
                                                                <a href="/berita/juara-osn-2025" class="btn btn-outline-primary">Baca Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/news/news-2.jpg" class="card-img-top" alt="News 2">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="badge bg-success">Kegiatan</span>
                                                                    <small class="text-muted">12 Jan 2025</small>
                                                                </div>
                                                                <h5 class="card-title">Launching Program Digital Learning</h5>
                                                                <p class="card-text">SMA Modern meluncurkan program pembelajaran digital dengan platform LMS terbaru untuk mendukung hybrid learning.</p>
                                                                <a href="/berita/digital-learning-2025" class="btn btn-outline-primary">Baca Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/news/news-3.jpg" class="card-img-top" alt="News 3">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="badge bg-warning">Pengumuman</span>
                                                                    <small class="text-muted">10 Jan 2025</small>
                                                                </div>
                                                                <h5 class="card-title">Pembukaan PPDB 2025/2026</h5>
                                                                <p class="card-text">Penerimaan Peserta Didik Baru tahun ajaran 2025/2026 telah dibuka dengan sistem online terintegrasi.</p>
                                                                <a href="/berita/ppdb-2025" class="btn btn-outline-primary">Baca Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-4">
                                                    <a href="/berita" class="btn btn-primary">Lihat Semua Berita</a>
                                                </div>
                                            </div>
                                        </section>'
                                    ]
                                ]
                            ]
                        ],

                        // CTA
                        [
                            'name' => 'CTA',
                            'order' => 6,
                            'blocks' => [
                                [
                                    'type' => 'cta_banner',
                                    'name' => 'PPDB CTA',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Bergabunglah dengan SMA Modern',
                                        'subtitle' => 'Masa Depan Cerah Dimulai dari Sekarang',
                                        'description' => 'Daftarkan diri Anda di SMA Modern dan rasakan pengalaman belajar terbaik dengan fasilitas modern, guru berkualitas, dan program unggulan yang akan membawa Anda menuju kesuksesan.',
                                        'button_text' => 'Daftar PPDB 2025',
                                        'button_url' => '/ppdb',
                                        'background_color' => '#3B82F6'
                                    ]
                                ]
                            ]
                        ],

                        // Footer
                        [
                            'name' => 'Footer',
                            'order' => 7,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Main Footer',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <footer class="bg-dark text-light py-5">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-4 mb-4">
                                                        <h5 class="mb-3">SMA Modern</h5>
                                                        <p>Sekolah Menengah Atas unggulan yang berkomitmen mencetak generasi cerdas, berkarakter, dan siap menghadapi tantangan masa depan dengan pendidikan berkualitas tinggi.</p>
                                                        <div class="social-links mt-3">
                                                            <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i> Facebook</a>
                                                            <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i> Instagram</a>
                                                            <a href="#" class="text-light"><i class="fab fa-youtube"></i> YouTube</a>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Tentang</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/profil-sekolah" class="text-light">Profil Sekolah</a></li>
                                                            <li class="mb-2"><a href="/visi-misi" class="text-light">Visi & Misi</a></li>
                                                            <li class="mb-2"><a href="/sejarah" class="text-light">Sejarah</a></li>
                                                            <li class="mb-2"><a href="/struktur-organisasi" class="text-light">Struktur Organisasi</a></li>
                                                            <li class="mb-2"><a href="/fasilitas" class="text-light">Fasilitas</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Akademik</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/kurikulum" class="text-light">Kurikulum</a></li>
                                                            <li class="mb-2"><a href="/jadwal-pelajaran" class="text-light">Jadwal Pelajaran</a></li>
                                                            <li class="mb-2"><a href="/program-studi" class="text-light">Program Studi</a></li>
                                                            <li class="mb-2"><a href="/evaluasi" class="text-light">Evaluasi</a></li>
                                                            <li class="mb-2"><a href="/kalender-akademik" class="text-light">Kalender</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Informasi</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/berita" class="text-light">Berita</a></li>
                                                            <li class="mb-2"><a href="/pengumuman" class="text-light">Pengumuman</a></li>
                                                            <li class="mb-2"><a href="/agenda" class="text-light">Agenda</a></li>
                                                            <li class="mb-2"><a href="/gallery" class="text-light">Gallery</a></li>
                                                            <li class="mb-2"><a href="/download" class="text-light">Download</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Kontak</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2">
                                                                <i class="fas fa-phone me-2"></i>
                                                                <a href="tel:+62211234567" class="text-light">(021) 123-4567</a>
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="fas fa-envelope me-2"></i>
                                                                <a href="mailto:info@smamodern.sch.id" class="text-light">info@smamodern.sch.id</a>
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                                Jl. Pendidikan No. 123<br>
                                                                Jakarta Pusat 10430
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="fas fa-clock me-2"></i>
                                                                Sen-Jum: 07:00-16:00 WIB
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <hr class="my-4">

                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <p class="mb-0">&copy; 2025 SMA Modern. Hak cipta dilindungi undang-undang.</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <p class="mb-0">Dibuat dengan ❤️ oleh School CMS</p>
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
}
