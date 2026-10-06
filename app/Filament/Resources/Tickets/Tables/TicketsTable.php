<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('qr_code')
                    ->label('QR-код')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary')
                    ->fontFamily('mono'),

                TextColumn::make('booking.order_number')
                    ->label('Заказ')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),

                TextColumn::make('performance.spectacle.title')
                    ->label('Спектакль')
                    ->searchable()
                    ->wrap()
                    ->limit(30),

                TextColumn::make('performance.starts_at')
                    ->label('Дата показа')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),

                TextColumn::make('performance.venue.name')
                    ->label('Зал')
                    ->badge()
                    ->color('info'),

                TextColumn::make('seat')
                    ->label('Место')
                    ->state(fn ($record) => "Ряд {$record->seat->row_number}, место {$record->seat->seat_number}")
                    ->badge()
                    ->color('success'),

                TextColumn::make('price')
                    ->label('Цена')
                    ->money('BYN')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('Продан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()->label('Просмотр'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}