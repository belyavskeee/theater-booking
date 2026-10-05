<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookings extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Последние брони';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['performance.spectacle', 'user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('Номер заказа')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('customer_name')
                    ->label('Клиент')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('performance.spectacle.title')
                    ->label('Спектакль')
                    ->limit(30)
                    ->wrap(),

                TextColumn::make('performance.starts_at')
                    ->label('Дата показа')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Сумма')
                    ->money('BYN', divideBy: 1)
                    ->sortable(),

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

                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}