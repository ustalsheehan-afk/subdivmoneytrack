<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->foreignId('due_id')->nullable()->after('payment_id')->constrained('dues')->onDelete('set null');
            $table->string('status')->default('unpaid')->after('reason'); // paid, unpaid, pending
            $table->string('type')->default('general')->after('status'); // late_payment, violation, damage, overdue, general
        });
    }

    public function down(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->dropForeign(['due_id']);
            $table->dropColumn(['due_id', 'status', 'type']);
        });
    }
};
