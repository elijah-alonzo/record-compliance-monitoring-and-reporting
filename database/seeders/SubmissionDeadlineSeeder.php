<?php

namespace Database\Seeders;

use App\Features\SubmissionDeadlines\Models\SubmissionDeadline;
use Illuminate\Database\Seeder;

class SubmissionDeadlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubmissionDeadline::create([
            'first_semester_deadline' => now()->addDays(7)->toDateString(),
            'second_semester_deadline' => now()->addDays(14)->toDateString(),
            'summer_semester_deadline' => now()->addDays(21)->toDateString(),
        ]);

        $this->command->info('Submission deadlines seeder completed successfully.');
    }
}
