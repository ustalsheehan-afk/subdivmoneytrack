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
            // Only add columns if they don't exist
            if (!Schema::hasColumn('residents', 'name')) {
                $table->string('name')->nullable();
            }

            if (!Schema::hasColumn('residents', 'email')) {
                $table->string('email')->nullable();
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
        Schema::table('residents', function (Blueprint $table) {
            if (Schema::hasColumn('residents', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('residents', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
