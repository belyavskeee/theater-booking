<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'venue_id',
        'name',
        'rows_count',
        'seats_per_row',
        'price_modifier',
        'sort_order',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
