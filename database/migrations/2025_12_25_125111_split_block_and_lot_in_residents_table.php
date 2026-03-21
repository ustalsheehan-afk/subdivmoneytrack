<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns if they don't exist
        Schema::table('residents', function (Blueprint $table) {
            if (!Schema::hasColumn('residents', 'block')) {
                $table->unsignedInteger('block')->nullable()->after('address');
            }
            if (!Schema::hasColumn('residents', 'lot')) {
                $table->unsignedInteger('lot')->nullable()->after('block');
            }
        });

        // Only split data if the old column exists
        if (Schema::hasColumn('residents', 'block_and_lot')) {
            $residents = DB::table('residents')->select('id', 'block_and_lot')->get();

            foreach ($residents as $resident) {
                if ($resident->block_and_lot) {
                    [$block, $lot] = explode('-', $resident->block_and_lot) + [null, null];

                    DB::table('residents')
                        ->where('id', $resident->id)
                        ->update([
                            'block' => $block ? (int)$block : null,
                            'lot' => $lot ? (int)$lot : null,
                        ]);
                }
            }

            // Drop old column
            Schema::table('residents', function (Blueprint $table) {
                $table->dropColumn('block_and_lot');
            });
        }
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (!Schema::hasColumn('residents', 'block_and_lot')) {
                $table->string('block_and_lot')->nullable()->after('address');
            }
        });

        if (Schema::hasColumn('residents', 'block') && Schema::hasColumn('residents', 'lot')) {
            $residents = DB::table('residents')->select('id', 'block', 'lot')->get();

            foreach ($residents as $resident) {
                $combined = ($resident->block ?? '') . '-' . ($resident->lot ?? '');
                DB::table('residents')
                    ->where('id', $resident->id)
                    ->update([
                        'block_and_lot' => $combined,
                    ]);
            }

            // Drop new columns
            Schema::table('residents', function (Blueprint $table) {
                $table->dropColumn(['block', 'lot']);
            });
        }
    }
};
