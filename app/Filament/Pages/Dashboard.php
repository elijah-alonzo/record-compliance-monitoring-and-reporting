<?php

namespace App\Filament\Pages;

use App\Features\Loads\Models\Load;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Academic Filters')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('year')
                            ->label('Academic Year')
                            ->options(fn () => Load::query()
                                ->distinct()
                                ->orderBy('year', 'desc')
                                ->pluck('year', 'year')
                                ->toArray() + ['all' => 'All'])
                            ->searchable()
                            ->preload()
                            ->native(false),
                        Select::make('semester')
                            ->label('Semester')
                            ->options([
                                'all' => 'All',
                                '1st Semester' => '1st Semester',
                                '2nd Semester' => '2nd Semester',
                                'Summer' => 'Summer',
                            ])
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        $widgets = $this->getWidgets();

        return $schema
            ->components([
                Grid::make($this->getColumns())
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents(array_slice($widgets, 0, 1))),
                $this->getFiltersFormContentComponent()
                    ->columnSpanFull(),
                Grid::make($this->getColumns())
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents(array_slice($widgets, 1))),
            ]);
    }
}
