<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PpdbSetting;
use App\Models\PpdbCost;

class PpdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PPDB Settings
        $settings = [
            'brochure_enabled' => true,
            'brochure_title' => 'Download Brosur PPDB 2024/2025',
            'brochure_description' => 'Unduh brosur resmi Penerimaan Peserta Didik Baru untuk informasi lengkap tentang proses pendaftaran, persyaratan, dan jadwal kegiatan.',
            'brochure_format' => 'PDF',
            'brochure_size' => '2.8 MB',
            'costs_enabled' => true,
            'costs_title' => 'Rincian Biaya PPDB 2024/2025',
            'costs_description' => 'Berikut adalah rincian biaya untuk Penerimaan Peserta Didik Baru tahun akademik 2024/2025.',
        ];

        foreach ($settings as $key => $value) {
            PpdbSetting::set($key, $value);
        }

        // PPDB Cost Items
        $academic_year = PpdbCost::getCurrentAcademicYear();
        
        $costs = [
            // Registration Fees
            [
                'item_name' => 'Biaya Pendaftaran',
                'description' => 'Biaya administrasi pendaftaran PPDB',
                'amount' => 50000,
                'category' => 'pendaftaran',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 1,
                'academic_year' => $academic_year,
            ],
            [
                'item_name' => 'Biaya Tes Seleksi',
                'description' => 'Biaya pelaksanaan tes masuk dan wawancara',
                'amount' => 100000,
                'category' => 'pendaftaran',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 2,
                'academic_year' => $academic_year,
            ],

            // Educational Fees
            [
                'item_name' => 'Uang Pangkal',
                'description' => 'Uang pangkal masuk sekolah (dibayar sekali)',
                'amount' => 2500000,
                'category' => 'pendidikan',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 1,
                'academic_year' => $academic_year,
            ],
            [
                'item_name' => 'SPP Bulanan',
                'description' => 'Sumbangan Pembinaan Pendidikan per bulan',
                'amount' => 350000,
                'category' => 'pendidikan',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 2,
                'academic_year' => $academic_year,
            ],
            [
                'item_name' => 'Biaya Kegiatan',
                'description' => 'Biaya kegiatan ekstrakurikuler dan pengembangan',
                'amount' => 150000,
                'category' => 'pendidikan',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 3,
                'academic_year' => $academic_year,
            ],

            // Equipment & Materials
            [
                'item_name' => 'Seragam Sekolah',
                'description' => 'Paket seragam lengkap (3 stel)',
                'amount' => 400000,
                'category' => 'perlengkapan',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 1,
                'academic_year' => $academic_year,
            ],
            [
                'item_name' => 'Buku Pelajaran',
                'description' => 'Paket buku pelajaran semester 1',
                'amount' => 300000,
                'category' => 'perlengkapan',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 2,
                'academic_year' => $academic_year,
            ],
            [
                'item_name' => 'Alat Tulis dan ATK',
                'description' => 'Paket alat tulis dan keperluan sekolah',
                'amount' => 75000,
                'category' => 'perlengkapan',
                'is_mandatory' => false,
                'is_active' => true,
                'sort_order' => 3,
                'academic_year' => $academic_year,
            ],

            // Optional Services
            [
                'item_name' => 'Katering Sekolah',
                'description' => 'Layanan makan siang di sekolah (per bulan)',
                'amount' => 200000,
                'category' => 'layanan',
                'is_mandatory' => false,
                'is_active' => true,
                'sort_order' => 1,
                'academic_year' => $academic_year,
            ],
            [
                'item_name' => 'Transportasi Sekolah',
                'description' => 'Layanan antar jemput siswa (per bulan)',
                'amount' => 250000,
                'category' => 'layanan',
                'is_mandatory' => false,
                'is_active' => true,
                'sort_order' => 2,
                'academic_year' => $academic_year,
            ],
            [
                'item_name' => 'Kelas Tambahan',
                'description' => 'Program bimbingan belajar tambahan (per bulan)',
                'amount' => 180000,
                'category' => 'layanan',
                'is_mandatory' => false,
                'is_active' => true,
                'sort_order' => 3,
                'academic_year' => $academic_year,
            ],
        ];

        foreach ($costs as $cost) {
            PpdbCost::create($cost);
        }

        $this->command->info('PPDB data seeded successfully!');
    }
}
