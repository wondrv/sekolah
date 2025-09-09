<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedSampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:sample-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed sample data for testing admin panel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding sample data for admin panel testing...');

        // Get admin user
        $adminUser = \App\Models\User::where('is_admin', true)->first();

        // Create sample categories
        $category = \App\Models\Category::firstOrCreate([
            'name' => 'Pengumuman',
            'slug' => 'pengumuman'
        ]);

        // Create sample posts
        $this->info('Creating sample posts...');
        \App\Models\Post::firstOrCreate([
            'slug' => 'selamat-datang-di-website-sekolah'
        ], [
            'title' => 'Selamat Datang di Website Sekolah',
            'excerpt' => 'Selamat datang di website resmi sekolah kami.',
            'body' => 'Ini adalah artikel pertama di website sekolah. Website ini dibuat untuk memberikan informasi terkini tentang kegiatan sekolah.',
            'category_id' => $category->id,
            'user_id' => $adminUser->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Create sample testimonials
        $this->info('Creating sample testimonials...');
        \App\Models\Testimonial::firstOrCreate([
            'name' => 'Budi Santoso',
            'role' => 'Alumni'
        ], [
            'content' => 'Sekolah ini memberikan pendidikan terbaik. Saya sangat berterima kasih atas semua ilmu yang saya dapatkan.',
            'graduation_year' => 2020,
            'rating' => 5,
            'is_featured' => true,
            'is_active' => true,
        ]);

        // Create sample achievements
        $this->info('Creating sample achievements...');
        \App\Models\Achievement::firstOrCreate([
            'title' => 'Juara 1 Olimpiade Matematika'
        ], [
            'description' => 'Siswa kami meraih juara 1 dalam Olimpiade Matematika tingkat provinsi.',
            'category' => 'Akademik',
            'level' => 'Provinsi',
            'achievement_date' => now()->subMonths(2),
            'achiever_name' => 'Tim Matematika',
            'is_featured' => true,
        ]);

        $this->info('Sample data created successfully!');
        $this->info('You can now test the admin panel with sample content.');
        $this->info('Login with: admin@sekolah.local / password123');

        return 0;
    }
}
