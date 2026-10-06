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
    ];


    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function sectors() 
    { 
        return $this->hasMany(Sector::class)->orderBy('sort_order'); 
    }

    // Общее количество мест (по всем секторам)
    public function totalSeats(): int
    {
        return $this->seats()->count();
    }

    // Суммарная вместимость по конфигурации секторов (без обращения к БД)
    public function capacityAttribute(): int
    {
        return $this->sectors->sum(fn ($s) => $s->rows_count * $s->seats_per_row);
    }
}
