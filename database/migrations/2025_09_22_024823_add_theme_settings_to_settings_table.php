<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default theme settings
        DB::table('settings')->insert([
            ['key' => 'active_theme', 'value' => 'default'],
            ['key' => 'theme_config', 'value' => json_encode([])],
            ['created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['active_theme', 'theme_config'])->delete();
    }
};
