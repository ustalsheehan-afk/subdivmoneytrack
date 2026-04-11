<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrepareDemoReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:prepare-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset transactional data for final defense demo while preserving admins, configs, templates, and reference data.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $groups = [
            'messages' => ['messages', 'message_threads'],
            'notifications' => ['notifications'],
            'payments' => ['penalties', 'payments'],
            'reservations' => ['reservation_audit_logs', 'amenity_reservations'],
            'service_requests' => ['service_requests'],
            'invitations' => ['invitations'],
        ];

        $existingTables = [];
        foreach ($groups as $tables) {
            foreach ($tables as $table) {
                if (Schema::hasTable($table) && !in_array($table, $existingTables, true)) {
                    $existingTables[] = $table;
                }
            }
        }

        if (empty($existingTables)) {
            $this->warn('No target tables found. Nothing to reset.');
            return self::SUCCESS;
        }

        $this->info('Preparing demo reset for transactional tables only...');

        $beforeCounts = [];
        foreach ($existingTables as $table) {
            $beforeCounts[$table] = DB::table($table)->count();
        }

        $driver = DB::getDriverName();

        try {
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                foreach ($existingTables as $table) {
                    DB::table($table)->truncate();
                }
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } elseif ($driver === 'pgsql') {
                $quoted = array_map(static fn ($table) => '"' . $table . '"', $existingTables);
                DB::statement('TRUNCATE TABLE ' . implode(', ', $quoted) . ' RESTART IDENTITY CASCADE');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
                foreach ($existingTables as $table) {
                    DB::table($table)->delete();
                }
                DB::statement('DELETE FROM sqlite_sequence WHERE name IN (\'' . implode('\',\'', $existingTables) . '\')');
                DB::statement('PRAGMA foreign_keys = ON');
            } else {
                foreach ($existingTables as $table) {
                    DB::table($table)->truncate();
                }
            }
        } catch (\Throwable $e) {
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }

            if ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }

            $this->error('Demo reset failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Reset completed. Summary:');
        foreach ($existingTables as $table) {
            $after = DB::table($table)->count();
            $this->line(sprintf(' - %s: %d -> %d', $table, $beforeCounts[$table], $after));
        }

        $this->newLine();
        $this->line('Preserved data includes admins, users/residents, roles/permissions, system configs, categories, and templates.');

        return self::SUCCESS;
    }
}
