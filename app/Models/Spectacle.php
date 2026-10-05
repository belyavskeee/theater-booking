<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Spectacle extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'age_limit',
        'duration_minutes',
        'director',
        'artist',
        'author',
        'cast',
        'poster_path',
        'trailer_url',
        'is_active',
    ];

    protected $casts = [
        'cast' => 'array',
        'is_active' => 'boolean',
        'age_limit' => 'integer',
        'duration_minutes' => 'integer',
        'intermission_minutes' => 'integer',
    ];

    // boot
    protected static function booted(): void
    {
        static::creating(function (self $spectacle) {
            if (empty($spectacle->slug)) {
                $spectacle->slug = Str::slug($spectacle->title);
            }
        });
    }

    // route model binding по slug
    public function getRouteKeyName(): string 
    {
        return 'slug';
    }

    // связи
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(SpectacleImage::class)->orderBy('sort_order');
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    // скоупы
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // хелперы
    // ближайшие показы для главной и детальной
    public function upcomingPerformances()
    {
        return $this->performances()
            ->where('starts_at', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('starts_at');
    }

    // ближайший показ для карточки на главной
    public function nextPerformance()
    {
        return $this->upcomingPerformances()->first();
    }

    // средний рейтинг
    public function averageRating(): float 
    {
        return round($this->reviews()->where('is_published', true)->avg('rating') ?? 0, 1);
    }

    // полная длительность с антрактом
    public function fullDurationAttribute(): int
    {
        return $this->duration_minutes + $this->intermission_minutes;
    }
}
