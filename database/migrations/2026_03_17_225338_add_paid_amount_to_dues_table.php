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
        Schema::table('dues', function (Blueprint $table) {
            if (!Schema::hasColumn('dues', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('amount');
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
        Schema::table('dues', function (Blueprint $table) {
            if (Schema::hasColumn('dues', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }
};
