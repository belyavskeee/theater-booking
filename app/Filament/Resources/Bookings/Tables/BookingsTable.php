<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Номер заказа')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('customer_name')
                    ->label('Клиент')
                    ->searchable()
                    ->default('—'),

                TextColumn::make('performance.spectacle.title')
                    ->label('Спектакль')
                    ->searchable()
                    ->wrap()
                    ->limit(30),

                TextColumn::make('performance.starts_at')
                    ->label('Дата показа')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('tickets_count')
                    ->label('Мест')
                    ->counts('tickets')
                    ->badge()
                    ->color('info'),

                TextColumn::make('total_price')
                    ->label('Сумма')
                    ->money('BYN')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending'   => 'Ожидает оплаты',
                        'paid'      => 'Оплачено',
                        'cancelled' => 'Отменено',
                        'expired'   => 'Истекла',
                        default     => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'pending'   => 'warning',
                        'paid'      => 'success',
                        'cancelled' => 'danger',
                        'expired'   => 'gray',
                        default     => 'gray',
                    }),

                TextColumn::make('payment_method')
                    ->label('Способ оплаты')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'card'       => 'Карта',
                        'erip'       => 'ЕРИП',
                        'apple_pay'  => 'Apple Pay',
                        'google_pay' => 'Google Pay',
                        default      => '—',
                    })
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('customer_phone')
                    ->label('Телефон')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('paid_at')
                    ->label('Оплачено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()->label('Просмотр'),
                EditAction::make()->label('Изменить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'pending'   => 'Ожидает оплаты',
                        'paid'      => 'Оплачено',
                        'cancelled' => 'Отменено',
                        'expired'   => 'Истекла',
                    ]),

                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('С даты'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('По дату'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ]);
    }
}