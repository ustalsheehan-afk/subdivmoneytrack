<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            if (!Schema::hasColumn('amenities', 'days_available')) {
                $table->json('days_available')->nullable()->after('availability');
            }
            if (!Schema::hasColumn('amenities', 'time_slots')) {
                $table->json('time_slots')->nullable()->after('days_available');
            }
            if (!Schema::hasColumn('amenities', 'gallery')) {
                $table->json('gallery')->nullable()->after('image');
            }
            if (!Schema::hasColumn('amenities', 'highlight')) {
                $table->boolean('highlight')->default(false)->after('status');
            }
            if (!Schema::hasColumn('amenities', 'rules_path')) {
                $table->string('rules_path')->nullable()->after('holiday_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            if (Schema::hasColumn('amenities', 'days_available')) {
                $table->dropColumn('days_available');
            }
            if (Schema::hasColumn('amenities', 'time_slots')) {
                $table->dropColumn('time_slots');
            }
            if (Schema::hasColumn('amenities', 'gallery')) {
                $table->dropColumn('gallery');
            }
            if (Schema::hasColumn('amenities', 'highlight')) {
                $table->dropColumn('highlight');
            }
            if (Schema::hasColumn('amenities', 'rules_path')) {
                $table->dropColumn('rules_path');
            }
        });
    }
};
