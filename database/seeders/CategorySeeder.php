<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'AI & Machine Learning', 'icon' => '🤖', 'sort_order' => 1],
            ['name' => 'Productivity', 'icon' => '⚡', 'sort_order' => 2],
            ['name' => 'Developer Tools', 'icon' => '🛠️', 'sort_order' => 3],
            ['name' => 'Marketing & Sales', 'icon' => '📈', 'sort_order' => 4],
            ['name' => 'SaaS & Tools', 'icon' => '💼', 'sort_order' => 5],
            ['name' => 'Design & Art', 'icon' => '🎨', 'sort_order' => 6],
            ['name' => 'Health & Wellness', 'icon' => '🏃', 'sort_order' => 7],
            ['name' => 'Finance & FinTech', 'icon' => '💰', 'sort_order' => 8],
            ['name' => 'Education & Learning', 'icon' => '📚', 'sort_order' => 9],
            ['name' => 'Social Media', 'icon' => '📱', 'sort_order' => 10],
            ['name' => 'E-commerce', 'icon' => '🛒', 'sort_order' => 11],
            ['name' => 'Startup & Business', 'icon' => '🚀', 'sort_order' => 12],
            ['name' => 'Video & Content', 'icon' => '🎬', 'sort_order' => 13],
            ['name' => 'Community & Networking', 'icon' => '🤝', 'sort_order' => 14],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
