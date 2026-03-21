<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Due;
use App\Models\Payment;
use App\Models\Penalty;
use Carbon\Carbon;

class GenerateOverduePenalties extends Command
{
    protected $signature = 'penalties:generate-overdue';
    protected $description = 'Generate overdue penalties for unpaid dues';

    public function handle()
    {
        $today = Carbon::today();

        // Fetch dues where due date has passed
        $dues = Due::whereDate('due_date', '<', $today)->get();

        foreach ($dues as $due) {

            // Skip if the due is fully paid via approved payment
            $approvedPayment = $due->payments()
                                   ->where('status', 'approved')
                                   ->first();
            if ($approvedPayment) {
                continue;
            }

            // Prevent duplicate overdue penalties
            $exists = Penalty::where('due_id', $due->id)
                             ->where('type', 'overdue')
                             ->exists();
            if ($exists) {
                continue;
            }

            // Create overdue penalty
            Penalty::create([
                'resident_id' => $due->resident_id,
                'due_id'      => $due->id,
                'type'        => 'overdue',
                'reason'      => 'Overdue Fee',
                'amount'      => 50, // default overdue fee, you can customize
                'status'      => 'unpaid',
                'date_issued' => now(),
            ]);

            $this->info("Overdue penalty generated for Due ID: {$due->id}");
        }

        $this->info('Overdue penalties generation completed.');
        return Command::SUCCESS;
    }
}
