<?php

namespace App\Filament\Resources\Seats\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SeatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Расположение')
                    ->description('Эти поля нельзя изменить — они определяют физическое место в зале.')
                    ->schema([
                        Placeholder::make('venue_name')
                            ->label('Зал')
                            ->content(fn ($record) => $record?->venue?->name ?? '—'),

                        Placeholder::make('sector_name')
                            ->label('Сектор')
                            ->content(fn ($record) => $record?->sector?->name ?? '—'),

                        Placeholder::make('row_number')
                            ->label('Ряд')
                            ->content(fn ($record) => $record?->row_number ?? '—'),

                        Placeholder::make('seat_number')
                            ->label('Место')
                            ->content(fn ($record) => $record?->seat_number ?? '—'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                Section::make('Цена')
                    ->description('Если место должно стоить иначе, чем весь сектор — задайте коэффициент.')
                    ->schema([
                        TextInput::make('price_modifier')
                            ->label('Коэффициент цены')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->default(1.00)
                            ->minValue(0.1)
                            ->maxValue(10.0)
                            ->helperText('Множитель к базовой цене показа. 1.00 — как у всего сектора. 1.5 — на 50% дороже, 0.8 — на 20% дешевле.'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}