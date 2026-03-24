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
        Schema::create('message_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->string('category')->default('general'); // general, payment, complaint, reservation, service_request
            $table->string('status')->default('pending'); // pending, in_progress, replied, closed
            
            // Cross-module polymorphic link
            $table->string('module_type')->nullable(); // App\Models\Due, App\Models\Payment, etc.
            $table->unsignedBigInteger('module_id')->nullable();
            
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->index(['module_type', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_threads');
    }
};
