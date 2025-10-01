<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            $table->json('template_files')->nullable();
            $table->string('template_type', 10)->default('blocks');
        });
    }

    public function down(): void
    {
        Schema::table('user_templates', function (Blueprint $table) {
            $table->dropColumn(['template_files', 'template_type']);
        });
    }
};
