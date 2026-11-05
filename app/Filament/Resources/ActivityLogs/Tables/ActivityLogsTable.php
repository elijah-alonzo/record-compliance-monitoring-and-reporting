<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('System Logs')
            ->description('Log of actions and changes made in the system.')
            ->defaultPaginationPageOption(50)
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('action_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Created' => 'success',
                        'Updated' => 'warning',
                        'Deleted' => 'danger',
                        default => 'gray',
                    })
                    ->getStateUsing(fn ($record): string => str_starts_with($record->description ?? '', 'Created')
                        ? 'Created'
                        : (str_starts_with($record->description ?? '', 'Updated')
                            ? 'Updated'
                            : (str_starts_with($record->description ?? '', 'Deleted')
                                ? 'Deleted'
                                : 'Other')))
                    ->sortable(query: function ($query, string $direction) {
                        $query->orderByRaw(
                            "case when description like 'Created%' then 1 when description like 'Updated%' then 2 when description like 'Deleted%' then 3 else 4 end {$direction}"
                        );
                    }),
                TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),

                TextColumn::make('user.full_name')
                    ->label('User')
                    ->formatStateUsing(fn ($state, $record): string => $record->user?->full_name
                        ?: ($record->user?->email ?? 'System'))
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Action')
                    ->wrap()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }
}
