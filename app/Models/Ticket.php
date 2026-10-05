<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'performance_id',
        'seat_id',
        'price',
        'qr-code'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // boot
    protected static function booted(): void 
    {
        static::creating(function (self $ticket) {
            if (empty($ticket->qr_code)) {
                $ticket->qr_code = self::generateQrCode();
            }
        });
    }

    // связи
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function performance()
    {
        return $this->belongsTo(Performance::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    //хелперы
    // уникальный код для qr
    public static function generateQrCode(): string
    {
        return strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
    }

    // является ли билет "предстоящим"
    public function isUpcoming(): bool
    {
        return $this->performance->starts_at->isFuture();
    }
}
