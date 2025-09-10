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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();

            // Student Information
            $table->string('student_name');
            $table->string('student_nik')->unique();
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('religion');
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Parent Information
            $table->string('father_name');
            $table->string('father_occupation')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name');
            $table->string('mother_occupation')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();

            // Academic Information
            $table->string('previous_school')->nullable();
            $table->string('desired_program');
            $table->year('academic_year');

            // Documents
            $table->string('birth_certificate_path')->nullable();
            $table->string('family_card_path')->nullable();
            $table->string('report_card_path')->nullable();
            $table->string('photo_path')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'enrolled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->index(['status', 'academic_year']);
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
