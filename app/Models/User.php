<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    const ROLE_ADMIN   = 'admin';
    const ROLE_CASHIER = 'cashier';
    const ROLE_USER    = 'user';

    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // связи
    public function bookings() 
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews() 
    {
        return $this->hasMany(Review::class);
    }

    public function reviewVotes() 
    {
        return $this->hasMany(ReviewVote::class);
    }

    //Хелперы
    //предстоящие билеты пользователя
    public function upcomingTickets()
    {
        return Ticket::whereHas('booking', fn ($q) => $q->where('user_id', $this->id)->where('status', 'paid'))
            ->whereHas('performance', fn ($q) => $q->where('status_at', '>=', now()))
            ->with(['performance.spectacle', 'performance.venue', 'seat'])
            ->get();
    }

    //прошедшие билеты
    public function pastTickets()
    {
        return Ticket::whereHas('booking', fn ($q) => $q->where('user_id', $this->id)->where('status', 'paid'))
            ->whereHas('performance', fn ($q) => $q->where('starts_at', '<', now()))
            ->with(['performance.spectacle', 'performance.venue', 'seat'])
            ->get();
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCashier(): bool
    {
        return $this->role === self::ROLE_CASHIER;
    }
}
