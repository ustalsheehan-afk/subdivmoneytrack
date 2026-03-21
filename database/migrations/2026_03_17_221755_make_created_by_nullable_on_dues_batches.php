<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First drop the foreign key to avoid issues during column change
        Schema::table('dues_batches', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });

        // Use raw SQL to ensure nullability works across environments
        DB::statement('ALTER TABLE dues_batches MODIFY COLUMN created_by BIGINT UNSIGNED NULL');

        // Re-add the foreign key
        Schema::table('dues_batches', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down()
    {
        // No need to reverse for stability
    }
};
