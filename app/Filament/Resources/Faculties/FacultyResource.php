<?php

namespace App\Filament\Resources\Faculties;

use App\Features\Faculties\Models\Faculty;
use App\Filament\Resources\Faculties\Pages\CreateFaculty;
use App\Filament\Resources\Faculties\Pages\EditFaculty;
use App\Filament\Resources\Faculties\Pages\ListFaculties;
use App\Filament\Resources\Faculties\Pages\ViewFaculty;
use App\Filament\Resources\Faculties\Schemas\FacultyForm;
use App\Filament\Resources\Faculties\Tables\FacultiesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Faculty';

    protected static ?string $modelLabel = 'Faculty';

    protected static ?string $pluralModelLabel = 'Faculty';

    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';

    public static function form(Schema $schema): Schema
    {
        return FacultyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacultiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFaculties::route('/'),
            'create' => CreateFaculty::route('/create'),
            'view' => ViewFaculty::route('/{record}'),
            'edit' => EditFaculty::route('/{record}/edit'),
        ];
    }
}
