<?php

namespace App\Filament\Resources\Performances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PerformanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('spectacle_id')
                    ->required()
                    ->numeric(),
                TextInput::make('venue_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('starts_at')
                    ->required(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('status')
                    ->options(['scheduled' => 'Scheduled', 'cancelled' => 'Cancelled', 'finished' => 'Finished'])
                    ->default('scheduled')
                    ->required(),
            ]);
    }
}
