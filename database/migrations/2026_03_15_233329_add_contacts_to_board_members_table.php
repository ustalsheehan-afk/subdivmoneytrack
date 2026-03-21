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
    public function up(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            $table->string('email')->nullable()->after('bio');
            $table->string('phone')->nullable()->after('email');
            $table->string('facebook')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'facebook']);
        });
    }
};
