<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\Section;
use App\Models\Block;

class HomepageEnhancementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $template = Template::where('slug', 'homepage')->first();
        if (!$template) {
            $this->command->warn('Homepage template not found. Skipping enhancements.');
            return;
        }

        // Ensure Gallery section exists
        $gallerySection = Section::firstOrCreate(
            [
                'template_id' => $template->id,
                'key' => 'gallery',
            ],
            [
                'name' => 'Gallery',
                'order' => 5,
                'active' => true,
            ]
        );

        // Create gallery teaser block if not present
        $hasGalleryBlock = Block::where('section_id', $gallerySection->id)
            ->where('type', 'gallery_teaser')
            ->exists();

        if (!$hasGalleryBlock) {
            Block::create([
                'section_id' => $gallerySection->id,
                'type' => 'gallery_teaser',
                'data' => [
                    'title' => 'Galeri Kegiatan',
                    'background_color' => 'bg-white',
                    'limit' => 6,
                    'show_more_link' => true,
                ],
                'order' => 1,
                'active' => true,
            ]);
        }

        // Optionally, push CTA after Gallery
        $ctaSection = Section::where('template_id', $template->id)->where('key', 'cta')->first();
        if ($ctaSection && $ctaSection->order <= 5) {
            $ctaSection->order = 6;
            $ctaSection->save();
        }

        $this->command->info('Homepage enhancements seeded: Gallery section with teaser block.');
    }
}
