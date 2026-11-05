<?php

namespace App\Filament\Widgets;

use App\Features\Loads\Models\Load;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class OverdueUnsubmittedLoadsWidget extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $year = $this->normalizeFilterValue($this->pageFilters['year'] ?? null);
        $semester = $this->normalizeFilterValue($this->pageFilters['semester'] ?? null);

        return $table
            ->heading('Overdue Unsubmitted Teaching Loads')
            ->description('Loads that remain unsubmitted after the assigned deadline')
            ->query(
                Load::query()
                    ->with(['faculties', 'subject', 'section'])
                    ->where('status', false)
                    ->whereDate('due_date', '<', now()->toDateString())
                    ->when($year, fn (Builder $query): Builder => $query->where('year', $year))
                    ->when($semester, fn (Builder $query): Builder => $query->where('semester', $semester))
                    ->orderBy('due_date')
            )
            ->columns([
                TextColumn::make('faculty_names')
                    ->label('Faculty')
                    ->state(fn (Load $record): string => $record->faculties
                        ->pluck('full_name')
                        ->filter()
                        ->implode(', '))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('faculties', function (Builder $facultyQuery) use ($search): void {
                            $facultyQuery
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable(),
                TextColumn::make('section.code')
                    ->label('Section')
                    ->searchable(),
                TextColumn::make('year')
                    ->label('Academic Year'),
                TextColumn::make('semester')
                    ->label('Semester'),
                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('days_overdue')
                    ->label('Days Overdue')
                    ->state(fn (Load $record): int => $record->due_date->diffInDays(now()))
                    ->badge()
                    ->color('danger')
                    ->sortable(),
            ]);
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
