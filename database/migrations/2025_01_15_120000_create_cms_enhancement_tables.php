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
        // Template assignments for dynamic routing (guard against re-run)
        if (!Schema::hasTable('template_assignments')) {
            Schema::create('template_assignments', function (Blueprint $table) {
                $table->id();
                $table->string('route_pattern');
                $table->string('page_slug')->nullable();
                $table->foreignId('template_id')->constrained()->onDelete('cascade');
                $table->integer('priority')->default(0);
                $table->boolean('active')->default(true);
                $table->timestamps();
                $table->index(['route_pattern', 'active']);
                $table->index(['page_slug', 'active']);
            });
        }

        // Global theme settings
        if (!Schema::hasTable('theme_settings')) {
            Schema::create('theme_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->json('value');
                $table->string('category')->default('general');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->index('category');
            });
        }

        // Extend templates table for more flexibility (only if table exists)
        if (Schema::hasTable('templates')) {
            Schema::table('templates', function (Blueprint $table) {
                if (!Schema::hasColumn('templates', 'type')) {
                    $table->string('type')->default('page');
                }
                if (!Schema::hasColumn('templates', 'layout_settings')) {
                    $table->json('layout_settings')->nullable();
                }
                if (!Schema::hasColumn('templates', 'is_global')) {
                    $table->boolean('is_global')->default(false);
                }
                if (!Schema::hasColumn('templates', 'sort_order')) {
                    $table->integer('sort_order')->default(0);
                }
            });
        }

        // Add more block settings (only if table exists)
        if (Schema::hasTable('blocks')) {
            Schema::table('blocks', function (Blueprint $table) {
                if (!Schema::hasColumn('blocks', 'style_settings')) {
                    $table->json('style_settings')->nullable();
                }
                if (!Schema::hasColumn('blocks', 'css_class')) {
                    $table->string('css_class')->nullable();
                }
                if (!Schema::hasColumn('blocks', 'visible_desktop')) {
                    $table->boolean('visible_desktop')->default(true);
                }
                if (!Schema::hasColumn('blocks', 'visible_tablet')) {
                    $table->boolean('visible_tablet')->default(true);
                }
                if (!Schema::hasColumn('blocks', 'visible_mobile')) {
                    $table->boolean('visible_mobile')->default(true);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn(['style_settings', 'css_class', 'visible_desktop', 'visible_tablet', 'visible_mobile']);
        });

        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn(['type', 'layout_settings', 'is_global', 'sort_order']);
        });

        Schema::dropIfExists('theme_settings');
        Schema::dropIfExists('template_assignments');
    }
};
