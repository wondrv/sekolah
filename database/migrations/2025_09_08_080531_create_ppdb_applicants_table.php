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
        Schema::create('ppdb_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nisn')->nullable();
            $table->date('birthdate');
            $table->text('address');
            $table->string('parent_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('level_applied'); // TK, SD, SMP, SMA
            $table->string('major_applied')->nullable(); // For SMA level
            $table->string('docs_path')->nullable(); // Uploaded documents
            $table->enum('status', ['pending', 'verified', 'accepted', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_applicants');
    }
};
