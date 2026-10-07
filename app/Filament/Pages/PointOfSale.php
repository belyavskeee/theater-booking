<?php

namespace App\Filament\Pages;

use App\Models\Performance;
use App\Models\Seat;
use App\Models\Ticket;
use App\Services\BookingService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class PointOfSale extends Page
{
    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-shopping-cart';
    protected static string|UnitEnum|null    $navigationGroup = 'Продажи';
    protected static ?string                 $navigationLabel = 'Касса';
    protected static ?int                    $navigationSort  = 1;
    protected static ?string                 $title           = 'Касса';

    protected string $view = 'filament.pages.point-of-sale';

    public ?int   $performanceId = null;
    public array  $selectedSeats = [];
    public string $customerName  = '';
    public string $customerPhone = '';
    public string $customerEmail = '';
    public string $paymentMethod = 'cash';

    public function mount(): void
    {
        $this->performanceId = Performance::upcoming()->value('id');
    }

    public function toggleSeat(int $seatId, float $price): void
    {
        if (isset($this->selectedSeats[$seatId])) {
            unset($this->selectedSeats[$seatId]);
        } else {
            $this->selectedSeats[$seatId] = $price;
        }
    }

    public function clearCart(): void
    {
        $this->selectedSeats = [];
        $this->reset(['customerName', 'customerPhone', 'customerEmail']);
    }

    public function getPerformanceProperty(): ?Performance
    {
        return $this->performanceId
            ? Performance::with('spectacle', 'venue')->find($this->performanceId)
            : null;
    }

    public function getPerformancesProperty()
    {
        return Performance::upcoming()->with('spectacle', 'venue')->take(50)->get();
    }

    public function getSeatsBySectorProperty(): array
    {
        $performance = $this->performance;
        if (!$performance) return [];

        $takenIds = Ticket::where('performance_id', $performance->id)
            ->pluck('seat_id')
            ->all();

        return Seat::where('seats.venue_id', $performance->venue_id)
            ->with('sector')
            ->get()
            ->sortBy([
                fn ($a, $b) => ($a->sector?->sort_order ?? 0) <=> ($b->sector?->sort_order ?? 0),
                ['row_number', 'asc'],
                ['seat_number', 'asc'],
            ])
            ->groupBy(fn ($seat) => $seat->sector?->name ?? 'Без сектора')
            ->map(function ($seats, $sectorName) use ($takenIds, $performance) {
                $sector = $seats->first()->sector;

                return [
                    'name'     => $sectorName,
                    'modifier' => (float) ($sector?->price_modifier ?? 1.0),
                    'count'    => $seats->count(),
                    'rows'     => $seats
                        ->groupBy('row_number')
                        ->map(fn ($row) => $row->map(fn ($seat) => [
                            'id'     => $seat->id,
                            'number' => $seat->seat_number,
                            'row'    => $seat->row_number,
                            'taken'  => in_array($seat->id, $takenIds),
                            'price'  => $performance->priceFor($seat),
                        ])->all())
                        ->all(),
                ];
            })
            ->values()
            ->all();
    }

    /** Статистика по залу: продано/свободно/всего */
    public function getHallStatsProperty(): array
    {
        $performance = $this->performance;
        if (!$performance) return ['total' => 0, 'taken' => 0, 'available' => 0, 'percent' => 0];

        $total = $performance->totalSeats();
        $taken = $performance->takenSeatsCount();
        $available = max(0, $total - $taken);

        return [
            'total'     => $total,
            'taken'     => $taken,
            'available' => $available,
            'percent'   => $total > 0 ? round(($taken / $total) * 100) : 0,
        ];
    }

    /** Детали выбранных мест для корзины */
    public function getCartDetailsProperty(): array
    {
        if (empty($this->selectedSeats)) return [];

        return Seat::whereIn('id', array_keys($this->selectedSeats))
            ->with('sector')
            ->get()
            ->map(fn ($seat) => [
                'id'     => $seat->id,
                'row'    => $seat->row_number,
                'number' => $seat->seat_number,
                'sector' => $seat->sector?->name ?? '—',
                'price'  => $this->selectedSeats[$seat->id],
            ])
            ->sortBy(['row', 'number'])
            ->values()
            ->all();
    }

    public function getTotalProperty(): float
    {
        return array_sum($this->selectedSeats);
    }

    public function getSelectedSeatsCountProperty(): int
    {
        return count($this->selectedSeats);
    }

    public function checkout(BookingService $service): void
    {
        $performance = $this->performance;
        if (!$performance || empty($this->selectedSeats)) {
            Notification::make()->warning()->title('Выберите места')->send();
            return;
        }

        if (empty($this->customerName)) {
            Notification::make()->warning()->title('Укажите имя покупателя')->send();
            return;
        }

        try {
            $booking = $service->create(
                $performance,
                array_keys($this->selectedSeats),
                [
                    'name'           => $this->customerName,
                    'phone'          => $this->customerPhone,
                    'email'          => $this->customerEmail,
                    'payment_method' => $this->paymentMethod,
                ],
                source: 'cashier',
                userId: auth()->id(),
            );

            Notification::make()
                ->success()
                ->title('Продажа оформлена')
                ->body("Заказ {$booking->order_number} · {$booking->total_price} BYN")
                ->send();

            $this->clearCart();
            $this->performanceId = Performance::upcoming()->value('id');
        } catch (\Throwable $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка продажи')
                ->body($e->getMessage())
                ->persistent()
                ->send();
        }
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin')
            || auth()->user()?->hasRole('cashier');
    }
}