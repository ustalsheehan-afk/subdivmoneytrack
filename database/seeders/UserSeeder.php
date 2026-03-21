<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Resident users
        for ($i = 1; $i <= 5; $i++) {
            User::query()->updateOrCreate(
                ['email' => "resident$i@example.com"],
                [
                    'name' => "Resident $i",
                    'password' => Hash::make('password'),
                    'role' => 'resident',
                ]
            );
        }
    }
}
