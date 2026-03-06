<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $listingType = fake()->randomElement(['launch', 'directory', 'both']);

        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->catchPhrase(),
            'tagline' => fake()->sentence(8),
            'description' => fake()->paragraphs(3, true),
            'website_url' => fake()->url(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'listing_type' => $listingType,
            'status' => 'approved',
            'is_featured' => fake()->boolean(15),
            'featured_until' => fake()->boolean(15) ? now()->addDays(30) : null,
            'launch_date' => $listingType !== 'directory' ? now()->subDays(fake()->numberBetween(0, 30)) : null,
            'vote_count' => fake()->numberBetween(0, 150),
            'pricing' => fake()->randomElement(['free', 'freemium', 'paid']),
            'twitter_handle' => '@' . fake()->userName(),
            'maker_comment' => fake()->boolean(60) ? fake()->sentence(12) : null,
        ];
    }
}
