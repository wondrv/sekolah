<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TemplateAssignment;
use App\Models\ThemeSetting;
use App\Models\Template;

class CmsSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default theme settings
        $this->createThemeSettings();
        
        // Create template assignments if templates exist
        $this->createTemplateAssignments();
    }
    
    /**
     * Create default theme settings
     */
    private function createThemeSettings()
    {
        $themeSettings = [
            'colors' => [
                'primary' => '#3B82F6',
                'secondary' => '#10B981',
                'accent' => '#F59E0B',
                'background' => '#FFFFFF',
                'text' => '#111827',
                'text_muted' => '#6B7280',
            ],
            'typography' => [
                'font_family' => 'Inter',
                'heading_font' => '',
                'font_size_base' => '16px',
                'line_height' => '1.6',
            ],
            'spacing' => [
                'container_max_width' => '1200px',
                'section_padding' => '60px',
                'border_radius' => '8px',
            ],
            'navigation' => [
                'style' => 'horizontal',
                'position' => 'static',
            ],
        ];

        foreach ($themeSettings as $category => $settings) {
            ThemeSetting::updateOrCreate(
                ['key' => $category],
                ['value' => json_encode($settings)]
            );
        }
    }
    
    /**
     * Create sample template assignments
     */
    private function createTemplateAssignments()
    {
        // Check if we have any templates
        $homeTemplate = Template::where('name', 'like', '%Home%')->first();
        
        if ($homeTemplate) {
            TemplateAssignment::updateOrCreate(
                ['route_pattern' => 'home'],
                [
                    'template_id' => $homeTemplate->id,
                    'page_slug' => null,
                    'priority' => 10,
                    'active' => true,
                ]
            );
        }
        
        // Create a wildcard assignment for any template
        $defaultTemplate = Template::first();
        
        if ($defaultTemplate) {
            TemplateAssignment::updateOrCreate(
                ['route_pattern' => '*'],
                [
                    'template_id' => $defaultTemplate->id,
                    'page_slug' => null,
                    'priority' => 1,
                    'active' => false, // Disabled by default
                ]
            );
        }
    }
}
