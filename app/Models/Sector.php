<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'rows_count',
        'seats_per_row',
        'price_modifier',
        'sort_order',
    ];

    protected $casts = [
        'rows_count'     => 'integer',
        'seats_per_row'  => 'integer',
        'price_modifier' => 'decimal:2',
        'sort_order'     => 'integer',
    ];

    // Eloquent-события

    protected static function booted(): void
    {
        static::created(function (self $sector) {
            $sector->generateSeats();
        });

        static::updated(function (self $sector) {
            if ($sector->wasChanged(['rows_count', 'seats_per_row'])) {
                $sector->generateSeats();
            }
        });
    }

    // Связи 

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    // Хелпер: генерация и синхронизация мест 

    public function generateSeats(): void
    {
        for ($row = 1; $row <= $this->rows_count; $row++) {
            for ($seat = 1; $seat <= $this->seats_per_row; $seat++) {
                $this->seats()->firstOrCreate(
                    [
                        'row_number'  => $row,
                        'seat_number' => $seat,
                    ],
                    [
                        'venue_id' => $this->venue_id,
                    ]
                );
            }
        }

        $this->seats()
            ->where(function ($q) {
                $q->where('row_number', '>', $this->rows_count)
                ->orWhere(function ($q2) {
                    $q2->where('row_number', '<=', $this->rows_count)
                        ->where('seat_number', '>', $this->seats_per_row);
                });
            })
            ->whereDoesntHave('tickets')
            ->delete();
    }
}