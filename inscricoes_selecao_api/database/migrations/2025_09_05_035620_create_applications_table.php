<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('motivation')->nullable();
            $table->timestamps();
            
            // Evitar candidaturas duplicadas
            $table->unique(['user_id', 'program_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};