<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основная информация')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название зала')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Например: Большая сцена')
                            ->helperText('Название, которое будет отображаться на сайте.'),

                        TextInput::make('address')
                            ->label('Адрес')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('г. Минск, ул. Примерная, 1')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}