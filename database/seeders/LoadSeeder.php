<?php

namespace Database\Seeders;

use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Enums\Semester;
use App\Features\Loads\Models\Load;
use App\Features\Sections\Models\Section;
use App\Features\Subjects\Models\Subject;
use App\Features\SubmissionDeadlines\Models\SubmissionDeadline;
use Illuminate\Database\Seeder;

class LoadSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing data
        $faculties = Faculty::all();
        $subjects = Subject::all();
        $sections = Section::all();

        $deadlineRecord = SubmissionDeadline::query()->first();

        if ($faculties->count() > 0 && $subjects->count() > 0 && $sections->count() > 0 && $deadlineRecord) {
            // Sample loads for current academic year
            $loads = [
                [
                    'subject_id' => $subjects->first()->id,
                    'section_id' => $sections->first()->id,
                    'year' => '2025-2026',
                    'semester' => Semester::First->value,
                    'due_date' => $deadlineRecord->first_semester_deadline,
                    'schedule' => now()->setTime(8, 0),
                    'status' => false,
                    'submitted_at' => null,
                    'faculty_ids' => [$faculties->first()->id],
                ],
                [
                    'subject_id' => $subjects->skip(1)->first()?->id ?? $subjects->first()->id,
                    'section_id' => $sections->skip(1)->first()?->id ?? $sections->first()->id,
                    'year' => '2025-2026',
                    'semester' => Semester::First->value,
                    'due_date' => $deadlineRecord->first_semester_deadline,
                    'schedule' => now()->setTime(10, 0),
                    'status' => true,
                    'submitted_at' => now(),
                    'faculty_ids' => [$faculties->first()->id],
                ],
            ];

            foreach ($loads as $load) {
                $facultyIds = $load['faculty_ids'] ?? [];
                unset($load['faculty_ids']);

                $record = Load::create($load);

                if ($facultyIds !== []) {
                    $record->faculties()->sync($facultyIds);
                }
            }
        }
    }
}
