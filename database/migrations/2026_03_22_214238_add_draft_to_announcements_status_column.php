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
        DB::statement("ALTER TABLE announcements MODIFY COLUMN status ENUM('active', 'trashed', 'archived', 'draft') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE announcements MODIFY COLUMN status ENUM('active', 'trashed', 'archived') DEFAULT 'active'");
    }
};
