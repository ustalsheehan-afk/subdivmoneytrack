<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('amenity_reservations', function (Blueprint $table) {
            if (Schema::hasColumn('amenity_reservations', 'resident_id')) {
                $table->dropForeign(['resident_id']);
            }
        });

        DB::statement('ALTER TABLE amenity_reservations MODIFY resident_id BIGINT UNSIGNED NULL');

        Schema::table('amenity_reservations', function (Blueprint $table) {
            $table->foreign('resident_id')->references('id')->on('users')->nullOnDelete();

            if (!Schema::hasColumn('amenity_reservations', 'customer_type')) {
                $table->string('customer_type')->default('resident')->after('resident_id');
            }

            if (!Schema::hasColumn('amenity_reservations', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('customer_type');
            }

            if (!Schema::hasColumn('amenity_reservations', 'guest_contact')) {
                $table->string('guest_contact')->nullable()->after('guest_name');
            }

            if (!Schema::hasColumn('amenity_reservations', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('guest_contact');
            }

            if (!Schema::hasColumn('amenity_reservations', 'booking_source')) {
                $table->string('booking_source')->default('resident_portal')->after('guest_email');
            }

            if (!Schema::hasColumn('amenity_reservations', 'created_by_admin_id')) {
                $table->foreignId('created_by_admin_id')->nullable()->after('booking_source')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('amenity_reservations', function (Blueprint $table) {
            if (Schema::hasColumn('amenity_reservations', 'created_by_admin_id')) {
                $table->dropForeign(['created_by_admin_id']);
                $table->dropColumn('created_by_admin_id');
            }

            $table->dropColumn([
                'customer_type',
                'guest_name',
                'guest_contact',
                'guest_email',
                'booking_source',
            ]);

            $table->dropForeign(['resident_id']);
        });

        DB::statement('ALTER TABLE amenity_reservations MODIFY resident_id BIGINT UNSIGNED NOT NULL');

        Schema::table('amenity_reservations', function (Blueprint $table) {
            $table->foreign('resident_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
