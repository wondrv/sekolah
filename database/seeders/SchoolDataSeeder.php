<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Banner;

class SchoolDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user for CMS
        $admin = \App\Models\User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.local',
            'password' => Hash::make('password'),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Berita Sekolah', 'slug' => 'berita-sekolah', 'description' => 'Berita dan informasi terbaru sekolah'],
            ['name' => 'Prestasi', 'slug' => 'prestasi', 'description' => 'Prestasi siswa dan sekolah'],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan', 'description' => 'Kegiatan sekolah dan ekstrakurikuler'],
            ['name' => 'Akademik', 'slug' => 'akademik', 'description' => 'Informasi akademik dan pembelajaran'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        // Create sample posts
        $posts = [
            [
                'title' => 'Pembukaan Tahun Ajaran Baru 2025/2026',
                'slug' => 'pembukaan-tahun-ajaran-baru-2025-2026',
                'excerpt' => 'Sekolah kami dengan bangga membuka tahun ajaran baru dengan semangat dan harapan baru.',
                'body' => '<p>Hari ini, sekolah kami resmi membuka tahun ajaran baru 2025/2026 dengan penuh semangat. Acara pembukaan dihadiri oleh seluruh siswa, guru, dan orang tua murid.</p><p>Kepala sekolah menyampaikan sambutan sekaligus visi misi untuk tahun ajaran yang baru ini. Diharapkan semua pihak dapat bekerja sama untuk mencapai tujuan pendidikan yang optimal.</p>',
                'category_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Prestasi Gemilang di Olimpiade Matematika Nasional',
                'slug' => 'prestasi-gemilang-olimpiade-matematika-nasional',
                'excerpt' => 'Siswa kami meraih juara 1 dalam Olimpiade Matematika Nasional tingkat SMA.',
                'body' => '<p>Dengan bangga kami sampaikan bahwa siswa kelas XII IPA, Ahmad Rahman, berhasil meraih juara 1 dalam Olimpiade Matematika Nasional tingkat SMA.</p><p>Prestasi ini membuktikan kualitas pendidikan dan dedikasi dalam pembinaan siswa berprestasi di sekolah kami.</p>',
                'category_id' => 3,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Kegiatan Bakti Sosial di Panti Asuhan',
                'slug' => 'kegiatan-bakti-sosial-panti-asuhan',
                'excerpt' => 'Siswa dan guru melakukan kegiatan bakti sosial di panti asuhan setempat.',
                'body' => '<p>Sebagai bentuk kepedulian sosial, siswa dan guru sekolah kami mengadakan kegiatan bakti sosial di Panti Asuhan Harapan Bangsa.</p><p>Kegiatan ini meliputi pemberian bantuan berupa sembako, pakaian, dan alat tulis untuk anak-anak panti asuhan.</p>',
                'category_id' => 4,
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'user_id' => $admin->id,
            ]
        ];

        foreach ($posts as $post) {
            \App\Models\Post::create($post);
        }

        // Create sample pages
        $pages = [
            [
                'title' => 'Visi Misi',
                'slug' => 'visi-misi',
                'body' => '<h2>Visi</h2><p>Menjadi sekolah unggulan yang menghasilkan generasi berkarakter, berprestasi, dan siap menghadapi tantangan global.</p><h2>Misi</h2><ul><li>Menyelenggarakan pendidikan berkualitas tinggi</li><li>Mengembangkan karakter siswa yang berakhlak mulia</li><li>Memfasilitasi pengembangan potensi akademik dan non-akademik</li><li>Menciptakan lingkungan belajar yang kondusif</li></ul>',
                'is_pinned' => true,
            ],
            [
                'title' => 'Sejarah Sekolah',
                'slug' => 'sejarah',
                'body' => '<p>Didirikan pada tahun 1990, sekolah kami telah mengabdi dalam dunia pendidikan selama lebih dari 30 tahun.</p><p>Dengan komitmen untuk memberikan pendidikan terbaik, kami terus berinovasi dan berkembang mengikuti perkembangan zaman sambil tetap mempertahankan nilai-nilai luhur pendidikan.</p>',
                'is_pinned' => false,
            ]
        ];

        foreach ($pages as $page) {
            \App\Models\Page::create($page);
        }

        // Create sample events
        $events = [
            [
                'title' => 'Ujian Tengah Semester',
                'description' => 'Pelaksanaan Ujian Tengah Semester untuk semua tingkat',
                'starts_at' => now()->addDays(10),
                'ends_at' => now()->addDays(15),
                'location' => 'Seluruh ruang kelas',
                'type' => 'academic',
                'is_featured' => true,
            ],
            [
                'title' => 'Festival Seni dan Budaya',
                'description' => 'Pentas seni dan budaya tahunan sekolah',
                'starts_at' => now()->addDays(20),
                'ends_at' => now()->addDays(20)->addHours(6),
                'location' => 'Aula Sekolah',
                'type' => 'extracurricular',
                'is_featured' => true,
            ],
            [
                'title' => 'Rapat Komite Sekolah',
                'description' => 'Rapat rutin komite sekolah',
                'starts_at' => now()->addDays(7),
                'ends_at' => now()->addDays(7)->addHours(2),
                'location' => 'Ruang Kepala Sekolah',
                'type' => 'general',
                'is_featured' => false,
            ]
        ];

        foreach ($events as $event) {
            \App\Models\Event::create($event);
        }

        // Create sample galleries
        $galleries = [
            [
                'title' => 'Kegiatan Pembelajaran',
                'slug' => 'kegiatan-pembelajaran',
                'description' => 'Dokumentasi aktivitas pembelajaran sehari-hari',
            ],
            [
                'title' => 'Ekstrakurikuler',
                'slug' => 'ekstrakurikuler',
                'description' => 'Kegiatan ekstrakurikuler siswa',
            ],
            [
                'title' => 'Prestasi Siswa',
                'slug' => 'prestasi-siswa',
                'description' => 'Dokumentasi prestasi yang diraih siswa',
            ]
        ];

        foreach ($galleries as $gallery) {
            \App\Models\Gallery::create($gallery);
        }

        // Create sample banners
        $banners = [
            [
                'title' => 'Selamat Datang di Website Sekolah Kami',
                'subtitle' => 'Pendidikan Berkualitas untuk Masa Depan Gemilang',
                'button_text' => 'Daftar Sekarang',
                'button_url' => '/ppdb',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Prestasi Membanggakan',
                'subtitle' => 'Siswa kami meraih berbagai prestasi di tingkat nasional',
                'button_text' => 'Lihat Prestasi',
                'button_url' => '/berita?category=prestasi',
                'sort_order' => 2,
                'is_active' => true,
            ]
        ];

        foreach ($banners as $banner) {
            \App\Models\Banner::create($banner);
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Admin login: admin@sekolah.local / password');
        $this->command->info('Editor login: editor@sekolah.local / password');
    }
}
