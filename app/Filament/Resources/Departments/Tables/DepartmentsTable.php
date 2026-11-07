<?php

namespace App\Filament\Resources\Departments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Department Information')
            ->description('List of departments along with their details.')
            ->defaultPaginationPageOption(50)
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record): string|array => self::resolveDepartmentColor($record->code, $record->color))
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Department Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('faculties_count')
                    ->label('Faculties')
                    ->counts('faculties')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    private static function resolveDepartmentColor(?string $code, ?string $color): string|array
    {
        if ($color) {
            return self::normalizeDepartmentColor($color);
        }

        return self::normalizeDepartmentColor(match (strtoupper($code ?? '')) {
            'CTE' => 'blue',
            'CTM' => 'yellow',
            'CMU' => 'red',
            default => 'gray',
        });
    }

    private static function normalizeDepartmentColor(string $color): string|array
    {
        $normalized = strtolower(trim($color));
        $hex = match ($normalized) {
            'purple' => '#7c3aed',
            'yellow' => '#eab308',
            'red' => '#ef4444',
            'blue' => '#3b82f6',
            'gray' => '#6b7280',
            default => str_starts_with($normalized, '#') ? $normalized : null,
        };

        if ($hex) {
            return Color::hex($hex);
        }

        return $normalized;
    }
}
