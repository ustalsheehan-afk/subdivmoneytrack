<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to avoid doctrine/dbal dependency
        DB::statement("ALTER TABLE announcements MODIFY category VARCHAR(255)");
    }

    public function down(): void
    {
        // Revert to ENUM
        // DB::statement("ALTER TABLE announcements MODIFY category ENUM('Event', 'Maintenance', 'Meeting', 'Security')");
    }
};
