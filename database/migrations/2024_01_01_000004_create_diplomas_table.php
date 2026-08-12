<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\DiplomaStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diplomas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('unique_identifier')->unique();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('diploma_number');
            $table->string('title');
            $table->string('major')->nullable();
            $table->string('minor')->nullable();
            $table->string('specialization')->nullable();
            $table->date('graduation_date');
            $table->string('academic_year');
            $table->string('grade')->nullable();
            $table->enum('mention', ['sans', 'assez_bien', 'bien', 'tres_bien', 'excellent'])->nullable();
            $table->string('status')->default(DiplomaStatus::ARCHIVED->value);
            $table->string('file_path')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('qr_code_token')->nullable()->unique();
            $table->text('qr_code_data')->nullable();
            $table->foreignId('created_by_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('reviewed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->unique(['institution_id', 'diploma_number']);
            $table->index('status');
            $table->index('institution_id');
            $table->index('student_id');
            $table->index('unique_identifier');
            $table->index('qr_code_token');
            $table->index('graduation_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diplomas');
    }
};
