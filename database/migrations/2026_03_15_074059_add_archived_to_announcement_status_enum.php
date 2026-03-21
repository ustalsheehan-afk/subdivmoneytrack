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
        // MySQL specific change to add 'archived' to enum
        DB::statement("ALTER TABLE announcements MODIFY COLUMN status ENUM('active', 'trashed', 'archived') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE announcements MODIFY COLUMN status ENUM('active', 'trashed') DEFAULT 'active'");
    }
};
