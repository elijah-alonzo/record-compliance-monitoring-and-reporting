<?php

namespace Database\Seeders;

use App\Features\Departments\Models\Department;
use App\Features\Faculties\Enums\EmploymentType;
use App\Features\Faculties\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->warn('No departments found. Please run DepartmentSeeder first.');

            return;
        }

        $faculties = [
            [
                'first_name' => 'Maria',
                'middle_name' => null,
                'last_name' => 'Shared Faculty',
                'employment_type' => EmploymentType::Shared->value,
                'department_id' => $departments->random()->id,
                'email' => 'maria.shared@spup.edu',
                'phone' => '+63 917 111 2222',
            ],
            [
                'first_name' => 'Serafin',
                'middle_name' => null,
                'last_name' => 'Gazzingan',
                'employment_type' => EmploymentType::FullTime->value,
                'department_id' => $departments->random()->id,
                'email' => 'serafin.gazzingan@spup.edu',
                'phone' => '+63 917 123 4567',
            ],
            [
                'first_name' => 'Rheychelle',
                'middle_name' => null,
                'last_name' => 'Antonio',
                'employment_type' => EmploymentType::FullTime->value,
                'department_id' => $departments->random()->id,
                'email' => 'rheychelle.antonio@spup.edu',
                'phone' => '+63 917 234 5678',
            ],
            [
                'first_name' => 'Sheena',
                'middle_name' => null,
                'last_name' => 'Gumarang',
                'employment_type' => EmploymentType::PartTime->value,
                'department_id' => $departments->random()->id,
                'email' => 'sheena.gumarang@spup.edu',
                'phone' => '+63 917 345 6789',
            ],
            [
                'first_name' => 'Dr. Marifel',
                'middle_name' => null,
                'last_name' => 'Kummer',
                'employment_type' => EmploymentType::FullTime->value,
                'department_id' => $departments->random()->id,
                'email' => 'marifel.kummer@spup.edu',
                'phone' => '+63 917 456 7890',
            ],
            [
                'first_name' => 'Justin',
                'middle_name' => null,
                'last_name' => 'Tan',
                'employment_type' => EmploymentType::PartTime->value,
                'department_id' => $departments->random()->id,
                'email' => 'justin.tan@spup.edu',
                'phone' => '+63 917 567 8901',
            ],
        ];

        foreach ($faculties as $faculty) {
            $faculty = Faculty::create($faculty);
            $faculty->courses()->sync($faculty->department->courses()->pluck('id'));
        }

        $this->command->info('Faculty seeder completed successfully.');
    }
}
