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
        Schema::table('service_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('amenity_id')->nullable()->after('resident_id');
            $table->date('reservation_date')->nullable()->after('type');
            $table->string('reservation_time')->nullable()->after('reservation_date');
            $table->integer('guest_count')->nullable()->after('reservation_time');
            $table->json('equipment')->nullable()->after('guest_count');

            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['amenity_id']);
            $table->dropColumn(['amenity_id', 'reservation_date', 'reservation_time', 'guest_count', 'equipment']);
        });
    }
};
