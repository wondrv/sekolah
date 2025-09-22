<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;

class CreatePpdbPageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'page:create-ppdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a sample PPDB page with block components';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if page already exists
        if (Page::where('slug', 'ppdb')->exists()) {
            $this->error('PPDB page already exists!');
            return 1;
        }

        // PPDB page content blocks
        $ppdbContent = [
            [
                'type' => 'hero',
                'data' => [
                    'title' => 'Penerimaan Peserta Didik Baru 2024/2025',
                    'subtitle' => 'Bergabunglah dengan keluarga besar sekolah kami dan wujudkan masa depan yang cerah bersama-sama.',
                    'cta_text' => 'Lihat Info Lengkap',
                    'cta_link' => '#info'
                ]
            ],
            [
                'type' => 'ppdb_brochure',
                'data' => []
            ],
            [
                'type' => 'ppdb_cost_table', 
                'data' => []
            ],
            [
                'type' => 'rich-text',
                'data' => [
                    'content' => '<h3>Persyaratan Pendaftaran</h3><ul><li>Fotokopi Ijazah/STTB yang telah dilegalisir</li><li>Fotokopi raport kelas 6 semester 1 dan 2</li><li>Fotokopi akta kelahiran</li><li>Fotokopi kartu keluarga</li><li>Pas foto 3x4 sebanyak 4 lembar</li></ul><h3>Jadwal Pendaftaran</h3><p>Pendaftaran dibuka mulai tanggal <strong>1 Mei 2024</strong> hingga <strong>30 Juni 2024</strong>. Tes seleksi akan dilaksanakan pada tanggal <strong>5-7 Juli 2024</strong>.</p>'
                ]
            ]
        ];

        // Create PPDB page
        $page = Page::create([
            'title' => 'Penerimaan Peserta Didik Baru (PPDB)',
            'slug' => 'ppdb',
            'content' => 'Halaman ini menampilkan informasi lengkap tentang PPDB menggunakan sistem blok.',
            'content_json' => json_encode($ppdbContent),
            'meta_title' => 'PPDB 2024/2025 - Penerimaan Peserta Didik Baru',
            'meta_description' => 'Informasi lengkap Penerimaan Peserta Didik Baru (PPDB) 2024/2025. Download brosur dan lihat rincian biaya pendaftaran.',
            'is_published' => true,
            'published_at' => now()
        ]);

        $this->info("PPDB page created successfully with ID: {$page->id}");
        $this->info("URL: " . url('/pages/' . $page->slug));
        
        return 0;
    }
}
