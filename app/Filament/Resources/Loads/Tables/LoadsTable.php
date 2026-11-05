<?php

namespace App\Filament\Resources\Loads\Tables;

use App\Features\Departments\Models\Department;
use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Enums\Semester;
use App\Features\Loads\Mail\TeachingLoadReminderMail;
use App\Features\Loads\Models\Load;
use App\Filament\Resources\Loads\LoadResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Teaching Load Information')
            ->description('List of teaching loads along with their details.')
            ->defaultPaginationPageOption(50)
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('year')
                    ->label('School Year')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('subject.subject_code')
                    ->label('Subject Code')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

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
                    })
                    ->toggleable(),

                TextColumn::make('section.code')
                    ->label('Section')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('submitted_at')
                    ->label('Submitted At')
                    ->dateTime('M d, Y h:i A')
                    ->placeholder('Not submitted')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('submission_timing')
                    ->label('Submission')
                    ->badge()
                    ->state(function (Load $record): string {
                        if (! $record->status || ! $record->submitted_at) {
                            return '-';
                        }

                        if (! $record->due_date) {
                            return 'Submitted';
                        }

                        return $record->submitted_at->lte($record->due_date->copy()->endOfDay())
                            ? 'On Time'
                            : 'Late';
                    })
                    ->color(function (Load $record): string {
                        if (! $record->status || ! $record->submitted_at) {
                            return 'warning';
                        }

                        if (! $record->due_date) {
                            return 'success';
                        }

                        return $record->submitted_at->lte($record->due_date->copy()->endOfDay())
                            ? 'success'
                            : 'danger';
                    })
                    ->toggleable(),

                ToggleColumn::make('status')
                    ->label('Status')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->afterStateUpdated(function (Load $record, bool $state): void {
                        $record->update([
                            'submitted_at' => $state ? ($record->submitted_at ?? now()) : null,
                        ]);
                    })
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('faculty')
                    ->label('Faculty')
                    ->options(fn () => Faculty::query()
                        ->orderBy('last_name')
                        ->orderBy('first_name')
                        ->get()
                        ->mapWithKeys(fn (Faculty $faculty) => [$faculty->id => $faculty->full_name])
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (! $value) {
                            return $query;
                        }

                        return $query->whereHas('faculties', fn (Builder $builder) => $builder->whereKey($value));
                    }),

                SelectFilter::make('department')
                    ->label('Department')
                    ->options(fn () => Department::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (! $value) {
                            return $query;
                        }

                        return $query->whereHas('faculties', function (Builder $builder) use ($value): void {
                            $builder->where('department_id', $value);
                        });
                    }),

                SelectFilter::make('semester')
                    ->options(Semester::class),

                SelectFilter::make('status')
                    ->options([
                        '1' => 'Submitted',
                        '0' => 'Not Submitted',
                    ]),

                SelectFilter::make('year')
                    ->options(function () {
                        return Load::distinct('year')
                            ->pluck('year', 'year')
                            ->toArray();
                    }),

                SelectFilter::make('submission_timing')
                    ->label('Submission Timing')
                    ->options([
                        'on_time' => 'On Time',
                        'late' => 'Late',
                        'pending' => 'Pending',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === 'on_time') {
                            return $query
                                ->where('status', true)
                                ->whereNotNull('submitted_at')
                                ->whereRaw('DATE(submitted_at) <= due_date');
                        }

                        if ($value === 'late') {
                            return $query
                                ->where('status', true)
                                ->whereNotNull('submitted_at')
                                ->whereRaw('DATE(submitted_at) > due_date');
                        }

                        if ($value === 'pending') {
                            return $query->where(function (Builder $pendingQuery): void {
                                $pendingQuery
                                    ->where('status', false)
                                    ->orWhereNull('submitted_at');
                            });
                        }

                        return $query;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (Load $record): string => LoadResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->color('info'),
                Action::make('notify')
                    ->label('Notify')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Load $record): void {
                        $record->loadMissing(['faculties', 'subject', 'section']);

                        $recipients = $record->faculties
                            ->filter(fn (Faculty $faculty) => (string) ($faculty->email ?? '') !== '');

                        if ($recipients->isEmpty()) {
                            Notification::make()
                                ->title('Faculty emails are missing')
                                ->body('Add email addresses for the assigned faculty members before sending notifications.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $sender = Auth::user()?->full_name;

                        foreach ($recipients as $faculty) {
                            Mail::to($faculty->email)->send(
                                new TeachingLoadReminderMail($record, $sender, $faculty->full_name)
                            );
                        }

                        Notification::make()
                            ->title('Notification sent')
                            ->body('Teaching load reminders were sent to '.$recipients->count().' faculty member(s).')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
