<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'rows_count',
        'seats_per_row',
    ];

    protected $casts = [
        'row_count' => 'integer',
        'seats_per_row' => 'integer',
    ];


    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    // хелпер общее число мест в зале
    public function totalSeats(): int 
    {
        return $this->seats()->count();
    }
}
