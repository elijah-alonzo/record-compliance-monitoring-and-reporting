<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->description('Create or edit user account details')
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

                        Select::make('roles')
                            ->label('Roles')
                            ->prefixIcon('heroicon-o-shield-check')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->placeholder('Select roles')
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->label('Email address')
                            ->prefixIcon('heroicon-o-envelope')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter email address')
                            ->columnSpan(1),

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

                Section::make('Security & Privacy')
                    ->description('Set up password for the user account')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Password')
                                    ->prefixIcon('heroicon-o-lock-closed')
                                    ->password()
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->hiddenOn('view')
                                    ->revealable()
                                    ->placeholder('Minimum 8 characters'),

                                TextInput::make('password_confirmation')
                                    ->label('Confirm Password')
                                    ->prefixIcon('heroicon-o-shield-check')
                                    ->password()
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->placeholder('Repeat password')
                                    ->revealable(),
                            ]),
                    ]),
            ]);
    }
}
