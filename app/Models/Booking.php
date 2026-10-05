<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'performance_id',
        'status',
        'total_price',
        'expires_at',
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // boot
    protected static function booted(): void 
    {
        static::creating(function (self $booking) {
            if (empty($booking->order_number)) {
                $booking->order_number = self::generateOrderNumber();
            }
        });
    }

    // связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function performance()
    {
        return $this->belongsTo(Performance::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // хелперы
    // генерация человеческого ном. заказа: TB-2025-000123
    public static function generateOrderNumber(): string 
    {
        $year = now()->year;
        $count = static::whereYear('created_at', $year)->count() + 1;

        return 'ТВ-' . $year . '-' . str_pad((string) $count, 6, '0', STR_PAD_LEFT);
    }

    // истекла ли бронь (для pending)
    public function isExpired(): bool 
    {
        return $this->status === 'pending'
            && $this->expires_at
            && $this->expires_at->isPast();
    }

    // оплачено ли
    public function isPaid(): bool 
    {
        return $this->status === 'paid';
    }

    // скоупы
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '<', now());
    }
}
