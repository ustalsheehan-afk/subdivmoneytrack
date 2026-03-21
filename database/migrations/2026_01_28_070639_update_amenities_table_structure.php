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
        Schema::table('amenities', function (Blueprint $table) {
            if (!Schema::hasColumn('amenities', 'availability')) {
                $table->json('availability')->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('amenities', 'booking_rules')) {
                $table->json('booking_rules')->nullable()->after('availability');
            }
            if (!Schema::hasColumn('amenities', 'max_capacity')) {
                $table->integer('max_capacity')->nullable()->after('capacity');
            }
        });

        // Convert enums to string to allow more values (MySQL specific)
        try {
            DB::statement("ALTER TABLE amenities MODIFY COLUMN slot_type VARCHAR(50) DEFAULT 'single'");
            DB::statement("ALTER TABLE amenities MODIFY COLUMN status VARCHAR(50) DEFAULT 'active'");
        } catch (\Exception $e) {
            // Fallback for other databases or if strictly handled (e.g. SQLite)
            // In SQLite, we can't easily modify columns, but we assume MySQL for this environment.
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropColumn(['availability', 'booking_rules', 'max_capacity']);
        });
        
        // Reverting enum changes is complex without raw SQL and knowing previous state perfectly, 
        // so we skip strictly reverting the type change to avoid data loss if new values were added.
    }
};
