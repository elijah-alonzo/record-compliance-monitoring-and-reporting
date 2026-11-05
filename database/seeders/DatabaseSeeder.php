<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            DepartmentSeeder::class,
            CourseSeeder::class,
            FacultySeeder::class,
            SubjectSeeder::class,
            SectionSeeder::class,
            SubmissionDeadlineSeeder::class,
            LoadSeeder::class,
        ]);
    }
}
