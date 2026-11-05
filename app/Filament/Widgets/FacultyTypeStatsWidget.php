<?php

namespace App\Filament\Widgets;

use App\Features\Faculties\Enums\EmploymentType;
use App\Features\Faculties\Models\Faculty;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FacultyTypeStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $shared = Faculty::query()->where('employment_type', EmploymentType::Shared->value)->count();
        $fullTime = Faculty::query()->where('employment_type', EmploymentType::FullTime->value)->count();
        $partTime = Faculty::query()->where('employment_type', EmploymentType::PartTime->value)->count();
        $total = max($shared + $fullTime + $partTime, 1);

        return [
            Stat::make('Shared Faculty', number_format($shared))
                ->color('info')
                ->icon('heroicon-o-user-group')
                ->description(number_format(($shared / $total) * 100, 1).'% of faculty roster')
                ->chart([
                    max($shared - 2, 0),
                    max($shared - 1, 0),
                    $shared,
                    min($shared + 1, $total),
                    $shared,
                    max($shared - 1, 0),
                ]),
            Stat::make('Full-Time Faculty', number_format($fullTime))
                ->color('success')
                ->icon('heroicon-o-briefcase')
                ->description(number_format(($fullTime / $total) * 100, 1).'% of faculty roster')
                ->chart([
                    max($fullTime - 3, 0),
                    max($fullTime - 1, 0),
                    $fullTime,
                    min($fullTime + 1, $total),
                    $fullTime,
                    max($fullTime - 1, 0),
                ]),
            Stat::make('Part-Time Faculty', number_format($partTime))
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->description(number_format(($partTime / $total) * 100, 1).'% of faculty roster')
                ->chart([
                    max($partTime - 2, 0),
                    max($partTime - 1, 0),
                    $partTime,
                    min($partTime + 1, $total),
                    $partTime,
                    max($partTime - 1, 0),
                ]),
        ];
    }
}
