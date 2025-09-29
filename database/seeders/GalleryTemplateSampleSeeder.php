<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateGallery;
use App\Models\TemplateCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GalleryTemplateSampleSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if not exists
        $adminUser = User::firstOrCreate([
            'email' => 'admin@school.local'
        ], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create template categories if not exist
        $academicCategory = TemplateCategory::firstOrCreate([
            'slug' => 'academic'
        ], [
            'name' => 'Academic',
            'description' => 'Templates focused on academic institutions',
            'color' => '#3B82F6',
            'active' => true,
            'sort_order' => 1
        ]);

        $modernCategory = TemplateCategory::firstOrCreate([
            'slug' => 'modern'
        ], [
            'name' => 'Modern',
            'description' => 'Contemporary and clean designs',
            'color' => '#10B981',
            'active' => true,
            'sort_order' => 2
        ]);

        // Create sample templates
        $templates = [
            [
                'name' => 'Academic Pro',
                'slug' => 'academic-pro',
                'description' => 'Professional template for academic institutions with modern design',
                'category_id' => $academicCategory->id,
                'preview_image' => null,
                'preview_images' => [
                    'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                    'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80'
                ],
                'template_data' => [
                    'templates' => [
                        [
                            'name' => 'Homepage',
                            'slug' => 'homepage',
                            'sections' => [
                                [
                                    'name' => 'Hero Section',
                                    'order' => 0,
                                    'blocks' => [
                                        [
                                            'type' => 'hero',
                                            'order' => 0,
                                            'active' => true,
                                            'data' => [
                                                'title' => 'Welcome to Our School',
                                                'subtitle' => 'Excellence in Education Since 1990',
                                                'button_text' => 'Learn More',
                                                'button_url' => '#about'
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'name' => 'About Section',
                                    'order' => 1,
                                    'blocks' => [
                                        [
                                            'type' => 'card-grid',
                                            'order' => 0,
                                            'active' => true,
                                            'data' => [
                                                'title' => 'Why Choose Us',
                                                'cards' => [
                                                    [
                                                        'title' => 'Quality Education',
                                                        'description' => 'Top-notch curriculum and experienced teachers'
                                                    ],
                                                    [
                                                        'title' => 'Modern Facilities',
                                                        'description' => 'State-of-the-art classrooms and laboratories'
                                                    ],
                                                    [
                                                        'title' => 'Student Support',
                                                        'description' => 'Comprehensive guidance and counseling services'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'author' => 'SchoolCMS Team',
                'version' => '1.0.0',
                'features' => [
                    'Responsive Design',
                    'SEO Optimized',
                    'Easy Customization',
                    'Multiple Color Schemes'
                ],
                'color_schemes' => [
                    'default' => ['primary' => '#3B82F6', 'secondary' => '#10B981'],
                    'dark' => ['primary' => '#1F2937', 'secondary' => '#374151']
                ],
                'downloads' => 156,
                'rating' => 4.8,
                'featured' => true,
                'premium' => false,
                'active' => true
            ],
            [
                'name' => 'Modern School',
                'slug' => 'modern-school',
                'description' => 'Clean and modern template perfect for contemporary schools',
                'category_id' => $modernCategory->id,
                'preview_image' => null,
                'preview_images' => [
                    'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80'
                ],
                'template_data' => [
                    'templates' => [
                        [
                            'name' => 'Homepage',
                            'slug' => 'homepage',
                            'sections' => [
                                [
                                    'name' => 'Hero Section',
                                    'order' => 0,
                                    'blocks' => [
                                        [
                                            'type' => 'hero',
                                            'order' => 0,
                                            'active' => true,
                                            'data' => [
                                                'title' => 'Future-Ready Education',
                                                'subtitle' => 'Preparing students for tomorrow\'s challenges',
                                                'button_text' => 'Explore Programs',
                                                'button_url' => '#programs'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'author' => 'SchoolCMS Team',
                'version' => '1.0.0',
                'features' => [
                    'Modern UI/UX',
                    'Mobile First',
                    'Fast Loading',
                    'Accessibility Ready'
                ],
                'downloads' => 89,
                'rating' => 4.6,
                'featured' => false,
                'premium' => false,
                'active' => true
            ]
        ];

        foreach ($templates as $templateData) {
            TemplateGallery::firstOrCreate([
                'slug' => $templateData['slug']
            ], $templateData);
        }

        $this->command->info('Sample template gallery data created successfully!');
        $this->command->info('Admin user: admin@school.local / password');
    }
}
