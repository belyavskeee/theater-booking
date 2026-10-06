<?php

namespace App\Filament\Resources\Venues\Pages;

use App\Filament\Resources\Venues\VenueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVenue extends EditRecord
{
    protected static string $resource = VenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $venue = $this->record;

        $requiredRows = (int) $venue->rows_count;
        $requiredSeats = (int) $venue->seats_per_row;

        // 1. Добавляем недостающие места
        for ($row = 1; $row <= $requiredRows; $row++) {
            for ($seat = 1; $seat <= $requiredSeats; $seat++) {
                $exists = $venue->seats()
                    ->where('row_number', $row)
                    ->where('seat_number', $seat)
                    ->exists();

                if (!$exists) {
                    $venue->seats()->create([
                        'row_number' => $row,
                        'seat_number' => $seat,
                        'sector'     => 'Партер',
                    ]);
                }
            }
        }

        // 2. Удаляем лишние места (за пределами новых границ),но только те, на которые не проданы билеты
        $venue->seats()
            ->where(function ($q) use ($requiredRows, $requiredSeats) {
                $q->where('row_number', '>', $requiredRows)
                  ->orWhere(function ($q2) use ($requiredSeats) {
                      // Учитываем только места в допустимых рядах
                      $q2->where('row_number', '<=', $requiredRows)
                         ->where('seat_number', '>', $requiredSeats);
                  });
            })
            ->whereDoesntHave('tickets') // ключевая защита
            ->delete();
    }
}