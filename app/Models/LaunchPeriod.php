<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaunchPeriod extends Model
{
    protected $fillable = [
        'starts_at',
        'ends_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function current(): ?self
    {
        return static::active()->first();
    }
}
