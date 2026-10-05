<?php

namespace App\Filament\Resources\Performances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PerformanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('spectacle_id')
                    ->numeric(),
                TextEntry::make('venue_id')
                    ->numeric(),
                TextEntry::make('starts_at')
                    ->dateTime(),
                TextEntry::make('base_price')
                    ->money(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
