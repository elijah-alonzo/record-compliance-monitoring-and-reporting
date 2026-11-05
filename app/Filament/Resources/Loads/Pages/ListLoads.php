<?php

namespace App\Filament\Resources\Loads\Pages;

use App\Filament\Resources\Loads\LoadResource;
use App\Filament\Widgets\SubmissionRateWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLoads extends ListRecords
{
    protected static string $resource = LoadResource::class;

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query),
            'not-submitted' => Tab::make('Not Submitted')
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->where(function (Builder $innerQuery): void {
                        $innerQuery
                            ->where('status', false)
                            ->orWhereNull('submitted_at');
                    })
                ),
            'submitted' => Tab::make('Submitted')
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query
                        ->where('status', true)
                        ->whereNotNull('submitted_at')
                ),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SubmissionRateWidget::class,
        ];
    }
}
