<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_template_id')->constrained()->onDelete('cascade');
            $table->string('type', 30); // activate | publish_draft | manual_restore
            $table->json('snapshot'); // snapshot of template_data + meta
            $table->string('note')->nullable();
            $table->timestamps();
            $table->index(['user_template_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_revisions');
    }
};
