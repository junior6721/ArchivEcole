<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['M', 'F', 'Other'])->nullable();
            $table->string('student_number')->nullable();
            $table->string('matricule')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['active', 'graduated', 'inactive'])->default('active');
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->unique(['institution_id', 'student_number']);
            $table->unique(['institution_id', 'matricule']);
            $table->index('status');
            $table->index('institution_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
