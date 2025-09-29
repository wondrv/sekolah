<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TemplateGallery;
use App\Models\UserTemplate;

class DefaultUserTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $adminUser = User::where('email', 'admin@school.local')->first();

        if (!$adminUser) {
            $this->command->error('Admin user not found. Please run user seeder first.');
            return;
        }

        // Get the first template from gallery (Rainbow Kids)
        $galleryTemplate = TemplateGallery::where('slug', 'rainbow-kids')->first();

        if (!$galleryTemplate) {
            $this->command->error('Gallery template not found. Please run TemplateGallerySeeder first.');
            return;
        }

        // Check if user already has an active template
        $existingTemplate = UserTemplate::where('user_id', $adminUser->id)
            ->where('is_active', true)
            ->first();

        if ($existingTemplate) {
            $this->command->info('Admin user already has active template: ' . $existingTemplate->name);
            return;
        }

        // Create user template from gallery
        $userTemplate = $galleryTemplate->createUserTemplate($adminUser->id, [
            'installed_at' => now(),
            'auto_activated' => true,
        ]);

        // Activate template
        $userTemplate->activate();

        $this->command->info('Successfully created and activated template: ' . $userTemplate->name);
        $this->command->info('Admin user now has active template for Pure CMS rendering');
    }
}
