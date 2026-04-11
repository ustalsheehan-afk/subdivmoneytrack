<?php

namespace App\Console\Commands;

use App\Models\Resident;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneResidentsForDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:prune-residents {--limit=60 : Maximum residents to delete} {--dry-run : Preview only, no deletion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete fake/non-gmail residents for demo cleanup while preserving gmail and admin-linked accounts.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));
        $dryRun = (bool) $this->option('dry-run');

        // Never touch gmail residents; also protect any resident email that exists in admins table.
        $baseQuery = Resident::query()
            ->whereNotNull('email')
            ->whereRaw('LOWER(email) NOT LIKE ?', ['%@gmail.com'])
            ->whereRaw('LOWER(email) NOT IN (SELECT LOWER(email) FROM admins)');

        // Prioritize obvious fake/demo domains first.
        $priorityIds = (clone $baseQuery)
            ->where(function ($q) {
                $q->whereRaw('LOWER(email) LIKE ?', ['%@example.com'])
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%test%'])
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%fake%'])
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%demo%']);
            })
            ->orderByDesc('id')
            ->pluck('id')
            ->all();

        $selected = array_slice($priorityIds, 0, $limit);

        // If prioritized fake accounts are less than requested limit, fill from remaining non-gmail residents.
        if (count($selected) < $limit) {
            $remainingIds = (clone $baseQuery)
                ->whereNotIn('id', $selected)
                ->orderByDesc('id')
                ->pluck('id')
                ->all();

            $selected = array_merge($selected, array_slice($remainingIds, 0, $limit - count($selected)));
        }

        $selected = array_values(array_unique($selected));

        if (empty($selected)) {
            $this->warn('No eligible non-gmail residents found. Nothing deleted.');
            return self::SUCCESS;
        }

        $rows = Resident::query()
            ->whereIn('id', $selected)
            ->orderBy('id')
            ->get(['id', 'first_name', 'last_name', 'email']);

        $this->info('Residents selected: ' . $rows->count());
        foreach ($rows as $resident) {
            $name = trim(($resident->first_name ?? '') . ' ' . ($resident->last_name ?? ''));
            $this->line(sprintf(' - #%d %s <%s>', $resident->id, $name !== '' ? $name : '(no name)', $resident->email ?? 'no-email'));
        }

        if ($dryRun) {
            $this->comment('Dry run mode: no records were deleted.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($selected) {
            Resident::query()->whereIn('id', $selected)->delete();
        });

        $this->info('Deleted residents: ' . count($selected));

        return self::SUCCESS;
    }
}
