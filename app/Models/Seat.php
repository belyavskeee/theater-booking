<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'sector_id',       
        'row_number',
        'seat_number',
        'price_modifier',
    ];

    protected $casts = [
        'row_number'     => 'integer',
        'seat_number'    => 'integer',
        'price_modifier' => 'decimal:2',
    ];

    // Связи 

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Хелперы 

    public function isTakenFor(Performance $performance): bool
    {
        return $this->tickets()->where('performance_id', $performance->id)->exists();
    }

    public function priceFor(Performance $performance): float
    {
        $modifier = $this->price_modifier ?? $this->sector?->price_modifier ?? 1.0;
        return round($performance->base_price * $modifier, 2);
    }

    public function getLabelAttribute(): string
    {
        return "Ряд {$this->row_number}, место {$this->seat_number}";
    }
}