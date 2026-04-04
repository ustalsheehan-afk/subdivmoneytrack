<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reservation_cancellation_reasons')) {
            return;
        }

        Schema::create('reservation_cancellation_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('scope')->default('both'); // resident, admin, both
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_cancellation_reasons');
    }
};
