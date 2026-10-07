<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Performance;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Создаёт бронь.
     * @param array $seatIds  массив id мест
     * @param array $customer ['name', 'phone', 'email', 'payment_method']
     * @param string $source  'online' | 'cashier'
     */
    public function create(
        Performance $performance,
        array $seatIds,
        array $customer = [],
        string $source = 'online',
        ?int $userId = null
    ): Booking {
        return DB::transaction(function () use ($performance, $seatIds, $customer, $source, $userId) {
            // 1. Блокируем занятые места (защита от гонок)
            $taken = Ticket::where('performance_id', $performance->id)
                ->whereIn('seat_id', $seatIds)
                ->lockForUpdate()
                ->pluck('seat_id')
                ->all();

            if (!empty($taken)) {
                throw new \RuntimeException('Места уже заняты: ' . implode(', ', $taken));
            }

            // 2. Считаем сумму и готовим билеты
            $total = 0;
            $tickets = [];
            foreach ($seatIds as $seatId) {
                $seat = Seat::findOrFail($seatId);
                $price = $performance->priceFor($seat);
                $total += $price;
                $tickets[] = ['seat_id' => $seatId, 'price' => $price];
            }

            // 3. Создаём бронь
            $booking = Booking::create([
                'performance_id'  => $performance->id,
                'user_id'         => $userId,
                'status'          => $source === 'cashier' ? 'paid' : 'pending',
                'total_price'     => $total,
                'customer_name'   => $customer['name']  ?? null,
                'customer_phone'  => $customer['phone'] ?? null,
                'customer_email'  => $customer['email'] ?? null,
                'payment_method'  => $customer['payment_method'] ?? ($source === 'cashier' ? 'cash' : null),
                'paid_at'         => $source === 'cashier' ? now() : null,
                'expires_at'      => $source === 'cashier' ? null : now()->addMinutes(5),
            ]);

            // 4. Создаём билеты
            foreach ($tickets as $t) {
                $booking->tickets()->create([
                    'performance_id' => $performance->id,
                    'seat_id'        => $t['seat_id'],
                    'price'          => $t['price'],
                ]);
            }

            return $booking;
        });
    }
}