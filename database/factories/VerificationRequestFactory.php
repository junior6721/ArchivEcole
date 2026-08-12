<?php

namespace Database\Factories;

use App\Models\VerificationRequest;
use App\Models\Diploma;
use App\Models\Institution;
use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VerificationRequestFactory extends Factory
{
    protected $model = VerificationRequest::class;

    public function definition(): array
    {
        $diploma = Diploma::factory();

        return [
            'uuid' => Str::uuid(),
            'request_number' => VerificationRequest::generateRequestNumber(),
            'diploma_id' => $diploma,
            'institution_id' => $diploma->institution_id,
            'verifier_name' => $this->faker->name(),
            'verifier_email' => $this->faker->safeEmail(),
            'verifier_phone' => $this->faker->phoneNumber(),
            'diploma_number' => 'DIP-' . strtoupper(Str::random(10)),
            'student_name' => $this->faker->name(),
            'graduation_year' => date('Y'),
            'status' => VerificationStatus::PENDING->value,
            'result' => 'pending',
            'expires_at' => now()->addHours(24),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VerificationStatus::COMPLETED->value,
            'result' => 'authentic',
            'verification_date' => now(),
        ]);
    }

    public function notFound(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VerificationStatus::COMPLETED->value,
            'result' => 'not_found',
            'diploma_id' => null,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VerificationStatus::EXPIRED->value,
            'expires_at' => now()->subHours(1),
        ]);
    }
}
