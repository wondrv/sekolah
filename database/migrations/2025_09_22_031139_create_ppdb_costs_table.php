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
        Schema::create('ppdb_costs', function (Blueprint $table) {
            $table->id();
            $table->string('item_name'); // Nama item biaya (e.g., "Biaya Pendaftaran", "SPP Bulanan")
            $table->text('description')->nullable(); // Deskripsi item
            $table->decimal('amount', 15, 2); // Jumlah biaya
            $table->string('category')->default('pendaftaran'); // kategori: pendaftaran, spp, seragam, dll
            $table->boolean('is_mandatory')->default(true); // Wajib atau opsional
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // Urutan tampilan
            $table->string('academic_year')->nullable(); // Tahun ajaran
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index(['academic_year', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_costs');
    }
};
