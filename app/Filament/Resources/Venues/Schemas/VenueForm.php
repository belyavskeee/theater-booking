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

                Section::make('Размеры зала')
                    ->description('Количество мест рассчитывается как «Ряды × Мест в ряду». После сохранения места сгенерируются автоматически.')
                    ->schema([
                        TextInput::make('rows_count')
                            ->label('Количество рядов')
                            ->required()
                            ->numeric()
                            ->default(10)
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('ряд.'),

                        TextInput::make('seats_per_row')
                            ->label('Мест в ряду')
                            ->required()
                            ->numeric()
                            ->default(12)
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('мест'),
                    ])
                    ->columns(2),
            ]);
    }
}