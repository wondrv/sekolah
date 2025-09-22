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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('registration_number')->unique();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('religion');
            $table->string('nationality')->default('Indonesia');
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            
            // Family Information
            $table->string('father_name');
            $table->string('father_occupation')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name');
            $table->string('mother_occupation')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_relation')->nullable();
            
            // Academic Information
            $table->string('previous_school')->nullable();
            $table->string('previous_school_address')->nullable();
            $table->year('graduation_year')->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            
            // Documents
            $table->string('photo')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('family_card')->nullable();
            $table->string('transcript')->nullable();
            $table->json('other_documents')->nullable();
            
            // Registration Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'enrolled'])->default('pending');
            $table->text('notes')->nullable();
            $table->datetime('registered_at')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'registered_at']);
            $table->index('graduation_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
