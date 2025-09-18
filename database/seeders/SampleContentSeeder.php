<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.test'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // Create categories
        $categories = [
            ['name' => 'Berita Umum', 'slug' => 'berita-umum'],
            ['name' => 'Prestasi', 'slug' => 'prestasi'],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create sample posts
        $posts = [
            [
                'title' => 'Selamat Datang di Website Sekolah',
                'slug' => 'selamat-datang-website-sekolah',
                'excerpt' => 'Website resmi sekolah telah diluncurkan untuk memberikan informasi terkini kepada seluruh warga sekolah.',
                'body' => '<p>Kami dengan bangga mempersembahkan website resmi sekolah yang baru. Website ini dirancang untuk menjadi pusat informasi bagi seluruh warga sekolah, mulai dari siswa, guru, orang tua, hingga masyarakat umum.</p><p>Melalui website ini, Anda dapat mengakses berbagai informasi penting seperti berita terbaru, agenda kegiatan, galeri foto, dan informasi akademik lainnya.</p>',
                'status' => 'published',
                'published_at' => now(),
                'category_id' => 1,
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Siswa Meraih Juara 1 Olimpiade Matematika',
                'slug' => 'siswa-juara-1-olimpiade-matematika',
                'excerpt' => 'Prestasi membanggakan diraih siswa kelas XII dalam kompetisi Olimpiade Matematika tingkat provinsi.',
                'body' => '<p>Dengan bangga kami sampaikan bahwa siswa kelas XII atas nama Ahmad Fauzi berhasil meraih juara 1 dalam kompetisi Olimpiade Matematika tingkat provinsi.</p><p>Prestasi ini merupakan hasil dari kerja keras dan dedikasi siswa yang didukung penuh oleh guru pembimbing.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'category_id' => 2,
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Kegiatan Study Tour ke Museum Nasional',
                'slug' => 'study-tour-museum-nasional',
                'excerpt' => 'Siswa kelas X mengikuti kegiatan study tour ke Museum Nasional sebagai bagian dari pembelajaran sejarah.',
                'body' => '<p>Kegiatan study tour ke Museum Nasional diikuti oleh seluruh siswa kelas X sebagai bagian dari pembelajaran mata pelajaran sejarah.</p><p>Siswa dapat melihat langsung koleksi artefak bersejarah dan mendapatkan penjelasan detail dari pemandu museum.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'category_id' => 3,
                'user_id' => $admin->id,
            ],
        ];

        foreach ($posts as $postData) {
            Post::firstOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }

        // Create sample events
        $events = [
            [
                'title' => 'Ulangan Tengah Semester Ganjil',
                'description' => '<p>Pelaksanaan Ulangan Tengah Semester (UTS) untuk semua tingkat kelas.</p><p>Siswa diharapkan mempersiapkan diri dengan baik dan mengikuti jadwal yang telah ditentukan.</p>',
                'starts_at' => now()->addDays(7),
                'ends_at' => now()->addDays(14),
                'location' => 'Ruang Kelas Masing-masing',
                'type' => 'akademik',
            ],
            [
                'title' => 'Lomba Karya Tulis Ilmiah',
                'description' => '<p>Kompetisi karya tulis ilmiah untuk siswa SMA se-kabupaten.</p><p>Pendaftaran dibuka hingga akhir bulan ini.</p>',
                'starts_at' => now()->addDays(21),
                'ends_at' => now()->addDays(21)->addHours(8),
                'location' => 'Aula Sekolah',
                'type' => 'ekstrakurikuler',
            ],
            [
                'title' => 'Rapat Orang Tua Siswa',
                'description' => '<p>Rapat koordinasi antara pihak sekolah dengan orang tua siswa untuk membahas perkembangan akademik.</p>',
                'starts_at' => now()->addDays(30),
                'ends_at' => now()->addDays(30)->addHours(3),
                'location' => 'Aula Sekolah',
                'type' => 'umum',
            ],
        ];

        foreach ($events as $eventData) {
            Event::firstOrCreate(
                ['title' => $eventData['title']],
                $eventData
            );
        }

        // Create sample pages
        $pages = [
            [
                'title' => 'Tentang Kita',
                'slug' => 'tentang-kami',
                'body' => '<h2>Sejarah Singkat</h2><p>Sekolah kami didirikan pada tahun 1985 dengan visi menjadi lembaga pendidikan yang unggul dan berkarakter.</p><h2>Visi</h2><p>Menjadi sekolah yang unggul dalam prestasi, berkarakter mulia, dan berwawasan global.</p><h2>Misi</h2><ul><li>Menyelenggarakan pendidikan berkualitas</li><li>Mengembangkan potensi siswa secara optimal</li><li>Membangun karakter siswa yang berakhlak mulia</li></ul>',
                'is_pinned' => true,
            ],
            [
                'title' => 'Fasilitas',
                'slug' => 'fasilitas',
                'body' => '<h2>Fasilitas Akademik</h2><ul><li>Ruang kelas ber-AC</li><li>Laboratorium IPA</li><li>Laboratorium Komputer</li><li>Perpustakaan</li></ul><h2>Fasilitas Olahraga</h2><ul><li>Lapangan basket</li><li>Lapangan futsal</li><li>Ruang senam</li></ul>',
                'is_pinned' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        // Create sample gallery
        $gallery = Gallery::firstOrCreate(
            ['slug' => 'kegiatan-sekolah-2024'],
            [
                'title' => 'Kegiatan Sekolah 2024',
                'description' => 'Dokumentasi berbagai kegiatan sekolah tahun 2024',
                'slug' => 'kegiatan-sekolah-2024',
            ]
        );

        $this->command->info('Sample content created successfully!');
    }
}
