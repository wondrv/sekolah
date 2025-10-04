<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            // template_files already exists, only add template_type
            if (!Schema::hasColumn('user_templates', 'template_type')) {
                $table->string('template_type', 10)->default('blocks');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            if (Schema::hasColumn('user_templates', 'template_type')) {
                $table->dropColumn('template_type');
            }
        });
    }
};
