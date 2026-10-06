<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'spectacle_id',
        'rating',
        'text',
        'is_published'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
    ];

    // связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spectacle()
    {
        return $this->belongsTo(Spectacle::class);
    }

    public function votes()
    {
        return $this->hasMany(ReviewVote::class);
    }

    // хелперы
    public function likesCount(): int
    {
        return $this->votes()->where('is_like', true)->count();
    }

    public function dislikesCount(): int 
    {
        return $this->votes()->where('is_like', false)->count();
    }

    // голос текущ. пользователя true=лайк, false=дизлайк, null=не голосовал
    public function userVote(?int $userId): ?bool 
    {
        if (!$userId) {
            return null;
        }

        $vote = $this->votes()->where('user_id', $userId)->first();

        return $vote?->is_like;
    }

    // скоуп
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
