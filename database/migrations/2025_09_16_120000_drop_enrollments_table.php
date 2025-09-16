<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop enrollments table if it exists
        if (Schema::hasTable('enrollments')) {
            Schema::drop('enrollments');
        }
    }

    public function down(): void
    {
        // No-op: We don't recreate the enrollments table.
        // If needed, restore from version control.
    }
};
