<?php

namespace App\Filament\Resources\Seats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SeatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('venue.name')
                    ->label('Зал')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('row_number')
                    ->label('Ряд')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('seat_number')
                    ->label('Место')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('sector')
                    ->label('Сектор')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price_modifier')
                    ->label('Коэффициент цены')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('×')
                    ->sortable()
                    ->color(fn ($state) => $state > 1 ? 'success' : 'gray')
                    ->toggleable(),

                TextColumn::make('tickets_count')
                    ->label('Билетов продано')
                    ->counts('tickets')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('venue_id')
            ->filters([
                SelectFilter::make('venue_id')
                    ->label('Зал')
                    ->relationship('venue', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()->label('Изменить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}