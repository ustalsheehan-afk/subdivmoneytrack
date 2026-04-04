<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationCancellationReason;

class ReservationCancellationReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            // Resident reasons
            ['label' => 'Change of plans', 'scope' => 'resident', 'active' => true, 'sort_order' => 1],
            ['label' => 'Scheduling conflict', 'scope' => 'resident', 'active' => true, 'sort_order' => 2],
            ['label' => 'Found alternative facility', 'scope' => 'resident', 'active' => true, 'sort_order' => 3],
            ['label' => 'Emergency', 'scope' => 'resident', 'active' => true, 'sort_order' => 4],
            ['label' => 'Incorrect booking', 'scope' => 'resident', 'active' => true, 'sort_order' => 5],
            ['label' => 'Other', 'scope' => 'resident', 'active' => true, 'sort_order' => 6],

            // Admin reasons
            ['label' => 'Policy violation', 'scope' => 'admin', 'active' => true, 'sort_order' => 7],
            ['label' => 'Admin override', 'scope' => 'admin', 'active' => true, 'sort_order' => 8],
            ['label' => 'Duplicate booking', 'scope' => 'admin', 'active' => true, 'sort_order' => 9],
            ['label' => 'Facility maintenance', 'scope' => 'admin', 'active' => true, 'sort_order' => 10],

            // Both
            ['label' => 'Weather conditions', 'scope' => 'both', 'active' => true, 'sort_order' => 11],
            ['label' => 'Health concerns', 'scope' => 'both', 'active' => true, 'sort_order' => 12],
        ];

        foreach ($reasons as $reason) {
            ReservationCancellationReason::updateOrCreate(
                ['label' => $reason['label'], 'scope' => $reason['scope']],
                $reason
            );
        }
    }
}