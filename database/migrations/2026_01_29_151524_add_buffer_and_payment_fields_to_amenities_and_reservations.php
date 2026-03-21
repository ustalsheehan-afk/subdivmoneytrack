<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            if (!Schema::hasColumn('amenities', 'buffer_minutes')) {
                $table->integer('buffer_minutes')->default(30)->after('price');
            }
        });

        Schema::table('amenity_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('amenity_reservations', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('status');
            }
            if (!Schema::hasColumn('amenity_reservations', 'total_price')) {
                 $table->decimal('total_price', 10, 2)->default(0)->after('guest_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amenities', function (Blueprint $table) {
             if (Schema::hasColumn('amenities', 'buffer_minutes')) {
                $table->dropColumn(['buffer_minutes']);
             }
        });
        Schema::table('amenity_reservations', function (Blueprint $table) {
             if (Schema::hasColumn('amenity_reservations', 'payment_status')) {
                $table->dropColumn(['payment_status']);
             }
             if (Schema::hasColumn('amenity_reservations', 'total_price')) {
                $table->dropColumn(['total_price']);
             }
        });
    }
};
