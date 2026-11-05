<?php

namespace Database\Seeders;

use App\Features\Courses\Models\Course;
use App\Features\Sections\Enums\YearLevel;
use App\Features\Sections\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'code' => 'BSN1',
                'year_level' => YearLevel::First->value,
                'department_id' => 1, // Assuming first department
                'course_name' => 'Bachelor of Science in Nursing',
            ],
            [
                'code' => 'BSN2',
                'year_level' => YearLevel::Second->value,
                'department_id' => 1,
                'course_name' => 'Bachelor of Science in Nursing',
            ],
            [
                'code' => 'BSN3',
                'year_level' => YearLevel::Third->value,
                'department_id' => 1,
                'course_name' => 'Bachelor of Science in Nursing',
            ],
            [
                'code' => 'BSN4',
                'year_level' => YearLevel::Fourth->value,
                'department_id' => 1,
                'course_name' => 'Bachelor of Science in Nursing',
            ],
            [
                'code' => 'BSCS1',
                'year_level' => YearLevel::First->value,
                'department_id' => 2, // Assuming second department
                'course_name' => 'Bachelor of Science in Information Technology',
            ],
            [
                'code' => 'BSCS2',
                'year_level' => YearLevel::Second->value,
                'department_id' => 2,
                'course_name' => 'Bachelor of Science in Information Technology',
            ],
            [
                'code' => 'BSCS3',
                'year_level' => YearLevel::Third->value,
                'department_id' => 2,
                'course_name' => 'Bachelor of Science in Information Technology',
            ],
            [
                'code' => 'BSCS4',
                'year_level' => YearLevel::Fourth->value,
                'department_id' => 2,
                'course_name' => 'Bachelor of Science in Information Technology',
            ],
        ];

        foreach ($sections as $section) {
            $courseId = Course::query()
                ->where('department_id', $section['department_id'])
                ->where('name', $section['course_name'])
                ->value('id');

            if (! $courseId) {
                continue;
            }

            Section::create([
                'code' => $section['code'],
                'year_level' => $section['year_level'],
                'department_id' => $section['department_id'],
                'course_id' => $courseId,
            ]);
        }

        $this->command->info('Section seeder completed successfully.');
    }
}
