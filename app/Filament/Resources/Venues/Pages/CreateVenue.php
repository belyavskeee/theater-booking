<?php

namespace App\Filament\Resources\Venues\Pages;

use App\Filament\Resources\Venues\VenueResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVenue extends CreateRecord
{
    protected static string $resource = VenueResource::class;

    protected function afterCreate(): void
    {
        $venue = $this->record;

        for ($row = 1; $row <= $venue->rows_count; $row++) {
            for ($seat = 1; $seat <= $venue->seats_per_row; $seat++) {
                $venue->seats()->create([
                    'row_number' => $row,
                    'seat_number' => $seat,
                    'sector' => 'Партер',
                ]);
            }
        }
    }
}