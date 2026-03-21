<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->date('billing_period_start')->nullable()->after('due_date');
            $table->date('billing_period_end')->nullable()->after('billing_period_start');
            $table->timestamp('archived_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->dropColumn(['billing_period_start', 'billing_period_end', 'archived_at']);
        });
    }
};
