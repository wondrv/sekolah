<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;

class CompleteMadrasahTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get pesantren category
        $pesantrenCategory = TemplateCategory::where('slug', 'pesantren')->first();

        // Create complete Madrasah template
        $template = [
            'name' => 'Madrasah Aliyah Complete',
            'slug' => 'madrasah-aliyah-complete',
            'description' => 'Template lengkap untuk Madrasah Aliyah dengan konten Islam siap pakai',
            'category_id' => $pesantrenCategory->id,
            'preview_image' => 'templates/previews/madrasah-complete.jpg',
            'template_data' => $this->getCompleteMadrasahTemplate(),
            'author' => 'School CMS Islamic Team',
            'version' => '2.0.0',
            'features' => [
                'Complete Islamic Content',
                'Arabic Text Integration',
                'Islamic Programs',
                'Tahfidz Al-Quran',
                'Fiqih & Akhlaq',
                'Islamic Calendar',
                'Prayer Times',
                'Complete Navigation'
            ],
            'color_schemes' => [
                'default' => ['primary' => '#059669', 'secondary' => '#047857', 'accent' => '#D97706'],
            ],
            'featured' => true,
            'premium' => false,
            'rating' => 5.0,
            'downloads' => 0,
        ];

        TemplateGallery::updateOrCreate(
            ['slug' => $template['slug']],
            $template
        );

        $this->command->info('Created complete Madrasah template with Islamic content!');
    }

    /**
     * Complete Madrasah Template
     */
    private function getCompleteMadrasahTemplate()
    {
        return [
            'templates' => [
                [
                    'name' => 'Madrasah Aliyah Homepage',
                    'slug' => 'homepage',
                    'description' => 'Homepage lengkap untuk Madrasah Aliyah dengan konten Islam',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        // Islamic Navigation
                        [
                            'name' => 'Islamic Navigation',
                            'order' => 0,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Madrasah Navigation',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <header class="sticky-top bg-white shadow">
                                            <div class="container-fluid">
                                                <!-- Islamic Top Bar -->
                                                <div class="row bg-success text-white py-2">
                                                    <div class="col-md-6">
                                                        <small class="fw-bold">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</small>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <small>📞 +62 813-1945-7080 | ✉️ info@madrasah.sch.id</small>
                                                    </div>
                                                </div>

                                                <!-- Main Navigation -->
                                                <nav class="navbar navbar-expand-lg navbar-light">
                                                    <a class="navbar-brand d-flex align-items-center" href="/">
                                                        <img src="/images/logo-madrasah.png" alt="Madrasah" height="45" class="me-2">
                                                        <div>
                                                            <div class="fw-bold text-success">مدرسة علياء</div>
                                                            <small class="text-muted">Madrasah Aliyah</small>
                                                        </div>
                                                    </a>

                                                    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
                                                        <span class="navbar-toggler-icon"></span>
                                                    </button>

                                                    <div class="collapse navbar-collapse" id="nav">
                                                        <ul class="navbar-nav ms-auto">
                                                            <li class="nav-item"><a class="nav-link active" href="/">Beranda</a></li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Profil</a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/sejarah">Sejarah</a></li>
                                                                    <li><a class="dropdown-item" href="/visi-misi">Visi & Misi</a></li>
                                                                    <li><a class="dropdown-item" href="/struktur">Struktur</a></li>
                                                                    <li><a class="dropdown-item" href="/fasilitas">Fasilitas</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Program Islam</a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/tahfidz">Tahfidz Al-Quran</a></li>
                                                                    <li><a class="dropdown-item" href="/bahasa-arab">Bahasa Arab</a></li>
                                                                    <li><a class="dropdown-item" href="/fiqih">Fiqih & Ibadah</a></li>
                                                                    <li><a class="dropdown-item" href="/akhlaq">Akhlaq & Tasawuf</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Akademik</a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/kurikulum">Kurikulum</a></li>
                                                                    <li><a class="dropdown-item" href="/jadwal">Jadwal</a></li>
                                                                    <li><a class="dropdown-item" href="/program-studi">Program Studi</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item dropdown">
                                                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Santri</a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="/ekstrakurikuler">Ekstrakurikuler</a></li>
                                                                    <li><a class="dropdown-item" href="/prestasi">Prestasi</a></li>
                                                                    <li><a class="dropdown-item" href="/asrama">Asrama</a></li>
                                                                </ul>
                                                            </li>
                                                            <li class="nav-item"><a class="nav-link" href="/berita">Berita</a></li>
                                                            <li class="nav-item"><a class="nav-link" href="/kontak">Kontak</a></li>
                                                            <li class="nav-item">
                                                                <a class="btn btn-success ms-2" href="/ppdb">Daftar Santri</a>
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

                        // Islamic Hero
                        [
                            'name' => 'Islamic Hero',
                            'order' => 1,
                            'blocks' => [
                                [
                                    'type' => 'hero',
                                    'name' => 'Madrasah Hero',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'مدرسة علياء الحديثة',
                                        'subtitle' => 'Madrasah Aliyah Modern - Membangun Generasi Qur\'ani Berakhlak Mulia',
                                        'description' => 'Lembaga pendidikan Islam terpadu yang mengintegrasikan ilmu agama dan umum. Membentuk santri sholeh, cerdas, dan siap menghadapi tantangan zaman dengan landasan Al-Quran dan As-Sunnah.',
                                        'button_text' => 'Daftar Santri Baru',
                                        'button_url' => '/ppdb',
                                        'secondary_button_text' => 'Virtual Tour',
                                        'secondary_button_url' => '/virtual-tour',
                                        'background_image' => 'hero/madrasah-hero.jpg'
                                    ],
                                    'settings' => [
                                        'overlay' => true,
                                        'text_color' => 'white'
                                    ]
                                ]
                            ]
                        ],

                        // Islamic Values
                        [
                            'name' => 'Nilai Islam',
                            'order' => 2,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Islamic Values',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold text-success">القيم الإسلامية</h2>
                                                    <h3 class="h3">Nilai-Nilai Islam</h3>
                                                    <p class="lead">Fondasi pendidikan berbasis Al-Quran dan As-Sunnah</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-success mb-3" style="font-size: 3rem;">☪️</div>
                                                                <h5 class="fw-bold">التوحيد</h5>
                                                                <h6 class="text-muted">TAUHID</h6>
                                                                <p>Aqidah lurus berdasarkan Al-Quran dan Sunnah</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-success mb-3" style="font-size: 3rem;">📖</div>
                                                                <h5 class="fw-bold">القرآن</h5>
                                                                <h6 class="text-muted">AL-QURAN</h6>
                                                                <p>Menghafal, memahami, dan mengamalkan Al-Quran</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-success mb-3" style="font-size: 3rem;">🤲</div>
                                                                <h5 class="fw-bold">الأخلاق</h5>
                                                                <h6 class="text-muted">AKHLAQ</h6>
                                                                <p>Akhlaq mulia mengikuti teladan Rasulullah SAW</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card h-100 text-center border-0 shadow-sm">
                                                            <div class="card-body p-4">
                                                                <div class="text-success mb-3" style="font-size: 3rem;">🎓</div>
                                                                <h5 class="fw-bold">العلم</h5>
                                                                <h6 class="text-muted">ILMU</h6>
                                                                <p>Menuntut ilmu dunia dan akhirat</p>
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

                        // Program Keagamaan
                        [
                            'name' => 'Program Keagamaan',
                            'order' => 3,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Islamic Programs',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <section class="py-5 bg-light">
                                            <div class="container">
                                                <div class="text-center mb-5">
                                                    <h2 class="display-5 fw-bold">البرامج الدينية</h2>
                                                    <h3 class="h3">Program Keagamaan Unggulan</h3>
                                                    <p class="lead">Pendidikan Islam terpadu untuk santri sholeh dan cerdas</p>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/tahfidz.jpg" class="card-img-top" alt="Tahfidz">
                                                            <div class="card-body">
                                                                <h5>تحفيظ القرآن</h5>
                                                                <h6 class="text-success">Tahfidz Al-Quran</h6>
                                                                <p>Program hafalan Al-Quran 30 juz dengan metode modern dan bimbingan ustadz berpengalaman.</p>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Target: 5-30 Juz</small>
                                                                </div>
                                                                <a href="/tahfidz" class="btn btn-success">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/arabic.jpg" class="card-img-top" alt="Arabic">
                                                            <div class="card-body">
                                                                <h5>اللغة العربية</h5>
                                                                <h6 class="text-success">Bahasa Arab</h6>
                                                                <p>Pembelajaran bahasa Arab intensif dari dasar hingga mahir untuk memahami Al-Quran.</p>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Sertifikat: TOAFL</small>
                                                                </div>
                                                                <a href="/bahasa-arab" class="btn btn-success">Selengkapnya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <img src="/images/programs/fiqih.jpg" class="card-img-top" alt="Fiqih">
                                                            <div class="card-body">
                                                                <h5>الفقه والعبادة</h5>
                                                                <h6 class="text-success">Fiqih & Ibadah</h6>
                                                                <p>Pemahaman mendalam hukum Islam dan tata cara ibadah yang benar.</p>
                                                                <div class="mb-2">
                                                                    <small class="text-muted">Mazhab: Syafi\'i</small>
                                                                </div>
                                                                <a href="/fiqih" class="btn btn-success">Selengkapnya</a>
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
                                    'name' => 'Madrasah Stats',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'إحصائيات المدرسة - Prestasi Madrasah',
                                        'stats' => [
                                            [
                                                'label' => 'Santri Aktif',
                                                'value' => '800+',
                                                'icon' => 'users'
                                            ],
                                            [
                                                'label' => 'Ustadz & Guru',
                                                'value' => '65+',
                                                'icon' => 'teacher'
                                            ],
                                            [
                                                'label' => 'Hafidz Al-Quran',
                                                'value' => '120+',
                                                'icon' => 'quran'
                                            ],
                                            [
                                                'label' => 'Alumni Berprestasi',
                                                'value' => '3,000+',
                                                'icon' => 'graduate'
                                            ]
                                        ]
                                    ],
                                    'settings' => [
                                        'background_color' => '#059669',
                                        'text_color' => 'white'
                                    ]
                                ]
                            ]
                        ],

                        // CTA
                        [
                            'name' => 'CTA',
                            'order' => 5,
                            'blocks' => [
                                [
                                    'type' => 'cta_banner',
                                    'name' => 'Daftar CTA',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'من المدرسة للأمة',
                                        'subtitle' => 'Dari Madrasah untuk Umat',
                                        'description' => 'Bergabunglah dengan pendidikan Islam berkualitas. Daftar sekarang dan rasakan pengalaman belajar yang menyeluruh.',
                                        'button_text' => 'Daftar Santri Baru 2025',
                                        'button_url' => '/ppdb',
                                        'background_color' => '#059669'
                                    ]
                                ]
                            ]
                        ],

                        // Islamic Footer
                        [
                            'name' => 'Footer',
                            'order' => 6,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Islamic Footer',
                                    'order' => 0,
                                    'content' => [
                                        'html' => '
                                        <footer class="bg-dark text-light py-5">
                                            <div class="container">
                                                <div class="text-center mb-4">
                                                    <h5 class="text-success">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</h5>
                                                    <small class="text-muted">Dengan menyebut nama Allah Yang Maha Pengasih</small>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4 mb-4">
                                                        <h5 class="text-success">مدرسة علياء الحديثة</h5>
                                                        <h6>Madrasah Aliyah Modern</h6>
                                                        <p>Lembaga pendidikan Islam terpadu untuk mencetak generasi qur\'ani berakhlak mulia dan berprestasi.</p>
                                                        <div class="social-links mt-3">
                                                            <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i> Facebook</a>
                                                            <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i> Instagram</a>
                                                            <a href="#" class="text-light"><i class="fab fa-youtube"></i> YouTube</a>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="text-success">البرامج</h6>
                                                        <h6>Program</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/tahfidz" class="text-light">Tahfidz</a></li>
                                                            <li class="mb-2"><a href="/bahasa-arab" class="text-light">Bahasa Arab</a></li>
                                                            <li class="mb-2"><a href="/fiqih" class="text-light">Fiqih</a></li>
                                                            <li class="mb-2"><a href="/akhlaq" class="text-light">Akhlaq</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="text-success">الأكاديمية</h6>
                                                        <h6>Akademik</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/kurikulum" class="text-light">Kurikulum</a></li>
                                                            <li class="mb-2"><a href="/jadwal" class="text-light">Jadwal</a></li>
                                                            <li class="mb-2"><a href="/program-studi" class="text-light">Program Studi</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="text-success">الطلاب</h6>
                                                        <h6>Santri</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2"><a href="/ekstrakurikuler" class="text-light">Ekstrakurikuler</a></li>
                                                            <li class="mb-2"><a href="/prestasi" class="text-light">Prestasi</a></li>
                                                            <li class="mb-2"><a href="/asrama" class="text-light">Asrama</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-2 col-md-6 mb-4">
                                                        <h6 class="text-success">التواصل</h6>
                                                        <h6>Kontak</h6>
                                                        <ul class="list-unstyled">
                                                            <li class="mb-2">
                                                                <i class="fas fa-phone me-2"></i>
                                                                <a href="tel:+6281319457080" class="text-light">+62 813-1945-7080</a>
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="fas fa-envelope me-2"></i>
                                                                <a href="mailto:info@madrasah.sch.id" class="text-light">info@madrasah.sch.id</a>
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                                Jl. Pendidikan Islam No. 123<br>Kota Pendidikan
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <hr class="my-4">

                                                <div class="text-center mb-3">
                                                    <p class="text-success mb-1">رَبَّنَا آتِنَا فِي الدُّنْيَا حَسَنَةً وَفِي الْآخِرَةِ حَسَنَةً وَقِنَا عَذَابَ النَّارِ</p>
                                                    <small class="text-muted">"Ya Tuhan kami, berilah kami kebaikan di dunia dan akhirat"</small>
                                                </div>

                                                <div class="text-center">
                                                    <p class="mb-0">&copy; 2025 Madrasah Aliyah Modern. Hak cipta dilindungi.</p>
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
