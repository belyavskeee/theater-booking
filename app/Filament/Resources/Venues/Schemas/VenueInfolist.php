<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VenueInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('address'),
                TextEntry::make('rows_count')
                    ->numeric(),
                TextEntry::make('seats_per_row')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
