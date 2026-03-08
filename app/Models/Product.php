<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'tagline',
        'description',
        'logo',
        'website_url',
        'category_id',
        'listing_type',
        'status',
        'is_featured',
        'featured_until',
        'launch_date',
        'vote_count',
        'is_do_follow',
        'twitter_handle',
        'maker_comment',
        'pricing',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_do_follow' => 'boolean',
            'featured_until' => 'date',
            'launch_date' => 'date',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function badge(): HasOne
    {
        return $this->hasOne(Badge::class);
    }

    public function featuredSlots(): HasMany
    {
        return $this->hasMany(FeaturedSlot::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeLaunches($query)
    {
        return $query->whereIn('listing_type', ['launch', 'both']);
    }

    public function scopeDirectory($query)
    {
        return $query->whereIn('listing_type', ['directory', 'both']);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('featured_until', '>=', now()->toDateString());
    }

    /**
     * Scope to products live for the current launch day.
     * Launch day starts at configurable hour (default 12:00 PM) in app timezone.
     */
    public function scopeToday($query)
    {
        return $query->where('launch_date', self::currentLaunchDate());
    }

    /**
     * Current launch date: today if we're at or past noon (in app timezone), else yesterday.
     */
    public static function currentLaunchDate(): string
    {
        $now = Carbon::now(config('app.timezone'));
        $noonToday = $now->copy()->startOfDay()->setTime(config('app.launch_hour', 12), 0, 0);

        return $now->gte($noonToday) ? $now->toDateString() : $now->copy()->subDay()->toDateString();
    }

    public function scopeByVotes($query)
    {
        return $query->orderBy('vote_count', 'desc');
    }

    public function hasVotedBy(int $userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }

        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=f59e0b&color=fff&size=128";
    }
}
