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
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->string('status')->default('pending')->after('role'); // pending, accepted, expired, cancelled
            $table->timestamp('accepted_at')->nullable()->after('expires_at');
        });

        // Use raw SQL to change column to avoid doctrine/dbal dependency
        DB::statement('ALTER TABLE invitations MODIFY resident_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone', 'status', 'accepted_at']);
        });

        DB::statement('ALTER TABLE invitations MODIFY resident_id BIGINT UNSIGNED NOT NULL');
    }
};
