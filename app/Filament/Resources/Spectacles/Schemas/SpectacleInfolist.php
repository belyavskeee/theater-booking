<?php

namespace App\Filament\Resources\Spectacles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SpectacleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('short_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('age_limit')
                    ->numeric(),
                TextEntry::make('duration_minutes')
                    ->numeric(),
                TextEntry::make('intermission_minutes')
                    ->numeric(),
                TextEntry::make('director')
                    ->placeholder('-'),
                TextEntry::make('artist')
                    ->placeholder('-'),
                TextEntry::make('poster_path')
                    ->placeholder('-'),
                TextEntry::make('trailer_url')
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
