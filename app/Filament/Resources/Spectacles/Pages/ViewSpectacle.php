<?php

namespace App\Filament\Resources\Spectacles\Pages;

use App\Filament\Resources\Spectacles\SpectacleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSpectacle extends ViewRecord
{
    protected static string $resource = SpectacleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
