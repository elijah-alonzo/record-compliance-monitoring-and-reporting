<?php

namespace App\Filament\Resources\Faculties\Schemas;

use App\Features\Faculties\Enums\EmploymentType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class FacultyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Faculty Information')
                    ->description('Manage faculty members and their details')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First name')
                            ->prefixIcon('heroicon-o-user')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter first name')
                            ->columnSpan(1),

                        TextInput::make('middle_name')
                            ->label('Middle name')
                            ->prefixIcon('heroicon-o-user')
                            ->maxLength(255)
                            ->placeholder('Enter middle name')
                            ->columnSpan(1),

                        TextInput::make('last_name')
                            ->label('Last name')
                            ->prefixIcon('heroicon-o-user')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter last name')
                            ->columnSpan(1),

                        Select::make('employment_type')
                            ->label('Employment Type')
                            ->prefixIcon('heroicon-o-briefcase')
                            ->required()
                            ->options(EmploymentType::class)
                            ->placeholder('Select employment type')
                            ->columnSpan(1),

                        Select::make('department_id')
                            ->label('Department')
                            ->prefixIcon('heroicon-o-building-office')
                            ->required()
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select department')
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('courses', []))
                            ->columnSpan(2),

                        Select::make('courses')
                            ->label('Courses')
                            ->prefixIcon('heroicon-o-academic-cap')
                            ->relationship(
                                'courses',
                                'name',
                                modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('department_id', $get('department_id')),
                            )
                            ->multiple()
                            ->extraAttributes(['class' => 'white-badge-select'])
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get): bool => blank($get('department_id')))
                            ->helperText('Courses this faculty member teaches major subjects in.')
                            ->placeholder('Select courses')
                            ->columnSpan(3),

                        TextInput::make('email')
                            ->label('Email address')
                            ->prefixIcon('heroicon-o-envelope')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('Enter email address')
                            ->columnSpan(2),

                        TextInput::make('phone')
                            ->label('Phone number')
                            ->prefixIcon('heroicon-o-phone')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('Enter phone number')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->extraAttributes(['class' => 'mb-6']),

            ]);
    }
}
