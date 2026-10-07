<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;

    protected $fillable = [
        'spectacle_id',
        'venue_id',
        'starts_at',
        'base_price',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'base_price' => 'decimal:2',
    ];


    public function spectacle()
    {
        return $this->belongsTo(Spectacle::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Хелепры для UI
    // общее чисто мест в зале показа
    public function totalSeats(): int
    {
        return $this->venue->seats()->count();
    }

    // сколько занято (только оплаченные брони)
    public function takenSeatsCount(): int
    {
        return $this->tickets()
            ->whereHas('booking', fn ($q) => $q->whereIn('status', ['paid', 'pending']))
            ->count();
    }

    // сколько свободно
    public function availableSeatsCount(): int 
    {
        return max(0, $this->totalSeats() - $this->takenSeatsCount());
    }

    public function priceFor(Seat $seat): float
    {
        $seatModifier   = (float) ($seat->price_modifier ?? 1.0);
        $sectorModifier = (float) ($seat->sector?->price_modifier ?? 1.0);

        return round($this->base_price * $seatModifier * $sectorModifier, 2);
    }

    /**
     * Статус спроса для UI:
     * sold_out — Нет мест
     * few — Мало мест (<15%)
     * high — Высокий спрос (<50%)
     * available — Доступны места
     */
    public function demandStatus(): string 
    {
        $total = $this->totalSeats();
        if ($total === 0) {
            return 'unknown';
        }

        $available = $this->availableSeatsCount();

        if ($available === 0) {
            return 'sold_out';
        }
        if ($available / $total < 0.15) {
            return 'few';
        }
        if ($available / $total < 0.5) {
            return 'high';
        }

        return 'available';
    }

    // человеческий статус спроса
    public function demandLabel(): string 
    {
        return match ($this->demandStatus()) {
            'sold_out' => 'Нет мест',
            'few' => 'Мало мест',
            'high' => 'Высокий спрос',
            'available' => 'Доступны места',
            default => '-',
        };
    }

    // Отменен ли показ/прошел уже
    public function isAvailableForSale(): bool 
    {
        return $this->status === 'scheduled' && $this->starts_at->isFuture();
    }

    // скоупы
    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('starts_at');
    }
}
