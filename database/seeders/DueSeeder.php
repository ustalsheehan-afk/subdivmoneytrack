<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\Due;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DueSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        if (Schema::hasTable('payments')) {
            DB::table('payments')->truncate();
        }
        Due::truncate();
        Schema::enableForeignKeyConstraints();

        $residents = Resident::all();

        if ($residents->isEmpty()) {
            return;
        }

        $now = now();
        $currentYear = $now->year;

        // 1. Monthly HOA (Jan - Dec)
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($currentYear, $month, 1);
            $monthName = $monthStart->format('F');
            // Due date is end of the month
            $dueDate = $monthStart->copy()->endOfMonth(); 
            $batchId = (string) Str::uuid();
            
            $rows = [];
            foreach ($residents as $resident) {
                $rows[] = [
                    'batch_id' => $batchId,
                    'resident_id' => $resident->id,
                    'title' => "Monthly HOA - $monthName $currentYear",
                    'description' => "Homeowner Association Dues for $monthName",
                    'amount' => 1500.00, 
                    'type' => Due::TYPE_MONTHLY_HOA,
                    'frequency' => Due::FREQUENCY_MONTHLY,
                    'month' => $monthName,
                    'due_date' => $dueDate->toDateString(),
                    'billing_period_start' => $monthStart->toDateString(),
                    'billing_period_end' => $monthStart->copy()->endOfMonth()->toDateString(),
                    'status' => Due::STATUS_UNPAID,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            foreach (array_chunk($rows, 1000) as $chunk) {
                Due::insert($chunk);
            }
        }

        // 2. Regular Fees (Garbage, Security, Water, Street Lights) - Current Month
        $currentMonthDate = $now->copy()->endOfMonth();
        $this->createBatch($residents, 'Garbage Fee', 200.00, Due::TYPE_REGULAR_FEES, Due::FREQUENCY_MONTHLY, $currentMonthDate);
        $this->createBatch($residents, 'Security Fee', 300.00, Due::TYPE_REGULAR_FEES, Due::FREQUENCY_MONTHLY, $currentMonthDate);
        $this->createBatch($residents, 'Water Bill', 450.00, Due::TYPE_REGULAR_FEES, Due::FREQUENCY_MONTHLY, $currentMonthDate);
        $this->createBatch($residents, 'Street Lights', 100.00, Due::TYPE_REGULAR_FEES, Due::FREQUENCY_MONTHLY, $currentMonthDate);

        // 3. Special Assessments (Road Repair, Event Fund)
        $this->createBatch($residents, 'Road Repair Fund', 1000.00, Due::TYPE_SPECIAL_ASSESSMENTS, Due::FREQUENCY_ONE_TIME, $now->copy()->addMonths(1));
        $this->createBatch($residents, 'Community Event Fund', 250.00, Due::TYPE_SPECIAL_ASSESSMENTS, Due::FREQUENCY_ONE_TIME, $now->copy()->addMonths(2));
    }

    private function createBatch($residents, $title, $amount, $type, $frequency, $dueDate)
    {
        $batchId = (string) Str::uuid();
        $rows = [];
        $now = now();
        $monthName = $dueDate->format('F');

        foreach ($residents as $resident) {
            $rows[] = [
                'batch_id' => $batchId,
                'resident_id' => $resident->id,
                'title' => $title,
                'description' => "$title - $monthName",
                'amount' => $amount,
                'type' => $type,
                'frequency' => $frequency,
                'month' => $monthName,
                'due_date' => $dueDate->toDateString(),
                'billing_period_start' => $dueDate->copy()->startOfMonth()->toDateString(),
                'billing_period_end' => $dueDate->copy()->endOfMonth()->toDateString(),
                'status' => Due::STATUS_UNPAID,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        
        foreach (array_chunk($rows, 1000) as $chunk) {
            Due::insert($chunk);
        }
    }
}
