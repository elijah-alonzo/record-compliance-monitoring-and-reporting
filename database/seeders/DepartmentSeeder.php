<?php

namespace Database\Seeders;

use App\Features\Departments\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['code' => 'SITE', 'name' => 'School of Information Technology and Engineering', 'color' => 'purple'],
            ['code' => 'SBAHM', 'name' => 'School of Business, Accountancy, and Hospitality Management', 'color' => 'yellow'],
            ['code' => 'SNAHS', 'name' => 'School of Nursing and Allied Health Sciences', 'color' => 'red'],
            ['code' => 'SASTE', 'name' => 'School of Arts Sciences and Teacher Education', 'color' => 'blue'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
