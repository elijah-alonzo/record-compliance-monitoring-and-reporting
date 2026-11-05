<?php

namespace App\Filament\Resources\Loads\Pages;

use App\Filament\Resources\Loads\LoadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoad extends EditRecord
{
    protected static string $resource = LoadResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $isSubmitted = (bool) ($data['status'] ?? false);

        if (! $isSubmitted) {
            $data['submitted_at'] = null;

            return $data;
        }

        $data['submitted_at'] = $this->record->submitted_at ?? now();

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->successRedirectUrl($this->getResource()::getUrl('index')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
