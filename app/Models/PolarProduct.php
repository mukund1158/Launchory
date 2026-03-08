<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolarProduct extends Model
{
    protected $fillable = ['plan_slug', 'polar_product_id'];

    public static function getPolarIdForPlan(string $plan): ?string
    {
        $row = static::where('plan_slug', $plan)->first();

        return $row?->polar_product_id;
    }
}
