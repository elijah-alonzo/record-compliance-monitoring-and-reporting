<?php

namespace App\Filament\Widgets;

use App\Features\Loads\Models\Load;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class SubmissionRateWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $year = $this->normalizeFilterValue($this->pageFilters['year'] ?? null);
        $semester = $this->normalizeFilterValue($this->pageFilters['semester'] ?? null);

        $loads = Load::query()
            ->select(['status', 'due_date', 'submitted_at'])
            ->when($year, fn (Builder $query): Builder => $query->where('year', $year))
            ->when($semester, fn (Builder $query): Builder => $query->where('semester', $semester))
            ->get();

        $onTime = 0;
        $late = 0;
        $pending = 0;

        foreach ($loads as $load) {
            if (! $load->status) {
                $pending++;

                continue;
            }

            if (! $load->submitted_at || ! $load->due_date) {
                $pending++;

                continue;
            }

            if ($load->submitted_at->lte($load->due_date->copy()->endOfDay())) {
                $onTime++;
            } else {
                $late++;
            }
        }

        $total = max($loads->count(), 1);

        return [
            Stat::make('On-Time Submissions', number_format($onTime))
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->description(number_format(($onTime / $total) * 100, 1).'% of all loads')
                ->chart([
                    max($onTime - 4, 0),
                    max($onTime - 2, 0),
                    max($onTime - 1, 0),
                    $onTime,
                    min($onTime + 1, $total),
                    $onTime,
                ]),

            Stat::make('Late Submissions', number_format($late))
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle')
                ->description(number_format(($late / $total) * 100, 1).'% of all loads')
                ->chart([
                    max($late - 2, 0),
                    max($late - 1, 0),
                    $late,
                    min($late + 1, $total),
                    $late,
                    max($late - 1, 0),
                ]),

            Stat::make('Pending Submissions', number_format($pending))
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->description(number_format(($pending / $total) * 100, 1).'% of all loads')
                ->chart([
                    max($pending - 3, 0),
                    max($pending - 1, 0),
                    $pending,
                    min($pending + 1, $total),
                    $pending,
                    max($pending - 1, 0),
                ]),
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
