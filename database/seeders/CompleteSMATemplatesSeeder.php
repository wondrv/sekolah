<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;

class CompleteSMATemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $pesantrenCategory = TemplateCategory::where('slug', 'pesantren')->first();
        $smpSmaCategory = TemplateCategory::where('slug', 'sekolah-menengah')->first();

        // Create templates with complete content
        $templates = [
            // Template 1: SMA Muhammadiyah Complete
            [
                'name' => 'SMA Muhammadiyah Complete',
                'slug' => 'sma-muhammadiyah-complete',
                'description' => 'Template lengkap berdasarkan SMA Muhammadiyah 1 Taman dengan semua konten siap pakai',
                'category_id' => $pesantrenCategory->id,
                'preview_image' => 'templates/previews/sma-muhammadiyah-complete.jpg',
                'template_data' => $this->getCompleteTemplate(),
                'author' => 'School CMS Team',
                'version' => '2.0.0',
                'features' => [
                    'Complete Navigation Menu',
                    'Ready-to-use Content',
                    'ISMUBA Integration',
                    'PPDB System',
                    'Gallery & Testimonials',
                    'Contact Information',
                    'Social Media Links',
                    'SEO Optimized'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#1565C0', 'secondary' => '#0D47A1', 'accent' => '#FF9800'],
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

        $this->command->info('Created complete SMA template with ready-to-use content!');
    }

    /**
     * Complete Template with all sections
     */
    private function getCompleteTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'SMA Muhammadiyah Homepage',
                    'slug' => 'homepage',
                    'description' => 'Homepage lengkap dengan navigasi, konten, dan footer',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        // Section 1: Header & Navigation
                        [
                            'name' => 'Header Navigation',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Main Navigation',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <header class="sticky-top bg-white shadow-sm">
                                            <div class="container-fluid">
                                                <nav class="navbar navbar-expand-lg navbar-light">
                                                    <a class="navbar-brand" href="/">
                                                        <img src="/images/logo-smam1ta.png" alt="SMA Muhammadiyah 1 Taman" height="45" class="me-2">
                                                        <span class="fw-bold text-primary">SMAMITA</span>
                                                    </a>

                                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                                                        <span class="navbar-toggler-icon"></span>
                                                    </button>

                                                    <div class="collapse navbar-collapse" id="navbarNav">
                                                        <ul class="navbar-nav ms-auto">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" href="/">Home</a>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Tentang Kami
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/profil-sekolah">Profil Sekolah</a></li>
                                                                    <li><a class="dropdown-item" href="/visi-misi">Visi & Misi</a></li>
                                                                    <li><a class="dropdown-item" href="/struktur-organisasi">Struktur Organisasi</a></li>
                                                                    <li><a class="dropdown-item" href="/sejarah">Sejarah</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Manajemen
                                                                </a>
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
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Layanan
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/ppdb">PPDB</a></li>
                                                                    <li><a class="dropdown-item" href="/rapor">Rapor</a></li>
                                                                    <li><a class="dropdown-item" href="/skl">SKL</a></li>
                                                                    <li><a class="dropdown-item" href="/berita">Berita</a></li>
                                                                    <li><a class="dropdown-item" href="/pengumuman">Pengumuman</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                    Resource
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/unduh">Unduh</a></li>
                                                                    <li><a class="dropdown-item" href="/pusat-bantuan">Pusat Bantuan</a></li>
                                                                    <li><a class="dropdown-item" href="/acara">Acara</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="/fasilitas">Fasilitas</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="/kontak">Kontak</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="btn btn-primary ms-2" href="https://registration.smam1ta.sch.id/" target="_blank">Daftar PPDB</a>
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

                        // Section 2: Hero
                        [
                            'name' => 'Hero Section',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Main Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'SMA Muhammadiyah 1 Taman',
                                        'subtitle' => 'The Excellent School',
                                        'description' => 'Sholeh Dalam Perilaku, Unggul Dalam Mutu dan Berdaya Saing Global. Bersama SMAMITA, Membangun Karakter untuk Indonesia',
                                        'button_text' => 'Daftar Sekarang',
                                        'button_url' => 'https://registration.smam1ta.sch.id/',
                                        'secondary_button_text' => 'Lihat Fasilitas',
                                        'secondary_button_url' => '/fasilitas',
                                        'background_image' => 'hero/smam1ta-hero.jpg'
                                    ],
                                    'settings' => [
                                        'overlay' => true,
                                        'text_color' => 'white'
                                    ]
                                ]
                            ]
                        ],

                        // Section 3: Core Values
                        [
                            'name' => 'Core Values',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Core Values Section',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold text-primary">CORE VALUE</h2>
                                                    <p class="lead">Nilai-nilai Inti yang Menjadi Fondasi Pendidikan SMAMITA</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-primary mb-3" style="font-size: 3rem;">🕌</div>
                                                                <h5 class="card-title fw-bold">RELIGIUS</h5>
                                                                <p class="card-text">Mengembangkan karakter islami dan spiritual yang kuat berdasarkan Al-Quran dan Sunnah</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-warning mb-3" style="font-size: 3rem;">🏆</div>
                                                                <h5 class="card-title fw-bold">EXCELLENCE</h5>
                                                                <p class="card-text">Mencapai keunggulan dalam akademik, non-akademik, dan pengembangan diri</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-success mb-3" style="font-size: 3rem;">💡</div>
                                                                <h5 class="card-title fw-bold">INNOVATIVE</h5>
                                                                <p class="card-text">Mengintegrasikan teknologi dan metode pembelajaran inovatif dalam pendidikan</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-info mb-3" style="font-size: 3rem;">🌍</div>
                                                                <h5 class="card-title fw-bold">GLOBAL MINDSET</h5>
                                                                <p class="card-text">Mempersiapkan siswa menghadapi tantangan dan kompetisi di era global</p>
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

                        // Section 4: Program Unggulan
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
                                                    <p class="lead">Program-program terbaik untuk mengembangkan potensi siswa</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/ismuba.jpg" class="card-img-top" alt="ISMUBA">
                                                            <div class="card-body">
                                                                <h5 class="card-title">ISMUBA</h5>
                                                                <h6 class="card-subtitle mb-2 text-muted">Al-Islam, Kemuhammadiyahan & Bahasa Arab</h6>
                                                                <p class="card-text">Program unggulan berbasis nilai-nilai Islam dan Kemuhammadiyahan dengan pembelajaran Bahasa Arab intensif</p>
                                                                <a href="/ismuba" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/tahfidz.jpg" class="card-img-top" alt="Tahfidz">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Program Tahfidz Al-Quran</h5>
                                                                <p class="card-text">Program menghafal Al-Quran dengan metode modern dan bimbingan ustadz berpengalaman</p>
                                                                <a href="/tahfidz" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/science.jpg" class="card-img-top" alt="Science & Tech">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Science & Technology</h5>
                                                                <p class="card-text">Program sains dan teknologi dengan laboratorium modern dan kompetisi olimpiade</p>
                                                                <a href="/science-tech" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/language.jpg" class="card-img-top" alt="Languages">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Bahasa Internasional</h5>
                                                                <p class="card-text">Program bahasa Inggris, Arab, dan Mandarin untuk persiapan global</p>
                                                                <a href="/language-program" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/leadership.jpg" class="card-img-top" alt="Leadership">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Leadership & Entrepreneurship</h5>
                                                                <p class="card-text">Mengembangkan jiwa kepemimpinan dan kewirausahaan sejak dini</p>
                                                                <a href="/leadership" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/character.jpg" class="card-img-top" alt="Character Building">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Character Building</h5>
                                                                <p class="card-text">Pembentukan karakter unggul dengan nilai-nilai Muhammadiyah</p>
                                                                <a href="/character-building" class="btn btn-primary">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-5">
                                                    <a href="https://registration.smam1ta.sch.id/" class="btn btn-primary btn-lg" target="_blank">Daftar Sekarang</a>
                                                </div>
                                            </div>
                                        </section>'
                                    ]
                                ]
                            ]
                        ],

                        // Section 5: Statistics
                        [
                            'name' => 'Statistics',
                            'order' => 4,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'School Statistics',
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

                        // Section 6: Gallery
                        [
                            'name' => 'Gallery',
                            'order' => 5,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Photo Gallery',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold">GALLERY</h2>
                                                    <p class="lead">Dokumentasi kegiatan dan fasilitas SMAMITA</p>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="gallery-item">
                                                            <img src="/images/gallery/kegiatan-1.jpg" class="img-fluid rounded shadow" alt="Kegiatan Pembelajaran">
                                                            <div class="gallery-caption mt-2">
                                                                <h6>Pembelajaran Interaktif</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="gallery-item">
                                                            <img src="/images/gallery/fasilitas-1.jpg" class="img-fluid rounded shadow" alt="Laboratorium">
                                                            <div class="gallery-caption mt-2">
                                                                <h6>Laboratorium Modern</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="gallery-item">
                                                            <img src="/images/gallery/prestasi-1.jpg" class="img-fluid rounded shadow" alt="Prestasi">
                                                            <div class="gallery-caption mt-2">
                                                                <h6>Juara Olimpiade</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="gallery-item">
                                                            <img src="/images/gallery/kegiatan-2.jpg" class="img-fluid rounded shadow" alt="Ekstrakurikuler">
                                                            <div class="gallery-caption mt-2">
                                                                <h6>Ekstrakurikuler</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="gallery-item">
                                                            <img src="/images/gallery/fasilitas-2.jpg" class="img-fluid rounded shadow" alt="Perpustakaan">
                                                            <div class="gallery-caption mt-2">
                                                                <h6>Perpustakaan Digital</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="gallery-item">
                                                            <img src="/images/gallery/kegiatan-3.jpg" class="img-fluid rounded shadow" alt="Festival Seni">
                                                            <div class="gallery-caption mt-2">
                                                                <h6>Festival Seni</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-4">
                                                    <a href="/gallery" class="btn btn-outline-primary">Lihat Semua Gallery</a>
                                                </div>
                                            </div>
                                        </section>'
                                    ]
                                ]
                            ]
                        ],

                        // Section 7: Testimonials
                        [
                            'name' => 'Testimonials',
                            'order' => 6,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Student Testimonials',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5 bg-light">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold">TESTIMONI</h2>
                                                    <p class="lead">Apa kata mereka tentang SMAMITA</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-md-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body text-center">
                                                                <div class="text-warning mb-3">⭐⭐⭐⭐⭐</div>
                                                                <p class="card-text">"SMAMITA memberikan pendidikan terbaik dengan menggabungkan nilai-nilai islami dan akademik yang unggul. Anak saya berkembang sangat baik di sini."</p>
                                                                <hr>
                                                                <strong>Ibu Siti Nurjanah</strong><br>
                                                                <small class="text-muted">Orang Tua Siswa</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body text-center">
                                                                <div class="text-warning mb-3">⭐⭐⭐⭐⭐</div>
                                                                <p class="card-text">"Fasilitas lengkap, guru berkualitas, dan program ISMUBA yang sangat membantu pembentukan karakter islami. Recommended banget!"</p>
                                                                <hr>
                                                                <strong>Ahmad Fauzan</strong><br>
                                                                <small class="text-muted">Alumni 2023</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body text-center">
                                                                <div class="text-warning mb-3">⭐⭐⭐⭐⭐</div>
                                                                <p class="card-text">"Program tahfidz dan pembelajaran berbasis teknologi di SMAMITA sangat membantu persiapan masa depan yang lebih baik."</p>
                                                                <hr>
                                                                <strong>Fatimah Az-Zahra</strong><br>
                                                                <small class="text-muted">Siswa Kelas XII</small>
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

                        // Section 8: CTA Banner
                        [
                            'name' => 'Call to Action',
                            'order' => 7,
                            'blocks' => [
                                [
                                    'type' => 'cta_banner',
                                    'name' => 'PPDB CTA',
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

                        // Section 9: Footer
                        [
                            'name' => 'Footer',
                            'order' => 8,
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
                                                        <h5 class="mb-3">SMA Muhammadiyah 1 Taman</h5>
                                                        <p>Sekolah berbasis islami unggul prestasi dengan motto "The Excellent School" untuk membangun karakter islami generasi Indonesia.</p>
                                                        <div class="social-links mt-3">
                                                            <a href="#" class="text-light me-3 text-decoration-none">
                                                                <i class="fab fa-facebook-f"></i> Facebook
                                                            </a>
                                                            <a href="#" class="text-light me-3 text-decoration-none">
                                                                <i class="fab fa-twitter"></i> Twitter
                                                            </a>
                                                            <a href="https://www.youtube.com/@SMAM1TATV" class="text-light text-decoration-none" target="_blank">
                                                                <i class="fab fa-youtube"></i> Youtube
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Manajemen</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/kurikulum" class="text-light text-decoration-none">Kurikulum</a></li>
                                                            <li class="mb-2"><a href="/kesiswaan" class="text-light text-decoration-none">Kesiswaan</a></li>
                                                            <li class="mb-2"><a href="/ismuba" class="text-light text-decoration-none">ISMUBA</a></li>
                                                            <li class="mb-2"><a href="/humas" class="text-light text-decoration-none">Humas</a></li>
                                                            <li class="mb-2"><a href="/sarpras" class="text-light text-decoration-none">Sarpras</a></li>
                                                            <li class="mb-2"><a href="/human-resource" class="text-light text-decoration-none">Human Resource</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Layanan</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/ppdb" class="text-light text-decoration-none">PPDB</a></li>
                                                            <li class="mb-2"><a href="/rapor" class="text-light text-decoration-none">Rapor</a></li>
                                                            <li class="mb-2"><a href="/skl" class="text-light text-decoration-none">SKL</a></li>
                                                            <li class="mb-2"><a href="/berita" class="text-light text-decoration-none">Berita</a></li>
                                                            <li class="mb-2"><a href="/pengumuman" class="text-light text-decoration-none">Pengumuman</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Resource</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/unduh" class="text-light text-decoration-none">Unduh</a></li>
                                                            <li class="mb-2"><a href="/pusat-bantuan" class="text-light text-decoration-none">Pusat Bantuan</a></li>
                                                            <li class="mb-2"><a href="/acara" class="text-light text-decoration-none">Acara</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="mb-3">Kontak</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2">
                                                                <i class="fas fa-phone me-2"></i>
                                                                <a href="tel:+6281319457080" class="text-light text-decoration-none">+62 813-1945-7080</a>
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="fas fa-envelope me-2"></i>
                                                                <a href="mailto:ppdb@smam1ta.sch.id" class="text-light text-decoration-none">ppdb@smam1ta.sch.id</a>
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                                Jl. Raya Ketegan No.35<br>Taman-Sidoarjo
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <hr class="my-4">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <p class="mb-0">&copy; 2025 SMA Muhammadiyah 1 Taman. All rights reserved.</p>
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
}
