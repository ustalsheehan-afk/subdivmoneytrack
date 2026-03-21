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
        Schema::table('residents', function (Blueprint $table) {
            $table->string('membership_type')->nullable()->after('status');
            $table->string('property_type')->nullable()->after('membership_type');
            $table->decimal('lot_area', 10, 2)->nullable()->after('property_type');
            $table->decimal('floor_area', 10, 2)->nullable()->after('lot_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['membership_type', 'property_type', 'lot_area', 'floor_area']);
        });
    }
};
