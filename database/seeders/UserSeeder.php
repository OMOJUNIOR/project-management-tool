<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminuser'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'User One',
            'email' => 'user1@user.com',
            'password' => Hash::make('testuser'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'User Two',
            'email' => 'user2@user.com',
            'password' => Hash::make('testuser'),
            'is_admin' => false,
        ]);
    }
}
