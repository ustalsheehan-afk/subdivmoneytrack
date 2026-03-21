<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Due;
use Illuminate\Support\Facades\Schema;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        Schema::disableForeignKeyConstraints();
        Payment::truncate();
        Schema::enableForeignKeyConstraints();

        $residents = Resident::with('dues')->get();

        foreach ($residents as $resident) {
            // Get 2 random dues assigned to this resident if available
            if ($resident->dues->count() >= 2) {
                $randomDues = $resident->dues->random(2);
                $approvedDue = $randomDues[0];
                $pendingDue = $randomDues[1];

                // 1. Create APPROVED Payment
                Payment::create([
                    'resident_id'    => $resident->id,
                    'due_id'         => $approvedDue->id,
                    'amount'         => $approvedDue->amount,
                    'date_paid'      => now()->subDays(rand(5, 30)), // Paid a while ago
                    'status'         => Payment::STATUS_APPROVED,
                    'payment_method' => Payment::METHOD_GCASH,
                ]);

                // Mark due as paid
                $approvedDue->update(['status' => 'paid']);


                // 2. Create PENDING Payment
                Payment::create([
                    'resident_id'    => $resident->id,
                    'due_id'         => $pendingDue->id,
                    'amount'         => $pendingDue->amount,
                    'date_paid'      => now()->subHours(rand(1, 24)), // Paid recently (today/yesterday)
                    'status'         => Payment::STATUS_PENDING,
                    'payment_method' => Payment::METHOD_BANK_TRANSFER,
                ]);
                
                // Pending payments do NOT update the due status yet
            } elseif ($resident->dues->count() == 1) {
                 // Fallback if only 1 due exists (unlikely given our DueSeeder, but safe)
                 $due = $resident->dues->first();
                 Payment::create([
                    'resident_id'    => $resident->id,
                    'due_id'         => $due->id,
                    'amount'         => $due->amount,
                    'date_paid'      => now(),
                    'status'         => Payment::STATUS_PENDING,
                    'payment_method' => Payment::METHOD_GCASH,
                ]);
            }
        }
    }
}
