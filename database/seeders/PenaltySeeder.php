<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penalty;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PenaltySeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Penalty::truncate();
        Schema::enableForeignKeyConstraints();

        $residents = Resident::all();

        if ($residents->isEmpty()) {
            return;
        }

        $penalties = [
            [
                'type' => 'late_payment',
                'reason' => 'Late payment for January HOA Dues',
                'amount' => 50.00,
                'days_ago' => 5,
            ],
            [
                'type' => 'violation',
                'reason' => 'Noise complaint (party after curfew)',
                'amount' => 500.00,
                'days_ago' => 12,
            ],
            [
                'type' => 'violation',
                'reason' => 'Improper garbage disposal',
                'amount' => 200.00,
                'days_ago' => 20,
            ],
            [
                'type' => 'damage',
                'reason' => 'Damage to clubhouse property (chair)',
                'amount' => 1500.00,
                'days_ago' => 45,
            ],
            [
                'type' => 'late_payment',
                'reason' => 'Late payment for December HOA Dues',
                'amount' => 50.00,
                'days_ago' => 35,
            ],
            [
                'type' => 'violation',
                'reason' => 'Parking in no-parking zone',
                'amount' => 1000.00,
                'days_ago' => 2,
            ],
            [
                'type' => 'overdue',
                'reason' => 'Unpaid Special Assessment',
                'amount' => 100.00,
                'days_ago' => 60,
            ],
        ];

        // Assign random penalties to random residents
        foreach ($residents as $resident) {
            // 30% chance a resident has a penalty
            if (rand(1, 100) <= 30) {
                $penaltyData = $penalties[array_rand($penalties)];
                
                // Randomize status
                $status = ['paid', 'unpaid', 'pending'][rand(0, 2)];

                Penalty::create([
                    'resident_id' => $resident->id,
                    'amount'      => $penaltyData['amount'],
                    'reason'      => $penaltyData['reason'],
                    'type'        => $penaltyData['type'],
                    'status'      => $status,
                    'date_issued' => Carbon::now()->subDays($penaltyData['days_ago']),
                ]);
            }
        }
        
        // Ensure at least some specific penalties exist for demonstration
        $specificResident = $residents->first();
        if ($specificResident) {
             Penalty::create([
                'resident_id' => $specificResident->id,
                'amount'      => 500.00,
                'reason'      => 'Noise complaint (Manual Entry)',
                'type'        => 'violation',
                'status'      => 'unpaid',
                'date_issued' => Carbon::now()->subDays(1),
            ]);
        }
    }
}
