<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserTemplate;
use App\Models\User;

class TestUserTemplateSeeder extends Seeder
{
    public function run()
    {
        // Ensure admin user exists
        $admin = User::firstOrCreate([
            'email' => 'admin@school.local'
        ], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create test template with proper structure
        UserTemplate::create([
            'user_id' => $admin->id,
            'name' => 'Test Template',
            'slug' => 'test-template',
            'description' => 'Template for testing builder',
            'template_data' => [
                'templates' => [
                    [
                        'name' => 'Homepage',
                        'slug' => 'homepage',
                        'description' => 'Main homepage template',
                        'type' => 'page',
                        'active' => true,
                        'sections' => [
                            [
                                'name' => 'Header Section',
                                'order' => 0,
                                'settings' => ['background' => 'light'],
                                'blocks' => [
                                    [
                                        'type' => 'hero',
                                        'order' => 0,
                                        'active' => true,
                                        'data' => [
                                            'title' => 'Welcome to Our School',
                                            'subtitle' => 'Excellence in Education',
                                            'button_text' => 'Learn More',
                                            'button_url' => '#about'
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'assignments' => [
                            [
                                'route_pattern' => 'home',
                                'priority' => 10,
                                'active' => true
                            ]
                        ]
                    ]
                ]
            ],
            'source' => 'custom',
            'is_active' => false
        ]);

        $this->command->info('Test UserTemplate created with proper structure!');
    }
}
