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
        Schema::table('support_messages', function (Blueprint $table) {
            // Since we can't change column type easily without doctrine/dbal, 
            // and the previous status was an enum ['pending', 'replied'], 
            // we will just add the new column for tracking read status.
            $table->boolean('is_read_by_admin')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn('is_read_by_admin');
        });
    }
};
