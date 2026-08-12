<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Institution;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber(),
            'institution_id' => null,
            'role' => UserRole::VERIFIER->value,
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::SUPER_ADMIN->value,
            'institution_id' => null,
        ]);
    }

    public function institutionAdmin(?Institution $institution = null): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::INSTITUTION_ADMIN->value,
            'institution_id' => $institution?->id ?? Institution::factory(),
        ]);
    }

    public function agent(?Institution $institution = null): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::AGENT->value,
            'institution_id' => $institution?->id ?? Institution::factory(),
        ]);
    }

    public function verifier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::VERIFIER->value,
            'institution_id' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
