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
        // Update menu items from 'Tentang Kita' to 'Tentang Kami'
        DB::table('menu_items')
            ->where('title', 'Tentang Kita')
            ->update(['title' => 'Tentang Kami']);

        // Update page titles from 'Tentang Kita' to 'Tentang Kami'
        DB::table('pages')
            ->where('title', 'Tentang Kita')
            ->update(['title' => 'Tentang Kami']);

        // Update page slugs from 'tentang-kita' to 'tentang-kami'
        DB::table('pages')
            ->where('slug', 'like', 'tentang-kita%')
            ->update([
                'slug' => DB::raw("REPLACE(slug, 'tentang-kita', 'tentang-kami')")
            ]);

        // Update menu item URLs from '/tentang-kita' to '/tentang-kami'
        DB::table('menu_items')
            ->where('url', 'like', '%tentang-kita%')
            ->update([
                'url' => DB::raw("REPLACE(url, 'tentang-kita', 'tentang-kami')")
            ]);

        // Update settings values containing 'tentang-kita'
        DB::table('settings')
            ->where('value', 'like', '%tentang-kita%')
            ->update([
                'value' => DB::raw("REPLACE(value, 'tentang-kita', 'tentang-kami')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse all changes
        DB::table('menu_items')
            ->where('title', 'Tentang Kami')
            ->update(['title' => 'Tentang Kita']);

        DB::table('pages')
            ->where('title', 'Tentang Kami')
            ->update(['title' => 'Tentang Kita']);

        DB::table('pages')
            ->where('slug', 'like', 'tentang-kami%')
            ->update([
                'slug' => DB::raw("REPLACE(slug, 'tentang-kami', 'tentang-kita')")
            ]);

        DB::table('menu_items')
            ->where('url', 'like', '%tentang-kami%')
            ->update([
                'url' => DB::raw("REPLACE(url, 'tentang-kami', 'tentang-kita')")
            ]);

        DB::table('settings')
            ->where('value', 'like', '%tentang-kami%')
            ->update([
                'value' => DB::raw("REPLACE(value, 'tentang-kami', 'tentang-kita')")
            ]);
    }
};
