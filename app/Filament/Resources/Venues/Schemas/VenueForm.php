<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('rows_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('seats_per_row')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
