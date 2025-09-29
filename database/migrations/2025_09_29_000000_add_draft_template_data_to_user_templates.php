<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('user_templates', 'draft_template_data')) {
                $table->json('draft_template_data')->nullable()->after('template_data');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            if (Schema::hasColumn('user_templates', 'draft_template_data')) {
                $table->dropColumn('draft_template_data');
            }
        });
    }
};
