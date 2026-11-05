<?php

namespace App\Filament\Resources\Loads\Schemas;

use App\Features\Courses\Models\Course;
use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Enums\Semester;
use App\Features\Sections\Models\Section;
use App\Features\Subjects\Enums\SubjectType;
use App\Features\Subjects\Models\Subject;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LoadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Schedule and Academic Details')
                        ->description('Set the academic period and class schedule information')
                        ->schema([
                            Select::make('semester')
                                ->label('Semester')
                                ->prefixIcon('heroicon-o-calendar')
                                ->options(Semester::class)
                                ->required()
                                ->placeholder('Select semester'),

                            Select::make('year')
                                ->label('Academic Year')
                                ->prefixIcon('heroicon-o-calendar-days')
                                ->options(function () {
                                    $startYear = (int) now()->year;
                                    $years = [];

                                    for ($i = 0; $i < 20; $i++) {
                                        $from = $startYear + $i;
                                        $to = $from + 1;
                                        $label = $from.'-'.$to;
                                        $years[$label] = $label;
                                    }

                                    return $years;
                                })
                                ->searchable()
                                ->required()
                                ->placeholder('Select academic year'),

                            Select::make('subject_id')
                                ->label('Subject')
                                ->prefixIcon('heroicon-o-book-open')
                                ->options(Subject::all()->mapWithKeys(function ($subject) {
                                    return [$subject->id => $subject->subject_code.' - '.$subject->name];
                                }))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->placeholder('Select subject')
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('faculties', [])),
                        ])
                        ->columns(2),

                    Step::make('Teaching Load Assignment')
                        ->description('Assign faculty members to teach subjects for specific sections')
                        ->schema([
                            Select::make('faculties')
                                ->label('Faculty')
                                ->prefixIcon('heroicon-o-user')
                                ->relationship(
                                    'faculties',
                                    'last_name',
                                    modifyQueryUsing: function (Builder $query, Get $get): Builder {
                                        $courseId = self::majorCourseId($get('subject_id'));

                                        return $courseId
                                            ? $query->whereHas('courses', fn (Builder $q) => $q->whereKey($courseId))
                                            : $query;
                                    },
                                )
                                ->helperText(function (Get $get): ?string {
                                    $courseId = self::majorCourseId($get('subject_id'));

                                    return $courseId
                                        ? 'Major subject: showing faculty from '.Course::find($courseId)?->name.' only.'
                                        : null;
                                })
                                ->getOptionLabelFromRecordUsing(fn (Faculty $faculty): string => $faculty->full_name)
                                ->extraAttributes(['class' => 'faculty-select-info'])
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->required()
                                ->placeholder('Select faculty members'),

                            Select::make('section_id')
                                ->label('Section')
                                ->prefixIcon('heroicon-o-academic-cap')
                                ->options(Section::query()
                                    ->with('course')
                                    ->get()
                                    ->mapWithKeys(function ($section) {
                                        return [$section->id => $section->code.' - '.($section->course?->name ?? '-')];
                                    }))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->placeholder('Select section'),

                            Textarea::make('schedule')
                                ->label('Schedule')
                                ->rows(3)
                                ->placeholder('Optionally, enter class schedule.')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                    ->columnSpanFull(),
            ]);
    }

    private static function majorCourseId(mixed $subjectId): ?int
    {
        $subject = filled($subjectId) ? Subject::find($subjectId) : null;

        return $subject?->type === SubjectType::Major ? $subject->course_id : null;
    }
}
