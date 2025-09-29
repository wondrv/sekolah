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
        // Template categories for gallery organization
        Schema::create('template_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // SD, SMP, SMA, Universitas, Pesantren, Custom
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Hex color for UI
            $table->string('icon')->nullable(); // Icon class or image
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Template gallery - Pre-built templates ready to use
        Schema::create('template_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->foreignId('category_id')->constrained('template_categories')->onDelete('cascade');
            $table->string('preview_image')->nullable(); // Screenshot/preview
            $table->json('preview_images')->nullable(); // Multiple screenshots
            $table->json('template_data'); // Complete template structure
            $table->json('demo_content')->nullable(); // Sample content for demo
            $table->string('author')->default('System');
            $table->string('version', 10)->default('1.0.0');
            $table->json('features')->nullable(); // Features list
            $table->json('color_schemes')->nullable(); // Available color variations
            $table->integer('downloads')->default(0);
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->boolean('featured')->default(false);
            $table->boolean('premium')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'active']);
            $table->index(['featured', 'active']);
        });

        // User's installed templates
        Schema::create('user_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('gallery_template_id')->nullable()->constrained('template_gallery')->onDelete('set null');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->json('template_data'); // Template structure
            $table->string('source')->default('custom'); // gallery, custom, imported
            $table->boolean('is_active')->default(false); // Currently active template
            $table->json('customizations')->nullable(); // User modifications
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'is_active']);
        });

        // Template import/export history
        Schema::create('template_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_template_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('format')->default('json'); // json, zip
            $table->json('export_options')->nullable(); // Include content, images, etc.
            $table->string('file_path');
            $table->integer('file_size')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Extend existing templates table
        Schema::table('templates', function (Blueprint $table) {
            $table->foreignId('user_template_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('template_version', 10)->default('1.0.0')->after('sort_order');
            $table->json('metadata')->nullable()->after('template_version'); // Additional template info
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropForeign(['user_template_id']);
            $table->dropColumn(['user_template_id', 'template_version', 'metadata']);
        });

        Schema::dropIfExists('template_exports');
        Schema::dropIfExists('user_templates');
        Schema::dropIfExists('template_gallery');
        Schema::dropIfExists('template_categories');
    }
};
