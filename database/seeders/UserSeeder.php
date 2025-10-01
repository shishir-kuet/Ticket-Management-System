<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Shishir',
            'email' => 'shishirkuet63@gmail.com',
            'password' => Hash::make('Vitruvian09csg1.6textabs'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Agent User
        User::create([
            'name' => 'Akash',
            'email' => 'akash41bt@gmail.com',
            'password' => Hash::make('2107013'),
            'role' => 'agent',
            'email_verified_at' => now(),
        ]);

        // Customer Users
        User::create([
            'name' => 'Mahim',
            'email' => 'arifulalam7865@gmail.com',
            'password' => Hash::make('2107023'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Farzana',
            'email' => 'minkaeasmin04@gmail.com',
            'password' => Hash::make('2107027'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
    }
}
