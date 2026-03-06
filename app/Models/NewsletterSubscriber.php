<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'confirmed',
        'token',
    ];

    protected function casts(): array
    {
        return [
            'confirmed' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (NewsletterSubscriber $subscriber) {
            $subscriber->token = Str::random(32);
        });
    }
}
