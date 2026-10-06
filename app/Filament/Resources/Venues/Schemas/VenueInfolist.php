<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VenueInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основная информация')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Название зала')
                            ->weight('bold'),

                        TextEntry::make('address')
                            ->label('Адрес')
                            ->icon('heroicon-o-map-pin'),
                    ])
                    ->columns(2),

                Section::make('Размеры зала')
                    ->schema([
                        TextEntry::make('rows_count')
                            ->label('Количество рядов')
                            ->numeric()
                            ->badge()
                            ->color('info'),

                        TextEntry::make('seats_per_row')
                            ->label('Мест в ряду')
                            ->numeric()
                            ->badge()
                            ->color('info'),

                        TextEntry::make('seats_count')
                            ->label('Всего мест (в базе)')
                            ->state(fn ($record) => $record->seats()->count())
                            ->numeric()
                            ->badge()
                            ->color('success')
                            ->helperText('Если не совпадает с «Ряды × Мест в ряду», значит, места не сгенерированы.'),
                    ])
                    ->columns(3),

                Section::make('Служебная информация')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Создан')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),

                        TextEntry::make('updated_at')
                            ->label('Обновлён')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}