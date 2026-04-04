<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('amenity_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('amenity_reservations', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('verified_by');
            }
            if (!Schema::hasColumn('amenity_reservations', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');
                $table->foreign('cancelled_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('amenity_reservations', 'cancellation_reason')) {
                $table->string('cancellation_reason')->nullable()->after('cancelled_by');
            }
            if (!Schema::hasColumn('amenity_reservations', 'cancellation_type')) {
                $table->string('cancellation_type')->nullable()->after('cancellation_reason'); // user_cancelled, admin_cancelled
            }
        });
    }

    public function down(): void
    {
        Schema::table('amenity_reservations', function (Blueprint $table) {
            if (Schema::hasColumn('amenity_reservations', 'cancellation_type')) {
                $table->dropColumn('cancellation_type');
            }
            if (Schema::hasColumn('amenity_reservations', 'cancellation_reason')) {
                $table->dropColumn('cancellation_reason');
            }
            if (Schema::hasColumn('amenity_reservations', 'cancelled_by')) {
                $table->dropForeign(['cancelled_by']);
                $table->dropColumn('cancelled_by');
            }
            if (Schema::hasColumn('amenity_reservations', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
        });
    }
};
