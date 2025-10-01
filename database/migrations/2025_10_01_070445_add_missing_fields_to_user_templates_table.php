<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            // Add missing fields for full template support
            if (!Schema::hasColumn('user_templates', 'status')) {
                $table->string('status')->default('inactive')->after('is_active');
            }
            if (!Schema::hasColumn('user_templates', 'version')) {
                $table->string('version')->default('1.0')->after('status');
            }
            if (!Schema::hasColumn('user_templates', 'settings')) {
                $table->json('settings')->nullable()->after('version');
            }

            // Make slug nullable to prevent constraint issues
            if (Schema::hasColumn('user_templates', 'slug')) {
                $table->string('slug')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            $table->dropColumn(['status', 'version', 'settings']);

            // Restore slug as required
            if (Schema::hasColumn('user_templates', 'slug')) {
                $table->string('slug')->nullable(false)->change();
            }
        });
    }
};
