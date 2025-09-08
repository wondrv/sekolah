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
        // Drop staff and PPDB related tables
        Schema::dropIfExists('ppdb_applicants');
        Schema::dropIfExists('students');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('classes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This would need to recreate the tables
        // For simplicity, we're not implementing the down method
        // as these tables are being permanently removed
    }
};
