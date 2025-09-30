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
        Schema::table('blocks', function (Blueprint $table) {
            // Add missing columns that the Block model expects
            if (!Schema::hasColumn('blocks', 'name')) {
                $table->string('name')->nullable()->after('type');
            }
            if (!Schema::hasColumn('blocks', 'content')) {
                $table->json('content')->nullable()->after('name');
            }
            if (!Schema::hasColumn('blocks', 'settings')) {
                $table->json('settings')->nullable()->after('content');
            }
            if (!Schema::hasColumn('blocks', 'style_settings')) {
                $table->json('style_settings')->nullable()->after('data');
            }
            if (!Schema::hasColumn('blocks', 'css_class')) {
                $table->string('css_class')->nullable()->after('style_settings');
            }
            if (!Schema::hasColumn('blocks', 'visible_desktop')) {
                $table->boolean('visible_desktop')->default(true)->after('css_class');
            }
            if (!Schema::hasColumn('blocks', 'visible_tablet')) {
                $table->boolean('visible_tablet')->default(true)->after('visible_desktop');
            }
            if (!Schema::hasColumn('blocks', 'visible_mobile')) {
                $table->boolean('visible_mobile')->default(true)->after('visible_tablet');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'content',
                'settings',
                'style_settings',
                'css_class',
                'visible_desktop',
                'visible_tablet',
                'visible_mobile'
            ]);
        });
    }
};
