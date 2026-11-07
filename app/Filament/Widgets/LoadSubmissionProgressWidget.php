<?php

namespace App\Filament\Widgets;

use App\Features\Loads\Models\Load;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class LoadSubmissionProgressWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected ?string $heading = 'Teaching Load Submission Progress';

    protected ?string $description = 'Submitted vs pending grading sheets';

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected ?string $maxHeight = '220px';

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'cutout' => '62%',
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $year = $this->normalizeFilterValue($this->pageFilters['year'] ?? null);
        $semester = $this->normalizeFilterValue($this->pageFilters['semester'] ?? null);

        $query = Load::query()
            ->when($year, fn (Builder $builder): Builder => $builder->where('year', $year))
            ->when($semester, fn (Builder $builder): Builder => $builder->where('semester', $semester));

        $submitted = (clone $query)->where('status', true)->count();
        $pending = (clone $query)->where('status', false)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Teaching Loads',
                    'data' => [$submitted, $pending],
                    'backgroundColor' => ['#030454', '#f5be22'],
                ],
            ],
            'labels' => ['Submitted', 'Pending'],
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
