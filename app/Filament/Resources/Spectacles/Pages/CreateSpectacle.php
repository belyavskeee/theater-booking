<?php

namespace App\Filament\Resources\Spectacles\Pages;

use App\Filament\Resources\Spectacles\SpectacleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSpectacle extends CreateRecord
{
    protected static string $resource = SpectacleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
