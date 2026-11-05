<?php

declare(strict_types=1);

namespace App\Features\Reports\Actions;

use App\Features\Loads\Models\Load;

final class GetFacultyReport
{
    public function __construct(private readonly ResolveReportLabel $label) {}

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function __invoke(array $filters): array
    {
        $loads = Load::query()
            ->with(['faculties.department', 'subject', 'section'])
            ->forPeriod($filters['year'] ?? null, $filters['semester'] ?? null)
            ->forFaculty($filters['faculty'])
            ->ordered()
            ->get()
            ->each(fn (Load $load) => $load->submission_status = $load->submissionStatus());

        return [
            'loads' => $loads,
            'year' => $filters['year'] ?? 'All Years',
            'semester' => $filters['semester'] ?? 'All Semesters',
            'facultyName' => $this->label->faculty($filters['faculty']),
        ];
    }
}
