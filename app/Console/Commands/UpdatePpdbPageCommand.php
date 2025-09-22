<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;

class UpdatePpdbPageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'page:update-ppdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update PPDB page to use correct block names';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = Page::where('slug', 'ppdb')->first();
        
        if (!$page) {
            $this->error('PPDB page not found!');
            return 1;
        }

        // Update page content with correct block names
        $content = [
            [
                'type' => 'hero',
                'settings' => [
                    'title' => 'Penerimaan Peserta Didik Baru 2024/2025',
                    'subtitle' => 'Bergabunglah dengan keluarga besar sekolah kami dan wujudkan masa depan yang cerah bersama-sama.',
                    'button_text' => 'Lihat Info Lengkap',
                    'button_url' => '#info'
                ]
            ],
            [
                'type' => 'ppdb-brochure',
                'settings' => []
            ],
            [
                'type' => 'ppdb-cost-table', 
                'settings' => []
            ],
            [
                'type' => 'rich_text',
                'settings' => [
                    'content' => '<h3>Persyaratan Pendaftaran</h3><ul><li>Fotokopi Ijazah/STTB yang telah dilegalisir</li><li>Fotokopi raport kelas 6 semester 1 dan 2</li><li>Fotokopi akta kelahiran</li><li>Fotokopi kartu keluarga</li><li>Pas foto 3x4 sebanyak 4 lembar</li></ul><h3>Jadwal Pendaftaran</h3><p>Pendaftaran dibuka mulai tanggal <strong>1 Mei 2024</strong> hingga <strong>30 Juni 2024</strong>. Tes seleksi akan dilaksanakan pada tanggal <strong>5-7 Juli 2024</strong>.</p>'
                ]
            ]
        ];

        $page->content_json = json_encode($content);
        $page->save();

        $this->info("PPDB page updated successfully with correct block names!");
        $this->info("Page URL: " . url('/pages/' . $page->slug));
        
        return 0;
    }
}
