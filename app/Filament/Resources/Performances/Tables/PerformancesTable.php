<?php

namespace App\Filament\Resources\Performances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PerformancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('spectacle.title')
                    ->label('Спектакль')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap()
                    ->limit(40),

                TextColumn::make('venue.name')
                    ->label('Зал')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('starts_at')
                    ->label('Дата и время')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),

                TextColumn::make('base_price')
                    ->label('Базовая цена')
                    ->money('BYN')
                    ->sortable(),

                TextColumn::make('status')
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

                TextColumn::make('tickets_count')
                    ->label('Продано билетов')
                    ->counts('tickets')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Обновлён')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('starts_at', 'desc')
            ->recordActions([
                ViewAction::make()->label('Просмотр'),
                EditAction::make()->label('Изменить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}