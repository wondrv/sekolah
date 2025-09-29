<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminUser extends Command
{
    protected $signature = 'cms:admin-reset {--email=admin@school.local} {--password=password}';
    protected $description = 'Create or reset the primary admin user and print credentials';

    public function handle(): int
    {
        $email = $this->option('email');
        $password = $this->option('password');

        $user = User::firstOrNew(['email' => $email]);
        $user->name = 'Administrator';
        $user->role = 'admin';
        $user->is_admin = true;
        $user->email_verified_at = now();
        $user->password = Hash::make($password);
        $user->save();

        $this->info("Admin user ready: {$email}");
        $this->info("Password: {$password}");
        $this->warn('Change this password after login.');

        return self::SUCCESS;
    }
}
