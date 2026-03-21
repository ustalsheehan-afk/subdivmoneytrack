<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => 'password']
        );

        $this->call([
            ResidentSeeder::class,
            DueSeeder::class,
            PaymentSeeder::class,
            AnnouncementSeeder::class,
            PenaltySeeder::class,
        ]);
    }
}
