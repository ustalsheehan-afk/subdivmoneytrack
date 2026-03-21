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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('penalty_id')->nullable()->after('due_id')->constrained('penalties')->nullOnDelete();
        });

        // Use raw SQL to make due_id nullable to avoid doctrine dependency
        DB::statement('ALTER TABLE payments MODIFY due_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['penalty_id']);
            $table->dropColumn('penalty_id');
        });

        DB::statement('ALTER TABLE payments MODIFY due_id BIGINT UNSIGNED NOT NULL');
    }
};
