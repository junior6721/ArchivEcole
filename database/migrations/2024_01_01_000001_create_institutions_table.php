<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('BJ');
            $table->string('registration_number')->nullable()->unique();
            $table->string('tax_id')->nullable()->unique();
            $table->string('logo_path')->nullable();
            $table->string('seal_path')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('subscription_tier')->default('free');
            $table->integer('diplomas_limit')->nullable();
            $table->integer('users_limit')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('status');
            $table->index('subscription_tier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
