<?php

namespace App\Filament\Resources\Performances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PerformanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основное')
                    ->schema([
                        Select::make('spectacle_id')
                            ->label('Спектакль')
                            ->relationship('spectacle', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('venue_id')
                            ->label('Зал')
                            ->relationship('venue', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        DateTimePicker::make('starts_at')
                            ->label('Дата и время показа')
                            ->required()
                            ->seconds(false)
                            ->minutesStep(5)
                            ->displayFormat('d.m.Y H:i')
                            ->native(false),

                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'scheduled' => 'Запланирован',
                                'cancelled' => 'Отменён',
                                'finished'  => 'Завершён',
                            ])
                            ->default('scheduled')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Цена')
                    ->description('Базовая цена — точка отсчёта. Для конкретных секторов цену можно переопределить в блоке «Цены по секторам» ниже.')
                    ->schema([
                        TextInput::make('base_price')
                            ->label('Базовая цена')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->suffix('BYN')
                            ->default(0),
                    ]),
            ]);
    }
}