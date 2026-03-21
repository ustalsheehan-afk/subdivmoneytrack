<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\Due;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;

class LiveDemoSeeder extends Seeder
{
    public function run()
    {
        $today = Carbon::parse('2026-01-09'); // Specific date as per environment
        
        // 1. Ensure Residents Exist
        $residents = Resident::all();
        if ($residents->isEmpty()) {
            $this->command->info('No residents found. Creating dummy residents...');
            User::factory()->count(10)->create()->each(function ($user) {
                Resident::create([
                    'user_id' => $user->id,
                    'contact_number' => '09123456789',
                    'address' => 'Block 1 Lot ' . rand(1, 50),
                    'status' => 'active'
                ]);
            });
            $residents = Resident::all();
        }

        // 2. Ensure Dues Exist for Jan 2026 and Dec 2025
        $months = [
            $today->copy()->startOfMonth(), // Jan 2026
            $today->copy()->subMonth()->startOfMonth() // Dec 2025
        ];

        foreach ($months as $monthDate) {
            $monthName = $monthDate->format('F');
            $year = $monthDate->year;
            $title = "Monthly HOA - $monthName $year";

            // Check if dues exist for this month (loose check by title)
            $exists = Due::where('title', $title)->exists();

            if (!$exists) {
                $this->command->info("Creating dues for $monthName $year...");
                $batchId = (string) Str::uuid();
                foreach ($residents as $resident) {
                    Due::create([
                        'batch_id' => $batchId,
                        'resident_id' => $resident->id,
                        'title' => $title,
                        'description' => "Homeowner Association Dues for $monthName",
                        'amount' => 1500.00,
                        'type' => 'monthly_hoa',
                        'frequency' => 'monthly',
                        'month' => $monthName,
                        'due_date' => $monthDate->copy()->endOfMonth(),
                        'billing_period_start' => $monthDate->copy()->startOfMonth(),
                        'billing_period_end' => $monthDate->copy()->endOfMonth(),
                        'status' => 'unpaid',
                    ]);
                }
            }
        }

        // 3. Create Payments for TODAY (2026-01-09)
        $this->command->info("Creating payments for TODAY ({$today->toDateString()})...");
        $janDues = Due::where('title', 'like', 'Monthly HOA - January 2026%')->take(10)->get();
        
        foreach ($janDues as $due) {
            // Check if already paid
            if ($due->status === 'paid') continue;

            Payment::create([
                'resident_id' => $due->resident_id,
                'due_id' => $due->id,
                'amount' => $due->amount,
                'date_paid' => $today->copy()->setTime(rand(8, 17), rand(0, 59)), // Business hours today
                'status' => 'approved',
                'payment_method' => 'gcash',
                'proof' => 'proof_demo.jpg',
            ]);

            $due->update(['status' => 'paid']);
        }

        // 4. Create Payments for Earlier This Month (Jan 2-8, 2026)
        $this->command->info("Creating payments for earlier this month...");
        $janDuesEarlier = Due::where('title', 'like', 'Monthly HOA - January 2026%')
                             ->where('status', 'unpaid')
                             ->take(10)
                             ->get();

        foreach ($janDuesEarlier as $due) {
            Payment::create([
                'resident_id' => $due->resident_id,
                'due_id' => $due->id,
                'amount' => $due->amount,
                'date_paid' => $today->copy()->subDays(rand(2, 7))->setTime(rand(8, 17), rand(0, 59)),
                'status' => 'approved',
                'payment_method' => 'cash',
            ]);
            $due->update(['status' => 'paid']);
        }

        // 5. Create Payments for Last Month (Dec 2025)
        $this->command->info("Creating payments for last month...");
        $decDues = Due::where('title', 'like', 'Monthly HOA - December 2025%')
                      ->take(15)
                      ->get();

        foreach ($decDues as $due) {
             if ($due->status === 'paid') continue;

            Payment::create([
                'resident_id' => $due->resident_id,
                'due_id' => $due->id,
                'amount' => $due->amount,
                'date_paid' => $today->copy()->subMonth()->addDays(rand(0, 20))->setTime(rand(8, 17), rand(0, 59)),
                'status' => 'approved',
                'payment_method' => 'bank transfer',
            ]);
            $due->update(['status' => 'paid']);
        }

        $this->command->info('Live Demo Data Seeded Successfully!');
    }
}
