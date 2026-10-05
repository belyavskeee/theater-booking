<?php

namespace App\Filament\Resources\Seats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SeatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('venue_id')
                    ->required()
                    ->numeric(),
                TextInput::make('row_number')
                    ->required()
                    ->numeric(),
                TextInput::make('seat_number')
                    ->required()
                    ->numeric(),
                TextInput::make('sector')
                    ->required()
                    ->default('Партер'),
                TextInput::make('price_modifier')
                    ->required()
                    ->numeric()
                    ->default(1.0),
            ]);
    }
}
