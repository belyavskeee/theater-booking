<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpectacleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'spectacle_id',
        'path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    public function spectacle()
    {
        return $this->belongsTo(Spectacle::class);
    }
}
