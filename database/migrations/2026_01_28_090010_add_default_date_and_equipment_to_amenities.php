<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            if (!Schema::hasColumn('amenities', 'default_date')) {
                $table->date('default_date')->nullable()->after('holiday_hours');
            }
            if (!Schema::hasColumn('amenities', 'equipment')) {
                $table->json('equipment')->nullable()->after('max_capacity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            if (Schema::hasColumn('amenities', 'default_date')) {
                $table->dropColumn('default_date');
            }
            if (Schema::hasColumn('amenities', 'equipment')) {
                $table->dropColumn('equipment');
            }
        });
    }
};
