<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestModel;
use App\Models\Homeowner;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $homeowners = Homeowner::all();

        foreach ($homeowners as $homeowner) {
            RequestModel::create([
                'homeowner_id' => $homeowner->id,
                'subject' => 'Street Light Repair',
                'message' => 'The street light near my house is not working.',
                'status' => 'pending',
                'date_sent' => now()->subDays(rand(1, 5)),
            ]);
        }
    }
}
