<?php

namespace Database\Seeders;

use App\Features\Courses\Models\Course;
use App\Features\Subjects\Enums\SubjectType;
use App\Features\Subjects\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'subject_code' => 'CS101',
                'name' => 'Introduction to Computer Science',
                'units' => 3,
                'type' => SubjectType::Major->value,
                'description' => 'Fundamental concepts of computer science and programming.',
            ],
            [
                'subject_code' => 'MATH101',
                'name' => 'College Algebra',
                'units' => 3,
                'type' => SubjectType::Major->value,
                'description' => 'Basic algebraic concepts and problem-solving techniques.',
            ],
            [
                'subject_code' => 'ENG101',
                'name' => 'English Composition',
                'units' => 3,
                'type' => SubjectType::Major->value,
                'description' => 'Writing skills and composition techniques.',
            ],
            [
                'subject_code' => 'GAME201',
                'name' => 'Game Development',
                'units' => 4,
                'type' => SubjectType::Major->value,
                'description' => 'Introduction to game development concepts and techniques.',
            ],
            [
                'subject_code' => 'CHEM101',
                'name' => 'General Chemistry I',
                'units' => 4,
                'type' => SubjectType::Major->value,
                'description' => 'Basic principles of chemistry and chemical reactions.',
            ],
            [
                'subject_code' => 'HIST101',
                'name' => 'World History',
                'units' => 3,
                'type' => SubjectType::Minor->value,
                'description' => 'Survey of world history from ancient to modern times.',
            ],
            [
                'subject_code' => 'BIO101',
                'name' => 'General Biology',
                'units' => 4,
                'type' => SubjectType::Major->value,
                'description' => 'Introduction to biological concepts and processes.',
            ],
            [
                'subject_code' => 'PE101',
                'name' => 'Physical Education',
                'units' => 2,
                'type' => SubjectType::Minor->value,
                'description' => 'Physical fitness and health education.',
            ],
        ];

        $courseIds = Course::query()->pluck('id', 'name');
        $nursing = $courseIds['Bachelor of Science in Nursing'] ?? null;
        $it = $courseIds['Bachelor of Science in Information Technology'] ?? null;
        $majorCourses = [
            'CS101' => $it,
            'MATH101' => $it,
            'ENG101' => $it,
            'GAME201' => $it,
            'CHEM101' => $nursing,
            'BIO101' => $nursing,
        ];

        foreach ($subjects as $subject) {
            $subject['course_id'] = $majorCourses[$subject['subject_code']] ?? null;
            Subject::create($subject);
        }

        $this->command->info('Subject seeder completed successfully.');
    }
}
