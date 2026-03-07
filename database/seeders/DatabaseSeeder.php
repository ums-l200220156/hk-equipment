<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin HK Equipment',
            'email' => 'hkadmin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Customer
        User::create([
            'name' => 'Customer HK',
            'email' => 'customer@hk.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}