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
        Schema::table('templates', function (Blueprint $table) {
            // Add missing columns that should have been added by template gallery system migration
            if (!Schema::hasColumn('templates', 'user_template_id')) {
                $table->foreignId('user_template_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('templates', 'type')) {
                $table->string('type')->default('page')->after('description');
            }
            if (!Schema::hasColumn('templates', 'layout_settings')) {
                $table->json('layout_settings')->nullable()->after('type');
            }
            if (!Schema::hasColumn('templates', 'is_global')) {
                $table->boolean('is_global')->default(false)->after('layout_settings');
            }
            if (!Schema::hasColumn('templates', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_global');
            }
            if (!Schema::hasColumn('templates', 'template_version')) {
                $table->string('template_version', 10)->default('1.0.0')->after('sort_order');
            }
            if (!Schema::hasColumn('templates', 'metadata')) {
                $table->json('metadata')->nullable()->after('template_version');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropForeign(['user_template_id']);
            $table->dropColumn([
                'user_template_id',
                'type',
                'layout_settings',
                'is_global',
                'sort_order',
                'template_version',
                'metadata'
            ]);
        });
    }
};
