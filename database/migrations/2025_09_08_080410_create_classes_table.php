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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Class name like "X IPA 1"
            $table->string('level'); // TK, SD, SMP, SMA
            $table->foreignId('wali_id')->nullable()->constrained('staff')->onDelete('set null'); // Class teacher
            $table->string('year'); // Academic year like "2024/2025"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
