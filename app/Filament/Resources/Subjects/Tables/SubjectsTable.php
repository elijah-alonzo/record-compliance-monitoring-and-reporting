<?php

namespace App\Filament\Resources\Subjects\Tables;

use App\Features\Subjects\Enums\SubjectType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Subject Information')
            ->description('List of subjects along with their details.')
            ->defaultPaginationPageOption(50)
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('subject_code')
                    ->label('Subject Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Subject Name')
                    ->searchable()
                    ->sortable()
                    ->grow()
                    ->toggleable(),

                TextColumn::make('units')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (?string $state): string => strtolower($state ?? '') === 'major' ? 'yellow' : 'success')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('course.code')
                    ->label('Course')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        default => 'primary',
                    })
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    })
                    ->placeholder('No description')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('type')
                    ->options(SubjectType::class),

                SelectFilter::make('course')
                    ->relationship('course', 'name')
                    ->searchable()
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
