<?php

namespace App\Filament\Resources\Subjects\Schemas;

use App\Features\Subjects\Enums\SubjectType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subject Information')
                    ->description('Manage curriculum subjects and course catalog')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('subject_code')
                            ->label('Subject Code')
                            ->prefixIcon('heroicon-o-identification')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter subject code'),

                        TextInput::make('name')
                            ->prefixIcon('heroicon-o-book-open')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter subject name'),

                        TextInput::make('units')
                            ->prefixIcon('heroicon-o-calculator')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(6)
                            ->placeholder('Enter credit units'),

                        Select::make('type')
                            ->prefixIcon('heroicon-o-tag')
                            ->required()
                            ->options(SubjectType::class)
                            ->placeholder('Select subject type')
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if ($state !== SubjectType::Major->value) {
                                    $set('course_id', null);
                                }
                            }),

                        Select::make('course_id')
                            ->label('Course')
                            ->prefixIcon('heroicon-o-academic-cap')
                            ->relationship('course', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get): bool => $get('type') !== SubjectType::Major->value)
                            ->required(fn (Get $get): bool => $get('type') === SubjectType::Major->value)
                            // Still save when disabled so switching to Minor clears the stored course.
                            ->dehydrated()
                            ->dehydrateStateUsing(fn ($state, Get $get) => $get('type') === SubjectType::Major->value ? $state : null)
                            ->helperText('For major subjects only.')
                            ->placeholder('Select course'),

                        Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Enter subject description'),
                    ])
                    ->columns(2),
            ]);
    }
}
