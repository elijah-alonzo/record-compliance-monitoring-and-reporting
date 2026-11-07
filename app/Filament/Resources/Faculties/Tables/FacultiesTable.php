<?php

namespace App\Filament\Resources\Faculties\Tables;

use App\Features\Faculties\Enums\EmploymentType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FacultiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Faculty Staff Information')
            ->description('List of faculty members along with their details.')
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

                TextColumn::make('employment_type')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('department.code')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record): string => self::resolveDepartmentColor($record->department?->code, $record->department?->color))
                    ->toggleable(),

                TextColumn::make('courses.code')
                    ->label('Courses')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('email')
                    ->searchable()
                    ->toggleable()
                    ->icon(Heroicon::Envelope),

                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable()
                    ->icon(Heroicon::Phone),

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
                SelectFilter::make('employment_type')
                    ->options(EmploymentType::class),
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

    private static function resolveDepartmentColor(?string $code, ?string $color): string
    {
        if ($color) {
            return self::normalizeDepartmentColor($color);
        }

        return match (strtoupper($code ?? '')) {
            'CTE' => 'blue',
            'CTM' => 'yellow',
            'CMU' => 'red',
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
