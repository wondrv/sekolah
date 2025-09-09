<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestAdminAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:admin-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin access and routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Admin Dashboard Setup...');

        // Test admin user exists
        $adminUser = \App\Models\User::where('is_admin', true)->first();
        if ($adminUser) {
            $this->info("✓ Admin user exists: {$adminUser->email}");
        } else {
            $this->error("✗ No admin user found");
        }

        // Test models
        $counts = [
            'Posts' => \App\Models\Post::count(),
            'Pages' => \App\Models\Page::count(),
            'Events' => \App\Models\Event::count(),
            'Galleries' => \App\Models\Gallery::count(),
            'Facilities' => \App\Models\Facility::count(),
            'Programs' => \App\Models\Program::count(),
            'Testimonials' => \App\Models\Testimonial::count(),
            'Achievements' => \App\Models\Achievement::count(),
        ];

        $this->info('Model counts:');
        foreach ($counts as $model => $count) {
            $this->line("  {$model}: {$count}");
        }

        // Test routes exist
        $routes = [
            'admin.dashboard',
            'admin.posts.index',
            'admin.pages.index',
            'admin.events.index',
            'admin.galleries.index',
            'admin.facilities.index',
            'admin.programs.index',
            'admin.testimonials.index',
            'admin.achievements.index',
        ];

        $this->info('Testing routes...');
        foreach ($routes as $route) {
            try {
                route($route);
                $this->info("✓ Route '{$route}' exists");
            } catch (\Exception $e) {
                $this->error("✗ Route '{$route}' missing: " . $e->getMessage());
            }
        }

        $this->info('Admin dashboard test completed!');
        return 0;
    }
}
