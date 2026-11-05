<?php

namespace App\Filament\Pages;

use App\Features\Loads\Enums\Semester;
use App\Features\Loads\Models\Load;
use App\Features\SubmissionDeadlines\Models\SubmissionDeadline;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SubmissionDeadlineSettings extends Page
{
    protected string $view = 'filament-panels::pages.page';

    protected static ?string $navigationLabel = 'Submission Deadline';

    protected static UnitEnum|string|null $navigationGroup = 'Grading Sheet Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 5;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $record = SubmissionDeadline::query()->first();

        $this->form->fill([
            'first_semester_deadline' => $record?->first_semester_deadline,
            'second_semester_deadline' => $record?->second_semester_deadline,
            'summer_semester_deadline' => $record?->summer_semester_deadline,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Submission Deadlines')
                    ->description('Set a deadline for each semester.')
                    ->schema([
                        DatePicker::make('first_semester_deadline')
                            ->label('First Semester Deadline')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->required()
                            ->native(false),

                        DatePicker::make('second_semester_deadline')
                            ->label('Second Semester Deadline')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->required()
                            ->native(false),

                        DatePicker::make('summer_semester_deadline')
                            ->label('Summer Semester Deadline')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save deadline')
                                ->submit('save')
                                ->color('primary'),
                        ])
                            ->alignment($this->getFormActionsAlignment())
                            ->fullWidth(false),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $firstSemester = $data['first_semester_deadline'] ?? null;
        $secondSemester = $data['second_semester_deadline'] ?? null;
        $summerSemester = $data['summer_semester_deadline'] ?? null;

        if (! $firstSemester || ! $secondSemester || ! $summerSemester) {
            Notification::make()
                ->title('All semester deadlines are required')
                ->danger()
                ->send();

            return;
        }

        $record = SubmissionDeadline::query()->first();

        if (! $record) {
            SubmissionDeadline::create([
                'first_semester_deadline' => $firstSemester,
                'second_semester_deadline' => $secondSemester,
                'summer_semester_deadline' => $summerSemester,
            ]);
        } else {
            $record->update([
                'first_semester_deadline' => $firstSemester,
                'second_semester_deadline' => $secondSemester,
                'summer_semester_deadline' => $summerSemester,
            ]);
        }

        Load::query()
            ->where('status', false)
            ->where('semester', Semester::First->value)
            ->update([
                'due_date' => $firstSemester,
            ]);

        Load::query()
            ->where('status', false)
            ->where('semester', Semester::Second->value)
            ->update([
                'due_date' => $secondSemester,
            ]);

        Load::query()
            ->where('status', false)
            ->where('semester', Semester::Summer->value)
            ->update([
                'due_date' => $summerSemester,
            ]);

        Notification::make()
            ->title('Submission deadlines saved')
            ->success()
            ->send();
    }
}
