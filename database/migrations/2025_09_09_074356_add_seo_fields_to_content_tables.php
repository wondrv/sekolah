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
        // Add SEO fields to posts
        Schema::table('posts', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
        });

        // Add SEO fields to pages
        Schema::table('pages', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
        });

        // Add SEO fields to events
        Schema::table('events', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
        });

        // Add SEO fields to galleries
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'og_image']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'og_image']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'og_image']);
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'og_image']);
        });
    }
};
