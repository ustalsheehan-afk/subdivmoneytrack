<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if we need to rename or if it's already done
        if (Schema::hasColumn('payments', 'amount_paid') && !Schema::hasColumn('payments', 'amount')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->decimal('amount', 15, 2)->after('due_id')->nullable();
            });
            
            // Copy data
            DB::table('payments')->update(['amount' => DB::raw('amount_paid')]);
            
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('amount_paid');
            });
        }

        Schema::table('payments', function (Blueprint $table) {
            // Add source field if it doesn't exist
            if (!Schema::hasColumn('payments', 'source')) {
                $table->enum('source', ['admin', 'resident'])->default('admin')->after('amount');
            }

            // Ensure due_id is indexed
            // Wrap in try-catch to avoid "index already exists" errors since we can't use Doctrine
            try {
                $table->index('due_id');
            } catch (\Exception $e) {}
            
            try {
                $table->index('status');
            } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'source')) {
                $table->dropColumn('source');
            }

            if (Schema::hasColumn('payments', 'amount') && !Schema::hasColumn('payments', 'amount_paid')) {
                $table->decimal('amount_paid', 15, 2)->after('due_id')->nullable();
                DB::table('payments')->update(['amount_paid' => DB::raw('amount')]);
                $table->dropColumn('amount');
            }
        });
    }
};
