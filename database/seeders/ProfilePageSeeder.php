<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfilePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create "Tentang Kita" page if it doesn't exist
        Page::updateOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang Kita',
                'slug' => 'tentang-kami',
                'body' => '
                    <div class="prose max-w-none">
                        <h2>Visi dan Misi</h2>

                        <h3>Visi</h3>
                        <p>Menjadi sekolah unggulan yang berkarakter, bermutu, dan berdaya saing global dengan tetap menjaga nilai-nilai luhur bangsa.</p>

                        <h3>Misi</h3>
                        <ul>
                            <li>Menyelenggarakan pendidikan yang berkualitas dan berkarakter</li>
                            <li>Mengembangkan potensi peserta didik secara optimal</li>
                            <li>Membangun budaya sekolah yang kondusif untuk pembelajaran</li>
                            <li>Menjalin kerjasama dengan berbagai pihak untuk meningkatkan mutu pendidikan</li>
                            <li>Mengembangkan teknologi informasi dalam proses pembelajaran</li>
                        </ul>

                        <h2>Sejarah</h2>
                        <p>Sekolah kami didirikan pada tahun 1990 dengan komitmen untuk memberikan pendidikan terbaik bagi generasi muda Indonesia. Dari tahun ke tahun, kami terus berkembang dan berinovasi dalam menyediakan layanan pendidikan yang berkualitas.</p>

                        <h2>Fasilitas</h2>
                        <p>Kami memiliki berbagai fasilitas pendukung pembelajaran yang modern dan lengkap, termasuk:</p>
                        <ul>
                            <li>Ruang kelas ber-AC dengan proyektor</li>
                            <li>Laboratorium IPA dan Komputer</li>
                            <li>Perpustakaan dengan koleksi buku yang lengkap</li>
                            <li>Lapangan olahraga</li>
                            <li>Aula serbaguna</li>
                            <li>Kantin sehat</li>
                        </ul>

                        <h2>Prestasi</h2>
                        <p>Sekolah kami bangga dengan berbagai prestasi yang telah diraih oleh siswa-siswi kami, baik di tingkat lokal, nasional, maupun internasional dalam berbagai bidang seperti akademik, olahraga, dan seni.</p>
                    </div>
                ',
                'is_pinned' => true,
            ]
        );

        // Create additional "Tentang Kita" sub pages
        Page::updateOrCreate(
            ['slug' => 'tentang-kami-visi-misi'],
            [
                'title' => 'Visi dan Misi',
                'slug' => 'tentang-kami-visi-misi',
                'body' => '
                    <div class="prose max-w-none">
                        <h2>Visi</h2>
                        <p class="text-lg font-medium text-blue-600 mb-6">Menjadi sekolah unggulan yang berkarakter, bermutu, dan berdaya saing global dengan tetap menjaga nilai-nilai luhur bangsa.</p>

                        <h2>Misi</h2>
                        <ol class="space-y-3">
                            <li>Menyelenggarakan pendidikan yang berkualitas dan berkarakter dengan mengintegrasikan nilai-nilai agama, moral, dan etika dalam setiap aspek pembelajaran.</li>
                            <li>Mengembangkan potensi peserta didik secara optimal melalui pendekatan pembelajaran yang inovatif, kreatif, dan berpusat pada siswa.</li>
                            <li>Membangun budaya sekolah yang kondusif untuk pembelajaran dengan menciptakan lingkungan yang aman, nyaman, dan inspiratif.</li>
                            <li>Menjalin kerjasama dengan berbagai pihak termasuk orang tua, masyarakat, dan institusi pendidikan lainnya untuk meningkatkan mutu pendidikan.</li>
                            <li>Mengembangkan teknologi informasi dan komunikasi dalam proses pembelajaran untuk mempersiapkan siswa menghadapi era digital.</li>
                            <li>Membina dan mengembangkan bakat serta minat siswa melalui kegiatan ekstrakurikuler yang beragam dan berkualitas.</li>
                        </ol>

                        <h2>Tujuan</h2>
                        <ul class="space-y-2">
                            <li>Menghasilkan lulusan yang beriman, bertakwa, berakhlak mulia, dan berkarakter kuat</li>
                            <li>Menciptakan lulusan yang memiliki kompetensi akademik yang unggul dan siap melanjutkan ke jenjang pendidikan yang lebih tinggi</li>
                            <li>Mengembangkan kemampuan siswa dalam berpikir kritis, kreatif, dan inovatif</li>
                            <li>Membekali siswa dengan keterampilan abad 21 yang diperlukan untuk sukses di masa depan</li>
                            <li>Menciptakan lingkungan belajar yang mendukung pengembangan potensi setiap siswa</li>
                        </ul>
                    </div>
                ',
                'is_pinned' => false,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'tentang-kami-sejarah'],
            [
                'title' => 'Sejarah Sekolah',
                'slug' => 'tentang-kami-sejarah',
                'body' => '
                    <div class="prose max-w-none">
                        <h2>Awal Berdiri</h2>
                        <p>Sekolah kami didirikan pada tahun 1990 atas prakarsa sekelompok tokoh masyarakat dan pendidik yang memiliki visi untuk mencerdaskan kehidupan bangsa. Dengan semangat gotong royong dan komitmen yang tinggi terhadap pendidikan, mereka mendirikan sekolah ini sebagai wadah untuk memberikan pendidikan berkualitas bagi anak-anak di daerah ini.</p>

                        <h2>Perkembangan</h2>
                        <p>Sejak didirikan, sekolah kami terus mengalami perkembangan yang pesat. Dimulai dengan hanya beberapa ruang kelas sederhana, kini sekolah kami telah berkembang menjadi institusi pendidikan yang modern dengan fasilitas lengkap dan standar nasional.</p>

                        <h3>Tahap Perkembangan:</h3>
                        <ul>
                            <li><strong>1990-1995:</strong> Periode awal dengan 6 ruang kelas dan 150 siswa</li>
                            <li><strong>1996-2000:</strong> Pembangunan gedung baru dan penambahan fasilitas laboratorium</li>
                            <li><strong>2001-2005:</strong> Akreditasi pertama dan pengembangan kurikulum</li>
                            <li><strong>2006-2010:</strong> Modernisasi fasilitas dan penerapan teknologi</li>
                            <li><strong>2011-2015:</strong> Ekspansi program ekstrakurikuler dan kemitraan</li>
                            <li><strong>2016-sekarang:</strong> Era digital dan pembelajaran berbasis teknologi</li>
                        </ul>

                        <h2>Pencapaian</h2>
                        <p>Selama perjalanan yang panjang ini, sekolah kami telah meraih berbagai prestasi dan pengakuan, baik dari pemerintah maupun masyarakat. Ribuan lulusan telah berhasil melanjutkan pendidikan ke perguruan tinggi terkemuka dan berkarya di berbagai bidang.</p>

                        <h2>Komitmen Masa Depan</h2>
                        <p>Memasuki era globalisasi dan digitalisasi, sekolah kami terus berkomitmen untuk menghadirkan pendidikan yang relevan dengan perkembangan zaman sambil tetap menjaga nilai-nilai luhur dan karakter bangsa.</p>
                    </div>
                ',
                'is_pinned' => false,
            ]
        );
    }
}
