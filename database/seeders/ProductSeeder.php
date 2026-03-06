<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(5)->create([
            'email_verified_at' => now(),
        ]);

        foreach ($users as $user) {
            Product::factory(4)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
