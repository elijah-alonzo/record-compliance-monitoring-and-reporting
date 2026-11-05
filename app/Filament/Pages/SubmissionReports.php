<?php

namespace App\Filament\Pages;

use App\Features\Departments\Models\Department;
use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Models\Load;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SubmissionReports extends Page
{
    protected string $view = 'filament-panels::pages.page';

    protected static ?string $title = 'Reports';

    protected static ?string $navigationLabel = 'Reports';

    protected static UnitEnum|string|null $navigationGroup = 'Grading Sheet Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;

    protected static ?int $navigationSort = 6;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => $this->getPrintUrl())
                ->openUrlInNewTab(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Report Filters')
                    ->description('Choose any filters to include in the printout.')
                    ->schema([
                        Select::make('year')
                            ->label('School Year')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->options(fn () => ['all' => 'All'] + Load::query()
                                ->distinct()
                                ->orderBy('year')
                                ->pluck('year', 'year')
                                ->toArray())
                            ->searchable()
                            ->preload(),

                        Select::make('semester')
                            ->label('Semester')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->options([
                                'all' => 'All',
                                '1st Semester' => '1st Semester',
                                '2nd Semester' => '2nd Semester',
                                'Summer' => 'Summer',
                            ]),

                        Select::make('faculty')
                            ->label('Faculty')
                            ->prefixIcon('heroicon-o-user')
                            ->options(fn () => ['all' => 'All'] + Faculty::query()
                                ->orderBy('last_name')
                                ->orderBy('first_name')
                                ->get()
                                ->mapWithKeys(fn (Faculty $faculty) => [$faculty->id => $faculty->full_name])
                                ->toArray())
                            ->searchable()
                            ->preload(),

                        Select::make('department')
                            ->label('Department')
                            ->prefixIcon('heroicon-o-building-office-2')
                            ->options(fn () => ['all' => 'All'] + Department::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray())
                            ->searchable()
                            ->preload(),

                        Select::make('status')
                            ->label('Status')
                            ->prefixIcon('heroicon-o-check-circle')
                            ->options([
                                'all' => 'All',
                                'submitted' => 'Submitted',
                                'not_submitted' => 'Not Submitted',
                            ])
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if ($state !== 'submitted') {
                                    $set('timing', null);
                                }
                            }),

                        Select::make('timing')
                            ->label('Timing')
                            ->prefixIcon('heroicon-o-clock')
                            ->options([
                                'all' => 'All',
                                'on_time' => 'On Time',
                                'late' => 'Late',
                            ])
                            ->disabled(fn (Get $get): bool => $get('status') !== 'submitted')
                            ->helperText('Timing is available for submitted loads only.'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form'),
            ]);
    }

    protected function getPrintUrl(): string
    {
        return route('reports.loads', $this->getPrintFilters());
    }

    /**
     * @return array<string, int|string>
     */
    protected function getPrintFilters(): array
    {
        return array_filter([
            'year' => $this->normalizeFilterValue($this->data['year'] ?? null),
            'semester' => $this->normalizeFilterValue($this->data['semester'] ?? null),
            'faculty' => $this->normalizeFilterValue($this->data['faculty'] ?? null),
            'department' => $this->normalizeFilterValue($this->data['department'] ?? null),
            'status' => $this->normalizeFilterValue($this->data['status'] ?? null),
            'timing' => $this->normalizeFilterValue($this->data['timing'] ?? null),
        ], fn ($value) => $value !== null && $value !== '');
    }

    protected function normalizeFilterValue(int|string|null $value): int|string|null
    {
        if (! is_scalar($value)) {
            return null;
        }

        if (is_string($value) && $value === 'all') {
            return null;
        }

        return $value;
    }
}
