<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $email = 'admin@school.local';
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Administrator',
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // default password
                'is_admin' => true,
                'role' => 'admin',
            ]);
            $this->command?->info("Admin user created: {$email} / password");
        } else {
            // Ensure admin flags consistent
            $user->update([
                'is_admin' => true,
                'role' => $user->role ?? 'admin',
            ]);
            $this->command?->warn('Admin user already exists; ensuring flags are set.');
        }
    }
}
