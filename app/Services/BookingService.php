<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Performance;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * Создаёт бронь с защитой от одновременного бронирования.
     *
     * @param Performance $performance
     * @param array<int>  $seatIds    массив id мест
     * @param array       $customer   ['name', 'phone', 'email', 'payment_method']
     * @param string      $source     'online' | 'cashier'
     * @param int|null    $userId
     * @return Booking
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function create(
        Performance $performance,
        array $seatIds,
        array $customer = [],
        string $source = 'online',
        ?int $userId = null
    ): Booking {
        // === Валидация входных данных ===

        $seatIds = array_values(array_unique(array_map('intval', $seatIds)));

        if (empty($seatIds)) {
            throw new \InvalidArgumentException('Не выбрано ни одного места.');
        }

        if ($source !== 'cashier' && $source !== 'online') {
            throw new \InvalidArgumentException("Неизвестный источник брони: {$source}");
        }

        if (! $performance->isAvailableForSale()) {
            throw new \RuntimeException('Этот показ больше недоступен для продажи.');
        }

        // Проверяем, что все места действительно из зала этого показа.
        // Это защита от подмены seat_id через DevTools.
        $validSeatsCount = Seat::whereIn('id', $seatIds)
            ->where('venue_id', $performance->venue_id)
            ->count();

        if ($validSeatsCount !== count($seatIds)) {
            throw new \RuntimeException('Некоторые места не принадлежат залу этого показа.');
        }

        // === Транзакция с 3 попытками на случай deadlock ===
        // При большой нагрузке MySQL может откатить транзакцию с deadlock.
        // Повторная попытка почти всегда проходит успешно.
        return DB::transaction(function () use ($performance, $seatIds, $customer, $source, $userId) {

            // === 1. ЗАХВАТ БЛОКИРОВОК НА МЕСТА ===
            // Это ключевой момент. lockForUpdate() заблокирует строки в таблице seats,
            // и вторая транзакция, обращающаяся к тем же местам, будет ждать
            // завершения первой, прежде чем продолжить.
            $seats = Seat::whereIn('id', $seatIds)
                ->lockForUpdate()
                ->get();

            if ($seats->count() !== count($seatIds)) {
                throw new \RuntimeException('Некоторые места не найдены в базе данных.');
            }

            // === 2. ПРОВЕРКА ЗАНЯТОСТИ (уже под блокировкой) ===
            $takenSeatIds = Ticket::where('performance_id', $performance->id)
                ->whereIn('seat_id', $seatIds)
                ->pluck('seat_id')
                ->all();

            if (! empty($takenSeatIds)) {
                // Формируем понятное сообщение с номерами занятых мест
                $takenLabels = $seats
                    ->whereIn('id', $takenSeatIds)
                    ->map(fn (Seat $s) => "Ряд {$s->row_number}, место {$s->seat_number}")
                    ->implode('; ');

                throw new \RuntimeException("Места уже заняты: {$takenLabels}. Выберите другие.");
            }

            // === 3. СЧИТАЕМ ЦЕНЫ ===
            $total = 0.0;
            $ticketsData = [];

            foreach ($seats as $seat) {
                $price = $performance->priceFor($seat);
                $total += $price;

                $ticketsData[] = [
                    'seat_id' => $seat->id,
                    'price'   => $price,
                ];
            }

            $total = round($total, 2);

            // === 4. СОЗДАЁМ БРОНЬ ===
            $isCashier = $source === 'cashier';

            $booking = Booking::create([
                'performance_id' => $performance->id,
                'user_id'        => $userId,
                'status'         => $isCashier ? 'paid' : 'pending',
                'total_price'    => $total,
                'customer_name'  => $customer['name']  ?? null,
                'customer_phone' => $customer['phone'] ?? null,
                'customer_email' => $customer['email'] ?? null,
                'payment_method' => $customer['payment_method'] ?? ($isCashier ? 'cash' : null),
                'paid_at'        => $isCashier ? now() : null,
                'expires_at'     => $isCashier ? null : now()->addMinutes(5),
            ]);
            // order_number генерируется автоматически в модели Booking (booted → creating)

            // === 5. СОЗДАЁМ БИЛЕТЫ ===
            foreach ($ticketsData as $data) {
                $booking->tickets()->create([
                    'performance_id' => $performance->id,
                    'seat_id'        => $data['seat_id'],
                    'price'          => $data['price'],
                ]);
            }

            // Возвращаем свежий объект со всеми связями для последующей генерации PDF
            return $booking->fresh([
                'tickets.seat.sector',
                'performance.spectacle',
                'performance.venue',
            ]);
        }, 3); // 3 попытки при deadlock
    }

    /**
     * Отменить бронь. Удаляет все связанные билеты и освобождает места.
     */
    public function cancel(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->tickets()->delete();
            $booking->update([
                'status' => 'cancelled',
            ]);
        });
    }

    /**
     * Подтвердить оплату онлайн-брони (когда пользователь заплатил в интернете).
     */
    public function confirmPayment(Booking $booking, string $paymentMethod = 'card'): Booking
    {
        return DB::transaction(function () use ($booking, $paymentMethod) {
            $booking->update([
                'status'         => 'paid',
                'payment_method' => $paymentMethod,
                'paid_at'        => now(),
                'expires_at'     => null,
            ]);

            return $booking->fresh(['tickets', 'performance.spectacle', 'performance.venue']);
        });
    }

    /**
     * Очистить истёкшие брони (pending, у которых expires_at < now).
     * Вызывается из Artisan-команды по расписанию (каждую минуту).
     *
     * @return int  количество отменённых броней
     */
    public function cleanupExpired(): int
    {
        return DB::transaction(function () {
            $expired = Booking::query()
                ->where('status', 'pending')
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->lockForUpdate()
                ->get();

            $count = 0;

            foreach ($expired as $booking) {
                $booking->tickets()->delete();
                $booking->update(['status' => 'expired']);
                $count++;
            }

            if ($count > 0) {
                Log::info("BookingService: cleaned {$count} expired bookings");
            }

            return $count;
        });
    }
}