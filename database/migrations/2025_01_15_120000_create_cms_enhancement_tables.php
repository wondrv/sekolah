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
        // Template assignments for dynamic routing
        Schema::create('template_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('route_pattern'); // e.g., 'home', 'pages.*', 'posts.show'
            $table->string('page_slug')->nullable(); // For specific pages
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->integer('priority')->default(0); // Higher priority takes precedence
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['route_pattern', 'active']);
            $table->index(['page_slug', 'active']);
        });

        // Global theme settings
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'primary_color', 'typography', 'spacing'
            $table->json('value'); // Store complex settings as JSON
            $table->string('category')->default('general'); // colors, typography, layout, etc.
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('category');
        });

        // Extend templates table for more flexibility
        Schema::table('templates', function (Blueprint $table) {
            $table->string('type')->default('page'); // page, post, archive, etc.
            $table->json('layout_settings')->nullable(); // Container width, sidebar, etc.
            $table->boolean('is_global')->default(false); // For header/footer templates
            $table->integer('sort_order')->default(0);
        });

        // Add more block settings
        Schema::table('blocks', function (Blueprint $table) {
            $table->json('style_settings')->nullable(); // Custom CSS, margins, padding
            $table->string('css_class')->nullable(); // Custom CSS classes
            $table->boolean('visible_desktop')->default(true);
            $table->boolean('visible_tablet')->default(true);
            $table->boolean('visible_mobile')->default(true);
        });
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