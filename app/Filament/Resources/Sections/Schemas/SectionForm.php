<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Features\Courses\Models\Course;
use App\Features\Sections\Enums\YearLevel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Section Information')
                    ->description('Manage available sections in the school')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('code')
                            ->prefixIcon('heroicon-o-hashtag')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule
                                ->where('year_level', $get('year_level'))
                                ->where('course_id', $get('course_id')))
                            ->placeholder('Enter section code (e.g., BEED1, BTH2)'),

                        Select::make('year_level')
                            ->prefixIcon('heroicon-o-calendar')
                            ->required()
                            ->options(YearLevel::class)
                            ->placeholder('Select year level'),

                        Select::make('department_id')
                            ->label('Department')
                            ->prefixIcon('heroicon-o-building-office')
                            ->required()
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select department')
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('course_id', null)),

                        Select::make('course_id')
                            ->label('Course')
                            ->prefixIcon('heroicon-o-academic-cap')
                            ->required()
                            ->options(fn (Get $get) => Course::query()
                                ->where('department_id', $get('department_id'))
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray())
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get) => blank($get('department_id')))
                            ->placeholder('Select course'),
                    ])
                    ->columns(2),
            ]);
    }
}
