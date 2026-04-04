<?php

namespace App\Console\Commands;

use App\Models\Due;
use App\Models\Penalty;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GeneratePenalties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penalties:generate-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate penalties for overdue dues';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting penalty generation...');

        $count = 0;

        Due::where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->whereDoesntHave('penalties')
            ->with('resident')
            ->chunk(100, function ($overdueDues) use (&$count) {
                foreach ($overdueDues as $due) {
                    try {
                        $penaltyAmount = $this->calculatePenalty($due);

                        Penalty::create([
                            'resident_id' => $due->resident_id,
                            'due_id' => $due->id,
                            'amount' => $penaltyAmount,
                            'reason' => 'Overdue payment - Auto-generated',
                            'type' => 'overdue',
                            'status' => Penalty::STATUS_PENDING,
                            'date_issued' => now(),
                            'due_date' => $due->due_date,
                        ]);

                        // Create notification for resident
                        Notification::create([
                            'resident_id' => $due->resident_id,
                            'title' => 'Penalty Applied',
                            'message' => "A penalty of ₱" . number_format($penaltyAmount, 2) . " has been applied for overdue payment: {$due->title}",
                            'type' => 'penalty',
                            'link' => '/resident/penalties',
                            'is_read' => false,
                        ]);

                        $count++;
                        Log::info('Penalty generated', [
                            'due_id' => $due->id,
                            'resident_id' => $due->resident_id,
                            'amount' => $penaltyAmount
                        ]);

                    } catch (\Exception $e) {
                        Log::error('Failed to generate penalty', [
                            'due_id' => $due->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            });

        $this->info("Generated {$count} penalties.");
        Log::info("Penalty generation completed: {$count} penalties created");

        return Command::SUCCESS;
    }

    /**
     * Calculate penalty amount
     */
    private function calculatePenalty($due)
    {
        // Option: 5% of due amount
        return $due->amount * 0.05;
    }
}
