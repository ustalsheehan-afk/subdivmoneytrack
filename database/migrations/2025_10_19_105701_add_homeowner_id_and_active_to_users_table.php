<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove homeowner_id column if it exists
            if (Schema::hasColumn('users', 'homeowner_id')) {
                $table->dropForeign(['homeowner_id']);
                $table->dropColumn('homeowner_id');
            }

            // Only add resident_id if it doesn't exist yet
            if (!Schema::hasColumn('users', 'resident_id')) {
                $table->foreignId('resident_id')->nullable()->constrained('residents')->onDelete('cascade');
            }

            // Only add active if it doesn't exist yet
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'resident_id')) {
                $table->dropForeign(['resident_id']);
                $table->dropColumn('resident_id');
            }

            if (Schema::hasColumn('users', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
