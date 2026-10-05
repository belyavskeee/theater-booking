<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'row_number',
        'seat_number',
        'sector',
        'price_modifier',
    ];

    protected $casts = [
        'row_number' => 'integer',
        'seat_number' => 'integer',
        'price_modifier' => 'decimal:2'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // хелперы
    // проверка занято то ли место на показе
    public function isTakenFor(Performance $performance): bool 
    {
        return $this->tickets()->where('performance_id', $performance->id)->exists();
    }

    // цена конкретного показа (учитывая модификатор)
    public function priceFor(Performance $performance): float 
    {
        return round($performance->base_price * this->price_modifier, 2);
    }

    // читаемое имя "Ряд 3, место 12"
    public function getLableAttribute(): string 
    {
        return "Ряд {$this->row_number}, место {$this->seat_number}";
    }
}
