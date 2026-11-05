<?php

declare(strict_types=1);

namespace App\Features\Reports\Actions;

use App\Features\Loads\Models\Load;

final class GetNotSubmittedReport
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
            ->forPeriod($filters['year'], $filters['semester'])
            ->forDepartment($filters['department'] ?? null)
            ->notSubmitted()
            ->ordered()
            ->get();

        return [
            'loads' => $loads,
            'year' => $filters['year'],
            'semester' => $filters['semester'],
            'department' => $this->label->department($filters['department'] ?? null),
        ];
    }
}
