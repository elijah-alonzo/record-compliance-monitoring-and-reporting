<?php

declare(strict_types=1);

namespace App\Features\Reports\Actions;

use App\Features\Loads\Models\Load;
use App\Features\Reports\Enums\SubmissionState;
use App\Features\Reports\Enums\SubmissionTiming;
use Illuminate\Database\Eloquent\Builder;

final class GetLoadsReport
{
    public function __construct(private readonly ResolveReportLabel $label) {}

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function __invoke(array $filters): array
    {
        $status = isset($filters['status']) ? SubmissionState::from($filters['status']) : null;
        $timing = isset($filters['timing']) ? SubmissionTiming::from($filters['timing']) : null;

        $loads = Load::query()
            ->with(['faculties.department', 'subject', 'section'])
            ->forPeriod($filters['year'] ?? null, $filters['semester'] ?? null)
            ->forDepartment($filters['department'] ?? null)
            ->forFaculty($filters['faculty'] ?? null)
            ->when($status, fn (Builder $builder, SubmissionState $state) => match ($state) {
                SubmissionState::Submitted => $builder->submitted(),
                SubmissionState::NotSubmitted => $builder->notSubmitted(),
            })
            ->when(
                $status !== SubmissionState::NotSubmitted ? $timing : null,
                fn (Builder $builder, SubmissionTiming $when) => match ($when) {
                    SubmissionTiming::OnTime => $builder->submittedOnTime(),
                    SubmissionTiming::Late => $builder->submittedLate(),
                },
            )
            ->ordered()
            ->get()
            ->each(fn (Load $load) => $load->submission_status = $load->submissionStatus());

        return [
            'loads' => $loads,
            'year' => $filters['year'] ?? 'All Years',
            'semester' => $filters['semester'] ?? 'All Semesters',
            'department' => $this->label->department($filters['department'] ?? null),
            'facultyName' => isset($filters['faculty']) ? $this->label->faculty($filters['faculty']) : 'All Faculty',
            'status' => $status?->label() ?? 'All Statuses',
            'timing' => $timing?->label() ?? 'All Timings',
        ];
    }
}
