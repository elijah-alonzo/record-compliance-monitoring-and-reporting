<?php

namespace Database\Seeders;

use App\Features\Courses\Models\Course;
use App\Features\Departments\Models\Department;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'department' => 'SNAHS',
                'code' => 'BSN',
                'name' => 'Bachelor of Science in Nursing',
                'description' => null,
            ],
            [
                'department' => 'SITE',
                'code' => 'BSIT',
                'name' => 'Bachelor of Science in Information Technology',
                'description' => null,
            ],
        ];

        foreach ($courses as $course) {
            $course['department_id'] = Department::where('code', $course['department'])->value('id');
            unset($course['department']);

            Course::create($course);
        }

        $this->command->info('Course seeder completed successfully.');
    }
}
