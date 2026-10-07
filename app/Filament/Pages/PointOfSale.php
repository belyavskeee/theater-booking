<?php

namespace App\Filament\Pages;

use App\Models\Performance;
use App\Models\Seat;
use App\Models\Ticket;
use App\Services\BookingService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PointOfSale extends Page
{
    protected static string|\BackedEnum|null $navigationIcon  = 'heroicon-o-shopping-cart';
    protected static string|\UnitEnum|null    $navigationGroup = 'Продажи';
    protected static ?string                  $navigationLabel = 'Касса';
    protected static ?int                     $navigationSort  = 1;
    protected static ?string                  $title           = 'Касса';
    protected string                          $view            = 'filament.pages.point-of-sale';

    public ?int   $performanceId  = null;
    public array  $selectedSeats  = []; // [seat_id => price]
    public string $customerName   = '';
    public string $customerPhone  = '';
    public string $customerEmail  = '';
    public string $paymentMethod  = 'cash';

    public function mount(): void
    {
        $this->performanceId = Performance::upcoming()->value('id');
    }

    public function selectPerformance(int $id): void
    {
        $this->performanceId = $id;
        $this->selectedSeats = [];
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
        return $this->performanceId ? Performance::with('spectacle', 'venue')->find($this->performanceId) : null;
    }

    public function getPerformancesProperty()
    {
        return Performance::upcoming()->with('spectacle', 'venue')->take(50)->get();
    }

    public function getSeatsByRowProperty()
    {
        $performance = $this->performance;
        if (!$performance) return [];

        $takenIds = Ticket::where('performance_id', $performance->id)->pluck('seat_id')->all();

        return Seat::where('venue_id', $performance->venue_id)
            ->orderBy('row_number')->orderBy('seat_number')
            ->get()
            ->groupBy('row_number')
            ->map(fn ($row) => $row->map(fn ($seat) => [
                'id'     => $seat->id,
                'number' => $seat->seat_number,
                'taken'  => in_array($seat->id, $takenIds),
                'price'  => $performance->priceFor($seat),
            ])->all())
            ->all();
    }

    public function getTotalProperty(): float
    {
        return array_sum($this->selectedSeats);
    }

    public function checkout(BookingService $service): void
    {
        $performance = $this->performance;
        if (!$performance || empty($this->selectedSeats)) {
            Notification::make()->warning()->title('Выберите места')->send();
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
                ->title("Продажа оформлена: {$booking->order_number}")
                ->send();

            $this->clearCart();
            $this->performanceId = Performance::upcoming()->value('id');
        } catch (\Throwable $e) {
            Notification::make()->danger()->title('Ошибка: ' . $e->getMessage())->persistent()->send();
        }
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() || auth()->user()?->isCashier();
    }
}