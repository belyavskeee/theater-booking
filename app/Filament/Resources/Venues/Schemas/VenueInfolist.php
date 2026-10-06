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

                Section::make('Вместимость')
                    ->schema([
                        TextEntry::make('sectors_count')
                            ->label('Секторов')
                            ->state(fn ($record) => $record->sectors()->count())
                            ->badge()
                            ->color('info'),

                        TextEntry::make('capacity')
                            ->label('Проектная вместимость')
                            ->state(fn ($record) => $record->sectors->sum(
                                fn ($s) => $s->rows_count * $s->seats_per_row
                            ))
                            ->badge()
                            ->color('primary')
                            ->helperText('Сумма по всем секторам.'),

                        TextEntry::make('seats_count')
                            ->label('Мест в базе')
                            ->state(fn ($record) => $record->seats()->count())
                            ->badge()
                            ->color(fn ($record) => $record->seats()->count() === $record->sectors->sum(
                                fn ($s) => $s->rows_count * $s->seats_per_row
                            ) ? 'success' : 'danger')
                            ->helperText('Зелёный — совпадает с проектной. Красный — есть расхождения.'),
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