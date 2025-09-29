<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ValidateAdmin extends Command
{
    protected $signature = 'cms:validate-admin';
    protected $description = 'Validate admin user setup and display login info';

    public function handle(): int
    {
        $admin = User::where('email', 'admin@school.local')->first();

        if (!$admin) {
            $this->error('❌ Admin user not found!');
            $this->info('Run: php artisan cms:admin-reset');
            return self::FAILURE;
        }

        $this->info('✅ Admin user found');
        $this->table(['Field', 'Value'], [
            ['Email', $admin->email],
            ['Name', $admin->name],
            ['Role', $admin->role],
            ['Is Admin', $admin->is_admin ? 'Yes' : 'No'],
            ['isAdmin() method', $admin->isAdmin() ? 'Yes' : 'No'],
            ['Created', $admin->created_at],
        ]);

        $this->info('🌐 Login URLs:');
        $this->line('  • Admin: http://127.0.0.1:8000/admin/dashboard');
        $this->line('  • Quick: http://127.0.0.1:8000/admin/quick-login');
        $this->line('  • Emergency: http://127.0.0.1:8000/admin/emergency-reset-admin');

        return self::SUCCESS;
    }
}
