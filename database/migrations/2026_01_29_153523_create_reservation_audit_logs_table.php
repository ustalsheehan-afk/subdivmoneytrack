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
    public function up()
    {
        Schema::create('reservation_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_reservation_id')->constrained('amenity_reservations')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin who performed action
            $table->string('action'); // approved, rejected, rescheduled
            $table->text('details')->nullable(); // JSON or text description of changes
            $table->string('previous_status')->nullable();
            $table->string('new_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_audit_logs');
    }
};
