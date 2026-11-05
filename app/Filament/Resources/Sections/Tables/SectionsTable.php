<?php

namespace App\Filament\Resources\Sections\Tables;

use App\Features\Sections\Enums\YearLevel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Section Information')
            ->description('List of sections along with their details.')
            ->defaultPaginationPageOption(50)
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('code')
                    ->label('Section Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('course.code')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('year_level')
                    ->label('Year Level')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('department.code')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record): string => self::resolveDepartmentColor($record->department?->code, $record->department?->color))
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
                SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('year_level')
                    ->options(YearLevel::class),
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
            ])
            ->defaultSort('code', 'asc');
    }

    private static function resolveDepartmentColor(?string $code, ?string $color): string
    {
        if ($color) {
            return self::normalizeDepartmentColor($color);
        }

        return match (strtoupper($code ?? '')) {
            'SITE' => 'purple',
            'SBAHM' => 'yellow',
            'SNAHS' => 'red',
            'SASTE' => 'blue',
            default => 'gray',
        };
    }

    private static function normalizeDepartmentColor(string $color): string
    {
        $normalized = strtolower(trim($color));

        return match ($normalized) {
            '#7c3aed' => 'purple',
            '#eab308' => 'yellow',
            '#ef4444' => 'red',
            '#3b82f6' => 'blue',
            '#6b7280' => 'gray',
            default => $normalized,
        };
    }
}
