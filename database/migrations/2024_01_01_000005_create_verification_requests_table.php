<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\VerificationStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('request_number')->unique();
            $table->foreignId('diploma_id')->nullable()->constrained('diplomas')->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained('institutions')->nullOnDelete();
            $table->string('verifier_name')->nullable();
            $table->string('verifier_email')->nullable();
            $table->string('verifier_phone')->nullable();
            $table->string('diploma_number');
            $table->string('student_name');
            $table->string('graduation_year');
            $table->string('status')->default(VerificationStatus::PENDING->value);
            $table->enum('result', ['authentic', 'not_found', 'insufficient_data', 'pending'])->default('pending');
            $table->foreignId('payment_transaction_id')->nullable()->constrained('payment_transactions')->nullOnDelete();
            $table->timestamp('verification_date')->nullable();
            $table->foreignId('verified_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->json('audit_data')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('status');
            $table->index('result');
            $table->index('diploma_id');
            $table->index('institution_id');
            $table->index('request_number');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_requests');
    }
};
