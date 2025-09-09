<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class AdditionalContentSeeder extends Seeder
{
    public function run(): void
    {
        // Get the admin user
        $admin = User::where('email', 'admin@sekolah.local')->first();

        // Additional categories matching the reference website
        $categories = [
            ['name' => 'Artikel Ilmiah', 'slug' => 'artikel-ilmiah', 'description' => 'Artikel dan penelitian ilmiah'],
            ['name' => 'Berita', 'slug' => 'berita', 'description' => 'Berita terkini sekolah'],
            ['name' => 'Fasilitas dan Infrastruktur', 'slug' => 'fasilitas-infrastruktur', 'description' => 'Informasi fasilitas sekolah'],
            ['name' => 'Keagamaan', 'slug' => 'keagamaan', 'description' => 'Kegiatan dan informasi keagamaan'],
            ['name' => 'Konseling', 'slug' => 'konseling', 'description' => 'Layanan bimbingan konseling'],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman', 'description' => 'Pengumuman resmi sekolah'],
            ['name' => 'Research and Development', 'slug' => 'research-development', 'description' => 'Riset dan pengembangan'],
            ['name' => 'Tips dan Trick', 'slug' => 'tips-trick', 'description' => 'Tips dan trik pembelajaran'],
            ['name' => 'Trend Global', 'slug' => 'trend-global', 'description' => 'Tren pendidikan global'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Additional posts with different categories
        $posts = [
            [
                'title' => 'Direktur Sekolah Gelar Rapat Darurat, Sampaikan Pesan Dinas Pendidikan',
                'slug' => 'direktur-sekolah-gelar-rapat-darurat-sampaikan-pesan-dinas-pendidikan',
                'excerpt' => 'Melalui Surat Edaran Resmi, Direktur sekolah menggelar rapat darurat untuk menyampaikan pesan penting dari Dinas Pendidikan.',
                'body' => '<p>Melalui Surat Edaran Resmi tertanggal 29 Agustus 2025, Kepala Dinas Pendidikan telah menyampaikan beberapa kebijakan penting yang harus segera diimplementasikan.</p><p>Direktur sekolah menggelar rapat darurat untuk memastikan semua kebijakan dapat terlaksana dengan baik dan sesuai dengan target yang ditetapkan.</p><p>Rapat ini dihadiri oleh seluruh guru, staf, dan perwakilan komite sekolah untuk memastikan koordinasi yang optimal dalam pelaksanaan kebijakan baru.</p>',
                'category_id' => Category::where('slug', 'berita')->first()->id ?? 1,
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Purnatugas Penuh Haru: Guru Senior Kenang Perjalanan 30 Tahun di Sekolah',
                'slug' => 'purnatugas-penuh-haru-guru-senior-kenang-perjalanan-30-tahun',
                'excerpt' => 'Suasana haru sekaligus penuh kehangatan mewarnai acara pelepasan masa purnatugas salah satu guru senior yang telah mengabdi selama 30 tahun.',
                'body' => '<p>Suasana haru sekaligus penuh kehangatan mewarnai acara pelepasan masa purnatugas Drs. Budi Santoso MM, salah satu guru Matematika senior yang telah mengabdi selama 30 tahun di sekolah ini.</p><p>Dalam sambutannya, Pak Budi menyampaikan rasa syukur dan terima kasih kepada seluruh keluarga besar sekolah yang telah menjadi bagian dari perjalanan hidupnya.</p><p>"30 tahun bukan waktu yang singkat. Di sini saya belajar, berkembang, dan berbagi ilmu dengan ribuan siswa yang telah melewati kelas saya," ungkap Pak Budi dengan mata berkaca-kaca.</p>',
                'category_id' => Category::where('slug', 'berita')->first()->id ?? 1,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Session Demonstration: Cara Kreatif Sekolah Latih Siswa Berpikir Kritis dan Demokratis',
                'slug' => 'session-demonstration-cara-kreatif-latih-siswa-berpikir-kritis-demokratis',
                'excerpt' => 'Pembelajaran Pendidikan Kewarganegaraan di sekolah kali ini berlangsung berbeda dengan menerapkan metode session demonstration.',
                'body' => '<p>Pembelajaran Pendidikan Kewarganegaraan (PKN) di sekolah kali ini berlangsung berbeda. Guru PKN, Ibu Sari Indrawati S.Pd, menerapkan metode session demonstration untuk melatih kemampuan berpikir kritis dan demokratis siswa.</p><p>Metode ini melibatkan siswa dalam simulasi sidang parlemen mini di mana mereka harus mempresentasikan argumen, mendengarkan pandangan berbeda, dan mencapai kesepakatan melalui diskusi yang sehat.</p><p>"Melalui metode ini, siswa tidak hanya memahami teori demokrasi, tetapi juga mempraktikkannya langsung," jelas Ibu Sari.</p>',
                'category_id' => Category::where('slug', 'kegiatan')->first()->id ?? 1,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Sekolah Sukses Gelar Festival Seni dan Budaya Bersama Seniman Lokal',
                'slug' => 'sekolah-sukses-gelar-festival-seni-budaya-bersama-seniman-lokal',
                'excerpt' => 'Sekolah menggelar Festival Seni dan Budaya 2025 yang menampilkan berbagai kreativitas siswa dan kolaborasi dengan seniman lokal.',
                'body' => '<p>Sekolah menggelar Festival Seni dan Budaya 2025 pada Kamis, (16/03/25). Acara yang menampilkan berbagai kreativitas siswa ini juga menghadirkan kolaborasi menarik dengan seniman-seniman lokal.</p><p>Festival ini menampilkan berbagai pertunjukan mulai dari tari tradisional, musik modern, puisi, hingga pameran karya seni rupa siswa.</p><p>Kepala Sekolah menyampaikan apresiasi tinggi terhadap antusiasme siswa dan dukungan dari para seniman lokal yang turut menyemarakkan acara ini.</p>',
                'category_id' => Category::where('slug', 'kegiatan')->first()->id ?? 1,
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Study Kampus: Siswa Kunjungi Universitas Terkemuka',
                'slug' => 'study-kampus-siswa-kunjungi-universitas-terkemuka',
                'excerpt' => 'Sekolah mengadakan kunjungan atau study kampus ke beberapa universitas terkemuka sebagai bagian dari program orientasi karir siswa.',
                'body' => '<p>Sekolah mengadakan kunjungan atau study kampus ke Universitas Indonesia dan ITB pada minggu lalu. Sebanyak 150 siswa kelas XII mengikuti program ini sebagai bagian dari orientasi karir dan persiapan melanjutkan pendidikan tinggi.</p><p>Selama kunjungan, siswa mendapat kesempatan untuk mengikuti kuliah umum, berkeliling fasilitas kampus, dan berinteraksi langsung dengan mahasiswa dan dosen.</p><p>"Program ini sangat membantu siswa dalam menentukan pilihan jurusan dan universitas yang sesuai dengan minat dan bakat mereka," ungkap koordinator program, Bapak Ahmad Wijaya S.Pd.</p>',
                'category_id' => Category::where('slug', 'kegiatan')->first()->id ?? 1,
                'status' => 'published',
                'published_at' => now()->subDays(14),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Implementasi Teknologi AI dalam Pembelajaran: Masa Depan Pendidikan',
                'slug' => 'implementasi-teknologi-ai-dalam-pembelajaran-masa-depan-pendidikan',
                'excerpt' => 'Sekolah mulai mengintegrasikan teknologi Artificial Intelligence (AI) dalam proses pembelajaran untuk meningkatkan kualitas pendidikan.',
                'body' => '<p>Dalam rangka mengikuti perkembangan teknologi pendidikan global, sekolah telah mulai mengintegrasikan teknologi Artificial Intelligence (AI) dalam beberapa mata pelajaran.</p><p>Pilot project ini dimulai di mata pelajaran Matematika dan Bahasa Inggris, di mana AI digunakan untuk memberikan pembelajaran yang lebih personal sesuai dengan kemampuan masing-masing siswa.</p><p>Dr. Rina Kartika, konsultan pendidikan teknologi, menjelaskan bahwa AI dapat membantu guru dalam mengidentifikasi kelemahan dan kekuatan setiap siswa secara lebih akurat.</p>',
                'category_id' => Category::where('slug', 'artikel-ilmiah')->first()->id ?? 1,
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Pengumuman: Jadwal Ujian Akhir Semester Genap 2025',
                'slug' => 'pengumuman-jadwal-ujian-akhir-semester-genap-2025',
                'excerpt' => 'Pengumuman resmi jadwal pelaksanaan Ujian Akhir Semester Genap tahun ajaran 2024/2025 untuk seluruh tingkat.',
                'body' => '<p><strong>PENGUMUMAN RESMI</strong></p><p>Kepada seluruh siswa dan orang tua, dengan ini kami sampaikan jadwal pelaksanaan Ujian Akhir Semester Genap tahun ajaran 2024/2025:</p><ul><li><strong>Tanggal:</strong> 1-15 Juni 2025</li><li><strong>Waktu:</strong> 07.30 - 11.30 WIB</li><li><strong>Tempat:</strong> Ruang kelas masing-masing</li></ul><p><strong>Ketentuan:</strong></p><ul><li>Siswa wajib hadir 15 menit sebelum ujian dimulai</li><li>Membawa kartu peserta ujian dan alat tulis</li><li>Berpakaian seragam lengkap</li></ul><p>Untuk informasi lebih lanjut dapat menghubungi bagian akademik.</p>',
                'category_id' => Category::where('slug', 'pengumuman')->first()->id ?? 1,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'user_id' => $admin->id,
            ],
        ];

        foreach ($posts as $postData) {
            Post::firstOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }

        $this->command->info('Additional categories and posts created successfully!');
    }
}
