<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Due;
use App\Models\Payment;
use App\Models\Penalty;
use App\Models\Resident;
use Carbon\Carbon;

class CheckOverdueDues extends Command
{
    protected $signature = 'dues:check-overdue';
    protected $description = 'Automatically add penalties for overdue dues';

    public function handle()
    {
        $today = Carbon::today();

        // Get all dues that are past due
        $overdueDues = Due::where('due_date', '<', $today)->get();

        foreach ($overdueDues as $due) {
            $residents = Resident::all();

            foreach ($residents as $resident) {
                // ✅ Check if already paid
                $payment = Payment::where('resident_id', $resident->id)
                    ->where('due_id', $due->id)
                    ->where('status', 'approved')
                    ->first();

                if ($payment) {
                    continue;
                }

                // ✅ Check if penalty already exists
                $existingPenalty = Penalty::where('resident_id', $resident->id)
                    ->where('due_id', $due->id)
                    ->first();

                if (!$existingPenalty) {
                    Penalty::create([
                        'resident_id' => $resident->id,
                        'due_id' => $due->id,
                        'reason' => "Late payment for {$due->title}",
                        'amount' => $due->amount * 0.10, // 10% penalty
                        'date_issued' => $today,
                        'status' => 'unpaid',
                    ]);

                    $this->info("✅ Penalty added for {$resident->name} ({$due->title})");
                }
            }
        }

        $this->info('🎯 Overdue dues check complete.');
    }
}
