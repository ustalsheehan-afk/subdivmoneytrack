<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an Admin User
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'active' => true,
                'must_change_password' => false,
                'lot_unit' => 'Admin Office',
            ]
        );

        $this->command->info('Admin user created successfully.');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password');
    }
}
