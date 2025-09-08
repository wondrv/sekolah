<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user for CMS
        User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.local',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);
    }
}
