<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('admin');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@launchory.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');
    }
}
