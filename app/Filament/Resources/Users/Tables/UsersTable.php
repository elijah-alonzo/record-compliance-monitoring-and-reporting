<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('User Information')
            ->description('List of the user accounts that can access the application.')
            ->defaultPaginationPageOption(50)
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('last_name')
                    ->label('Name')
                    ->formatStateUsing(fn ($record): string => $record->full_name)
                    ->searchable(query: function ($query, string $search) {
                        return $query
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color(fn (?string $state): string => match (strtolower($state ?? '')) {
                        'admin' => 'yellow',
                        'staff' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                        default => $state,
                    })
                    ->separator(', ')
                    ->toggleable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon(Heroicon::Envelope),

                TextColumn::make('phone')
                    ->label('Phone Number')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon(Heroicon::Phone),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->color('info'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
