<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amenity_reservations', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('date');
            $table->time('end_time')->nullable()->after('start_time');
            
            // Add index for efficient overlap checking
            // We include status to quickly filter out rejected/cancelled
            $table->index(['amenity_id', 'date', 'start_time', 'end_time', 'status'], 'amenity_res_overlap_index');
        });

        // Optional: Backfill data if needed (Best effort)
        // This is a rough backfill assuming 'HH:mm - HH:mm' format
        // We will do this in raw SQL for performance if supported, or PHP
        // Since it's a small system likely, PHP is fine, but migration should be robust.
        // Let's just leave columns nullable initially, and maybe update via a seeder or manual script if needed.
        // But for "refactor", we should try to support existing data.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amenity_reservations', function (Blueprint $table) {
            $table->dropIndex('amenity_res_overlap_index');
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
