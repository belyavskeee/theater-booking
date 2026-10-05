<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Performance;
use App\Models\Spectacle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Всего спектаклей', Spectacle::count())
                ->description('В репертуаре')
                ->descriptionIcon('heroicon-m-film')
                ->color('primary'),

            Stat::make('Ближайших показов', Performance::upcoming()->count())
                ->description('Запланировано')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('Броней за сегодня', Booking::whereDate('created_at', today())->count())
                ->description('Новых заказов')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Выручка за месяц', number_format((float) Booking::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->sum('total_price'), 2) . ' BYN')
                ->description('Оплачено')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}