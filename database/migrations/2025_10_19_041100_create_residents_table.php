<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            // 🔗 Relation to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 🏠 Resident details
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            $table->string('block_lot')->nullable();
            $table->date('move_in_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
