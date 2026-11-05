<?php

namespace App\Filament\Resources\Departments\RelationManagers;

use App\Filament\Imports\CourseImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    protected static ?string $title = 'Courses';

    protected static bool $shouldSkipAuthorization = true;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Course Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->placeholder('e.g. BSIT'),

                TextInput::make('name')
                    ->label('Course Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule) {
                        return $rule->where('department_id', $this->ownerRecord->getKey());
                    })
                    ->placeholder('Enter course name'),

                Textarea::make('description')
                    ->rows(3)
                    ->placeholder('Optional description'),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Courses')
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                TextColumn::make('name')
                    ->label('Course Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->placeholder('No description'),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Course')
                    ->color('info'),
                ImportAction::make()
                    ->importer(CourseImporter::class)
                    ->options([
                        'department_id' => $this->ownerRecord->getKey(),
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
