<?php

namespace App\Filament\Resources\Spectacles\Pages;

use App\Filament\Resources\Spectacles\SpectacleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSpectacle extends EditRecord
{
    protected static string $resource = SpectacleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
