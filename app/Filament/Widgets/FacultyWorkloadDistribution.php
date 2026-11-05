<?php

namespace App\Filament\Widgets;

use App\Features\Faculties\Models\Faculty;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class FacultyWorkloadDistribution extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected ?string $heading = 'Faculty Late Submission Rates';

    protected ?string $description = 'Faculty members with the highest late submission rates';

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected ?string $maxHeight = '220px';

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $year = $this->normalizeFilterValue($this->pageFilters['year'] ?? null);
        $semester = $this->normalizeFilterValue($this->pageFilters['semester'] ?? null);

        $facultyWithLateRate = Faculty::query()
            ->withCount([
                'loads as loads_count' => function (Builder $query) use ($year, $semester): void {
                    $query
                        ->when($year, fn (Builder $builder): Builder => $builder->where('year', $year))
                        ->when($semester, fn (Builder $builder): Builder => $builder->where('semester', $semester));
                },
            ])
            ->withCount([
                'loads as late_loads_count' => function (Builder $query) use ($year, $semester): void {
                    $query
                        ->where('status', true)
                        ->whereNotNull('submitted_at')
                        ->when($year, fn (Builder $builder): Builder => $builder->where('year', $year))
                        ->when($semester, fn (Builder $builder): Builder => $builder->where('semester', $semester))
                        ->whereRaw('DATE(submitted_at) > due_date');
                },
            ])
            ->get()
            ->map(function (Faculty $faculty): Faculty {
                $faculty->late_rate = $faculty->loads_count > 0
                    ? round(($faculty->late_loads_count / $faculty->loads_count) * 100, 1)
                    : 0.0;

                return $faculty;
            })
            ->sortBy([
                ['late_rate', 'desc'],
                ['loads_count', 'desc'],
                ['last_name', 'asc'],
                ['first_name', 'asc'],
            ])
            ->take(10)
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'Late Submission Rate (%)',
                    'data' => $facultyWithLateRate->pluck('late_rate')->all(),
                    'barPercentage' => 0.45,
                    'categoryPercentage' => 0.55,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $facultyWithLateRate->pluck('full_name')->all(),
        ];
    }

    private function normalizeFilterValue(mixed $value): ?string
    {
        if (! is_scalar($value)) {
            return null;
        }

        $value = (string) $value;

        return $value === 'all' ? null : $value;
    }
}
