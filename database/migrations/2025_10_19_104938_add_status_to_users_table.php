<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active');
            }

            // Remove the old homeowner_id reference
            if (Schema::hasColumn('users', 'homeowner_id')) {
                $table->dropColumn('homeowner_id');
            }

            // Add a link to residents instead
            if (!Schema::hasColumn('users', 'resident_id')) {
                $table->foreignId('resident_id')->nullable()->constrained('residents')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn('resident_id');
            $table->dropColumn('status');
        });
    }
};
    