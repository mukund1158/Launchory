<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Badge extends Model
{
    protected $fillable = [
        'product_id',
        'rank',
        'launch_date',
    ];

    protected function casts(): array
    {
        return [
            'launch_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getBadgeEmojiAttribute(): string
    {
        return match ($this->rank) {
            'gold' => '🥇',
            'silver' => '🥈',
            'bronze' => '🥉',
            default => '',
        };
    }

    public function getBadgeColorAttribute(): string
    {
        return match ($this->rank) {
            'gold' => '#f59e0b',
            'silver' => '#9ca3af',
            'bronze' => '#b45309',
            default => '#000000',
        };
    }
}
