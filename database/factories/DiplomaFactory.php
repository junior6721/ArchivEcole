<?php

namespace Database\Factories;

use App\Models\Diploma;
use App\Models\Institution;
use App\Models\Student;
use App\Models\User;
use App\Enums\DiplomaStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DiplomaFactory extends Factory
{
    protected $model = Diploma::class;

    public function definition(): array
    {
        $institution = Institution::factory();
        $student = Student::factory()->for($institution);
        $user = User::factory()->institutionAdmin($institution);

        return [
            'uuid' => Str::uuid(),
            'unique_identifier' => Diploma::generateUniqueIdentifier(),
            'institution_id' => $institution,
            'student_id' => $student,
            'diploma_number' => 'DIP-' . strtoupper(Str::random(10)),
            'title' => $this->faker->jobTitle(),
            'major' => $this->faker->word(),
            'minor' => $this->faker->word(),
            'specialization' => $this->faker->word(),
            'graduation_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'academic_year' => date('Y') . '/' . (date('Y') + 1),
            'grade' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'mention' => $this->faker->randomElement(['sans', 'assez_bien', 'bien', 'tres_bien']),
            'status' => DiplomaStatus::ARCHIVED->value,
            'created_by_id' => $user,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DiplomaStatus::VERIFIED->value,
            'reviewed_by_id' => User::factory()->superAdmin(),
            'reviewed_at' => now(),
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DiplomaStatus::REVOKED->value,
        ]);
    }
}
