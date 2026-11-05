<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Department Information')
                    ->description('Manage academic departments')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('code')
                            ->prefixIcon('heroicon-o-hashtag')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter department code (e.g., CIT)')
                            ->columnSpan(1),

                        TextInput::make('name')
                            ->prefixIcon('heroicon-o-building-office')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter department name')
                            ->columnSpan(2),

                        ColorPicker::make('color')
                            ->label('Badge Color')
                            ->formatStateUsing(fn (?string $state): ?string => match ($state) {
                                'purple' => '#7c3aed',
                                'yellow' => '#eab308',
                                'red' => '#ef4444',
                                'blue' => '#3b82f6',
                                'gray' => '#6b7280',
                                default => $state,
                            })
                            ->hex()
                            ->helperText('Pick any color for the badge.')
                            ->placeholder('Pick badge color')
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ]);
    }
}
