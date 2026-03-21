<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceRequest;
use App\Models\Resident;
use Illuminate\Support\Carbon;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $residents = Resident::all();

        if ($residents->isEmpty()) {
            $this->command->warn('No residents found. Please seed residents first.');
            return;
        }

        $types = ['Maintenance', 'Security', 'Complaint', 'Inquiry', 'Other'];
        $priorities = ['low', 'medium', 'high'];
        $statuses = ['pending', 'in progress', 'completed'];
        
        $descriptions = [
            'Street light flickering near Block 5.',
            'Garbage collection missed this week.',
            'Noise complaint regarding neighbors late at night.',
            'Request for gate pass for visitors.',
            'Pothole reported on the main road.',
            'Water pressure is low in the morning.',
            'Stray dog spotted roaming around the park.',
            'Tree branches touching power lines.',
            'Clubhouse reservation inquiry for next weekend.',
            'Broken swing in the playground.'
        ];

        foreach ($residents as $resident) {
            // Create 1-3 requests per resident
            $numRequests = rand(1, 3);

            for ($i = 0; $i < $numRequests; $i++) {
                $date = Carbon::now()->subDays(rand(0, 30));
                
                ServiceRequest::create([
                    'resident_id' => $resident->id,
                    'type' => $types[array_rand($types)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'priority' => $priorities[array_rand($priorities)],
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
        
        $this->command->info('Service Requests seeded successfully.');
    }
}
