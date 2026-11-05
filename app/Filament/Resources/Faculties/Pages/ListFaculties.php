<?php

namespace App\Filament\Resources\Faculties\Pages;

use App\Features\Departments\Models\Department;
use App\Filament\Imports\FacultyImporter;
use App\Filament\Resources\Faculties\FacultyResource;
use App\Filament\Widgets\FacultyTypeStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListFaculties extends ListRecords
{
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(FacultyImporter::class),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            FacultyTypeStatsWidget::class,
        ];
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query),
        ];

        Department::query()
            ->orderBy('code')
            ->get()
            ->each(function (Department $department) use (&$tabs): void {
                $tabs[$department->code] = Tab::make($department->code)
                    ->modifyQueryUsing(
                        fn (Builder $query): Builder => $query->where('department_id', $department->id)
                    );
            });

        return $tabs;
    }
}
