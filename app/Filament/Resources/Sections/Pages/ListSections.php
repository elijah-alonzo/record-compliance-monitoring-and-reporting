<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Imports\SectionImporter;
use App\Filament\Resources\Sections\SectionResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(SectionImporter::class),
        ];
    }
}
