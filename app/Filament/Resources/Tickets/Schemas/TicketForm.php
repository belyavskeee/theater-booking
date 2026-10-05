<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_id')
                    ->required()
                    ->numeric(),
                TextInput::make('performance_id')
                    ->required()
                    ->numeric(),
                TextInput::make('seat_id')
                    ->required()
                    ->numeric(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('qr_code')
                    ->required(),
            ]);
    }
}
