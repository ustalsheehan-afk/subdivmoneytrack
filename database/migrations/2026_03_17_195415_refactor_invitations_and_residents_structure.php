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
        // 1. Refactor Invitations Table
        Schema::table('invitations', function (Blueprint $table) {
            // Drop old name column if exists
            if (Schema::hasColumn('invitations', 'name')) {
                $table->dropColumn('name');
            }
            
            // Add first_name and last_name
            if (!Schema::hasColumn('invitations', 'first_name')) {
                $table->string('first_name')->after('id');
            }
            if (!Schema::hasColumn('invitations', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
        });

        // Use raw SQL to change columns to avoid doctrine/dbal dependency and handle MariaDB syntax
        DB::statement('ALTER TABLE invitations MODIFY token VARCHAR(64) UNIQUE');
        DB::statement('ALTER TABLE invitations MODIFY phone VARCHAR(255) NULL');
        DB::statement('ALTER TABLE invitations MODIFY accepted_at TIMESTAMP NULL');

        // 2. Refactor Residents Table
        if (Schema::hasColumn('residents', 'contact') && !Schema::hasColumn('residents', 'contact_number')) {
            DB::statement('ALTER TABLE residents CHANGE contact contact_number VARCHAR(255) NULL');
        }

        Schema::table('residents', function (Blueprint $table) {
            // Ensure first_name and last_name are present (they should be, but let's be safe)
            if (!Schema::hasColumn('residents', 'first_name')) {
                $table->string('first_name')->after('user_id')->nullable();
            }
            if (!Schema::hasColumn('residents', 'last_name')) {
                $table->string('last_name')->after('first_name')->nullable();
            }
            
            // Ensure block and lot are present
            if (!Schema::hasColumn('residents', 'block')) {
                $table->string('block')->after('last_name')->nullable();
            }
            if (!Schema::hasColumn('residents', 'lot')) {
                $table->string('lot')->after('block')->nullable();
            }
            
            // Ensure move_in_date is present
            if (!Schema::hasColumn('residents', 'move_in_date')) {
                $table->date('move_in_date')->after('lot')->nullable();
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
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
            $table->string('name')->nullable()->after('id');
        });

        if (Schema::hasColumn('residents', 'contact_number')) {
            DB::statement('ALTER TABLE residents CHANGE contact_number contact VARCHAR(255) NULL');
        }
    }
};
