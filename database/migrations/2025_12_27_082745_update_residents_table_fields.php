<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (!Schema::hasColumn('residents', 'first_name')) {
                $table->string('first_name');
            }
            if (!Schema::hasColumn('residents', 'last_name')) {
                $table->string('last_name');
            }
            if (!Schema::hasColumn('residents', 'email')) {
                $table->string('email')->unique();
            }
            if (!Schema::hasColumn('residents', 'move_out_date')) {
                $table->date('move_out_date')->nullable();
            }
            if (!Schema::hasColumn('residents', 'move_history')) {
                $table->json('move_history')->nullable();
            }
            if (!Schema::hasColumn('residents', 'status')) {
                $table->string('status')->default('active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'email',
                'move_out_date',
                'move_history',
                'status',
            ]);
        });
    }
};
