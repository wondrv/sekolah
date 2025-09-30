<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateCategory;
use App\Models\TemplateGallery;
use App\Models\UserTemplate;
use App\Models\User;

/**
 * Alternate modern school template providing a different layout & content mix
 * so admins can easily swap between two homepage experiences.
 */
class AlternateModernSchoolTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $category = TemplateCategory::firstOrCreate(
            ['slug' => 'sekolah-menengah'],
            [
                'name' => 'Sekolah Menengah',
                'description' => 'Template untuk SMP dan SMA'
            ]
        );

        $gallery = TemplateGallery::updateOrCreate(
            ['slug' => 'alt-modern-school-template'],
            [
                'name' => 'Alternatif Modern School',
                'slug' => 'alt-modern-school-template',
                'description' => 'Template alternatif dengan fokus program, testimoni, dan CTA menonjol.',
                'category_id' => $category->id,
                'preview_image' => 'templates/previews/alt-modern-school.jpg',
                'preview_images' => [
                    'templates/previews/alt-modern-school-1.jpg',
                    'templates/previews/alt-modern-school-2.jpg'
                ],
                'template_data' => $this->getTemplateData(),
                'author' => 'School CMS Team',
                'version' => '1.0.0',
                'features' => [
                    'Hero Split Layout',
                    'Program Cards Grid',
                    'Stats Banner',
                    'Testimonials Section',
                    'Events & Posts Teasers',
                    'Dual CTA Banners',
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#0F766E', 'secondary' => '#F59E0B', 'accent' => '#2563EB'],
                ],
                'featured' => false,
                'premium' => false,
                'rating' => 4.9,
                'downloads' => 0,
                'active' => true,
            ]
        );

        $admin = User::where('email', 'admin@school.local')->first();
        if ($admin) {
            UserTemplate::updateOrCreate(
                ['slug' => 'alt-modern-school-active'],
                [
                    'user_id' => $admin->id,
                    'name' => 'Alternatif Modern Active',
                    'slug' => 'alt-modern-school-active',
                    'description' => 'Template alternatif siap digunakan',
                    'template_data' => $this->getTemplateData(),
                    'gallery_template_id' => $gallery->id,
                    'source' => 'gallery',
                    'customizations' => [
                        'css' => $this->getCustomCSS(),
                        'javascript' => $this->getCustomJS(),
                    ],
                ]
            );
        }

        $this->command->info('Alternate Modern School Template created.');
    }

    private function getTemplateData(): array
    {
        return [
            'templates' => [
                [
                    'name' => 'Alternate Homepage',
                    'slug' => 'alternate-homepage',
                    'description' => 'Homepage alternatif dengan fokus program, testimoni dan call to action.',
                    'type' => 'page',
                    'active' => true,
                    'sections' => [
                        // Top Navigation (simpler variant)
                        [
                            'name' => 'Top Navigation',
                            'order' => 0,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Nav Bar',
                                    'order' => 0,
                                    'content' => [ 'html' => $this->getNavigationHTML() ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Hero Split Section
                        [
                            'name' => 'Hero Split',
                            'order' => 1,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Hero Split Content',
                                    'order' => 0,
                                    'content' => [ 'html' => $this->getHeroSplitHTML() ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Stats Banner
                        [
                            'name' => 'Stats Banner',
                            'order' => 2,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'stats',
                                    'name' => 'Quick Stats',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Jejak Prestasi',
                                        'stats' => [
                                            ['number' => '30+', 'label' => 'Guru Profesional'],
                                            ['number' => '1:20', 'label' => 'Rasio Guru:Siswa'],
                                            ['number' => '25+', 'label' => 'Ekstrakurikuler'],
                                            ['number' => '50+', 'label' => 'Prestasi Nasional'],
                                        ],
                                        'background_color' => 'bg-teal-700'
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Programs Grid
                        [
                            'name' => 'Programs Grid', // id="programs"
                            'order' => 3,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'card_grid',
                                    'name' => 'Programs Cards',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Fokus Pengembangan',
                                        'subtitle' => 'Mendukung potensi akademik, karakter, dan kreativitas siswa.',
                                        'background_color' => 'bg-gray-50',
                                        'columns' => 3,
                                        'anchor' => 'programs',
                                        'cards' => [
                                            ['title' => 'STEM Lab', 'description' => 'Eksperimen sains & teknologi terapan.', 'image' => 'https://picsum.photos/seed/stem/640/360'],
                                            ['title' => 'Literasi & Debat', 'description' => 'Penguatan komunikasi dan critical thinking.', 'image' => 'https://picsum.photos/seed/debate/640/360'],
                                            ['title' => 'Karya Digital', 'description' => 'Desain, media kreatif & produksi konten.', 'image' => 'https://picsum.photos/seed/digital/640/360'],
                                            ['title' => 'Pengabdian Sosial', 'description' => 'Membentuk empati melalui aksi nyata.', 'image' => 'https://picsum.photos/seed/social/640/360'],
                                            ['title' => 'Kader Islami', 'description' => 'Pembinaan nilai spiritual & akhlak.', 'image' => 'https://picsum.photos/seed/islamic/640/360'],
                                            ['title' => 'Atletik & Seni', 'description' => 'Fasilitasi olahraga & ekspresi seni.', 'image' => 'https://picsum.photos/seed/art/640/360'],
                                        ]
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Testimonials Section
                        [
                            'name' => 'Testimonials',
                            'order' => 4,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'rich_text',
                                    'name' => 'Testimonials Content',
                                    'order' => 0,
                                    'content' => [ 'html' => $this->getTestimonialsHTML(), 'anchor' => 'testimonials' ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Events
                        [
                            'name' => 'Events Teaser',
                            'order' => 5,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'events_teaser',
                                    'name' => 'Events List',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Agenda Terdekat',
                                        'limit' => 4,
                                        'anchor' => 'events',
                                        '_sample_events' => [
                                            ['title' => 'Workshop Sains Terbuka', 'date' => now()->addDays(5)->format('d M Y'), 'description' => 'Eksperimen laboratorium interaktif.'],
                                            ['title' => 'Lomba Debat Bahasa', 'date' => now()->addDays(9)->format('d M Y'), 'description' => 'Kompetisi antar kelas mengasah retorika.'],
                                            ['title' => 'Pentas Seni Internal', 'date' => now()->addDays(14)->format('d M Y'), 'description' => 'Penampilan musik, drama & tari siswa.'],
                                        ],
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Posts
                        [
                            'name' => 'Posts Teaser',
                            'order' => 6,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'posts_teaser',
                                    'name' => 'Latest Articles',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Wawasan & Berita',
                                        'limit' => 4,
                                        'show_read_more' => true,
                                        'anchor' => 'posts',
                                        '_sample_posts' => [
                                            ['title' => 'Peluncuran Program STEM Terpadu', 'date' => now()->subDays(2)->format('d M Y'), 'excerpt' => 'Program baru untuk integrasi sains & teknologi di kelas.'],
                                            ['title' => 'Ekstrakurikuler Baru: Studio Media Digital', 'date' => now()->subDays(5)->format('d M Y'), 'excerpt' => 'Fasilitas baru untuk produksi konten kreatif dan edukatif.'],
                                            ['title' => 'Siswa Raih Juara Olimpiade Matematika', 'date' => now()->subDays(7)->format('d M Y'), 'excerpt' => 'Prestasi membanggakan tingkat provinsi diraih tim olimpiade.'],
                                        ],
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Call To Action (Bottom)
                        [
                            'name' => 'Join CTA',
                            'order' => 7,
                            'active' => true,
                            'blocks' => [
                                [
                                    'type' => 'cta_banner',
                                    'name' => 'Join Us CTA',
                                    'order' => 0,
                                    'content' => [
                                        'title' => 'Bergabung Menjadi Bagian Sekolah Unggul',
                                        'subtitle' => 'Daftar sekarang untuk tahun ajaran baru dan raih peluang terbaik.',
                                        'button_text' => 'Informasi PPDB',
                                        'button_url' => '/ppdb',
                                        'background_style' => 'gradient-green',
                                        'anchor' => 'daftar'
                                    ],
                                    'active' => true
                                ]
                            ]
                        ],
                        // Footer
                        [
                            'name' => 'Footer',
                            'order' => 8,
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

    private function getNavigationHTML(): string
    {
        return '<nav class="w-full bg-white shadow-sm fixed top-0 left-0 z-40">  <div class="max-w-7xl mx-auto px-4">    <div class="flex items-center justify-between h-16">      <a href="/" class="flex items-center space-x-2">        <img src="https://smam1ta.sch.id/wp-content/uploads/2023/07/logo-smamita-2023-300x296.png" class="h-10 w-auto" alt="Logo">        <span class="font-bold text-teal-700 text-xl">SMAMITA</span>      </a>      <div class="hidden md:flex space-x-8 font-medium">        <a href="#hero" class="hover:text-teal-700">Beranda</a>        <a href="#programs" class="hover:text-teal-700">Program</a>        <a href="#events" class="hover:text-teal-700">Agenda</a>        <a href="#posts" class="hover:text-teal-700">Berita</a>        <a href="#footer" class="hover:text-teal-700">Kontak</a>      </div>      <div class="hidden md:block">        <a href="/ppdb" class="px-5 py-2 rounded-md bg-teal-600 text-white font-semibold hover:bg-teal-700 transition">PPDB</a>      </div>    </div>  </div></nav>';
    }

    private function getHeroSplitHTML(): string
    {
        return '<section id="hero" class="pt-24 pb-16 bg-gradient-to-br from-teal-700 to-teal-900 text-white relative overflow-hidden">  <div class="absolute inset-0 opacity-10 bg-[url(https://www.transparenttextures.com/patterns/cubes.png)]"></div>  <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-center relative">    <div>      <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">Mencetak Generasi Berkarakter, Cerdas & Berdaya Saing Global</h1>      <p class="text-lg md:text-xl mb-8 opacity-90">Lingkungan belajar islami, modern, dan kolaboratif untuk mengembangkan potensi akademik dan kepemimpinan.</p>      <div class="flex flex-wrap gap-4">        <a href="/ppdb" class="px-6 py-3 rounded-lg bg-amber-500 hover:bg-amber-600 font-semibold text-gray-900 transition">Daftar PPDB</a>        <a href="#programs" class="px-6 py-3 rounded-lg bg-white/10 hover:bg-white/20 font-semibold backdrop-blur border border-white/20 transition">Lihat Program</a>      </div>    </div>    <div class="relative">      <div class="aspect-video rounded-xl overflow-hidden shadow-2xl ring-1 ring-white/20">        <img src="https://smam1ta.sch.id/wp-content/uploads/2023/10/gedung-smamita-scaled.jpg" class="w-full h-full object-cover" alt="Campus" />      </div>    </div>  </div></section>';
    }

    private function getTestimonialsHTML(): string
    {
        return '<section id="testimonials" class="py-20 bg-white">  <div class="max-w-7xl mx-auto px-4">    <div class="text-center max-w-2xl mx-auto mb-12">      <h2 class="text-3xl md:text-4xl font-bold mb-4">Apa Kata <span class="text-teal-700">Mereka</span></h2>      <p class="text-gray-600">Testimoni siswa & alumni tentang pengalaman belajar.</p>    </div>    <div class="grid md:grid-cols-3 gap-8">      <div class="p-6 bg-gray-50 rounded-xl shadow-sm border border-gray-100">        <p class="text-gray-700 italic mb-4">\"Guru-guru sangat mendukung dan fasilitas sekolah lengkap untuk eksplorasi potensi.\"</p>        <div class="font-semibold text-teal-700">Alya - Siswa Kelas XI</div>      </div>      <div class="p-6 bg-gray-50 rounded-xl shadow-sm border border-gray-100">        <p class="text-gray-700 italic mb-4">\"Program tahfidz membantu saya lebih disiplin dan terarah dalam belajar.\"</p>        <div class="font-semibold text-teal-700">Farhan - Alumni</div>      </div>      <div class="p-6 bg-gray-50 rounded-xl shadow-sm border border-gray-100">        <p class="text-gray-700 italic mb-4">\"Kegiatan ekstrakurikuler sangat variatif dan menumbuhkan kepemimpinan.\"</p>        <div class="font-semibold text-teal-700">Nadya - Siswa Kelas XII</div>      </div>    </div>  </div></section>';
    }

    private function getFooterHTML(): string
    {
        return '<footer id="footer" class="bg-gray-900 text-gray-300 py-14">  <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-4 gap-10">    <div class="col-span-2">      <h3 class="text-white text-xl font-bold mb-4">SMAMITA</h3>      <p class="text-sm leading-relaxed mb-4">Sekolah menengah yang berkomitmen pada keunggulan akademik, karakter islami, dan inovasi berkelanjutan.</p>      <p class="text-xs text-gray-500">&copy; ' . date('Y') . ' SMAMITA. Hak cipta dilindungi.</p>    </div>    <div>      <h4 class="text-white font-semibold mb-3">Tautan</h4>      <ul class="space-y-2 text-sm">        <li><a href="/" class="hover:text-white">Beranda</a></li>        <li><a href="/ppdb" class="hover:text-white">PPDB</a></li>        <li><a href="#events" class="hover:text-white">Agenda</a></li>        <li><a href="#posts" class="hover:text-white">Berita</a></li>      </ul>    </div>    <div>      <h4 class="text-white font-semibold mb-3">Kontak</h4>      <ul class="space-y-2 text-sm">        <li>Jl. Contoh Pendidikan No. 10</li>        <li>Telp: (031) 700000</li>        <li>Email: info@smamita.sch.id</li>      </ul>    </div>  </div></footer>';
    }

    private function getCustomCSS(): string
    {
        return '/* Custom accent colors & overrides for alternate template */:root {  --primary-color: #0F766E;  --secondary-color: #F59E0B;  --accent-color: #2563EB;}.cta-banner.gradient-green {  background: linear-gradient(90deg,#0F766E,#0D9488);  color: #fff;}.cta-banner.gradient-blue {  background: linear-gradient(90deg,#1E3A8A,#1D4ED8);  color: #fff;}.hero-split-overlay {  background: linear-gradient(135deg,rgba(15,118,110,0.85),rgba(13,148,136,0.85));}.card-grid-block .card:hover {  transform: translateY(-6px);  transition: transform .35s ease;}';
    }

    private function getCustomJS(): string
    {
        return '// Simple fade-in on scrollconst observer = new IntersectionObserver(entries => {  entries.forEach(e => {    if (e.isIntersecting) {      e.target.classList.add("opacity-100","translate-y-0");    }  });},{ threshold: 0.15 });(document.querySelectorAll("[data-fade]")||[]).forEach(el=>{  el.classList.add("opacity-0","translate-y-4","transition","duration-700");  observer.observe(el);});';
    }
}
