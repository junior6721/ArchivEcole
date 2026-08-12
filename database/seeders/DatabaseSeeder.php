<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\User;
use App\Models\Student;
use App\Models\Diploma;
use App\Enums\UserRole;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::factory()
            ->superAdmin()
            ->create([
                'name' => 'Admin ArchivEcole',
                'email' => 'admin@archivecole.com',
                'password' => bcrypt('password'),
            ]);

        // Create test institutions with users and data
        Institution::factory(3)
            ->active()
            ->afterCreating(function (Institution $institution) {
                // Create institution admin
                User::factory()
                    ->institutionAdmin($institution)
                    ->create([
                        'name' => "{$institution->name} Admin",
                        'email' => "admin@{$institution->slug}.archivecole.com",
                    ]);

                // Create agents
                User::factory(2)
                    ->agent($institution)
                    ->create();

                // Create students
                $students = Student::factory(10)
                    ->for($institution)
                    ->create();

                // Create diplomas for students
                $students->each(function (Student $student) use ($institution) {
                    Diploma::factory(2)
                        ->for($student)
                        ->for($institution)
                        ->create();
                });
            })
            ->create();

        // Create some public verifiers
        User::factory(5)
            ->verifier()
            ->create();
    }
}
