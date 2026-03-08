<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedSlot extends Model
{
    protected $fillable = [
        'product_id',
        'slot_type',
        'starts_at',
        'ends_at',
        'amount_paid',
        'stripe_payment_id',
        'polar_order_id',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('starts_at', '<=', now())->where('ends_at', '>=', now());
    }
}
