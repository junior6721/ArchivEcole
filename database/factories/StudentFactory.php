<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'institution_id' => Institution::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'gender' => $this->faker->randomElement(['M', 'F']),
            'student_number' => 'STU-' . strtoupper(Str::random(8)),
            'matricule' => strtoupper(Str::random(10)),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => 'active',
        ];
    }

    public function graduated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'graduated',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
