<?php

namespace App\Filament\Resources\Performances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PerformanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Показ')
                    ->schema([
                        TextEntry::make('spectacle.title')
                            ->label('Спектакль')
                            ->weight('bold')
                            ->size('lg')
                            ->url(fn ($record) => route('filament.admin.resources.spectacles.edit', $record->spectacle_id))
                            ->color('primary'),

                        TextEntry::make('venue.name')
                            ->label('Зал')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('starts_at')
                            ->label('Дата и время')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'scheduled' => 'Запланирован',
                                'cancelled' => 'Отменён',
                                'finished'  => 'Завершён',
                                default     => $state,
                            })
                            ->color(fn (string $state) => match ($state) {
                                'scheduled' => 'success',
                                'cancelled' => 'danger',
                                'finished'  => 'gray',
                                default     => 'gray',
                            }),
                    ])
                    ->columns(2),

                Section::make('Цены и продажи')
                    ->schema([
                        TextEntry::make('base_price')
                            ->label('Базовая цена')
                            ->money('BYN')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('tickets_count')
                            ->label('Продано билетов')
                            ->state(fn ($record) => $record->tickets()->count())
                            ->badge()
                            ->color('warning'),

                        TextEntry::make('available')
                            ->label('Свободно мест')
                            ->state(fn ($record) => $record->availableSeatsCount())
                            ->badge()
                            ->color('success'),

                        TextEntry::make('demand')
                            ->label('Спрос')
                            ->state(fn ($record) => $record->demandLabel())
                            ->badge()
                            ->color(fn ($record) => match ($record->demandStatus()) {
                                'sold_out'  => 'danger',
                                'few'       => 'danger',
                                'high'      => 'warning',
                                'available' => 'success',
                                default     => 'gray',
                            }),
                    ])
                    ->columns(4),

                Section::make('Служебное')
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