<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->json('days_available');  // ✅ Make sure this exists
    $table->json('time_slots');      // ✅ Make sure this exists
    $table->integer('max_capacity');
    $table->decimal('price', 10, 2);
    $table->text('description')->nullable();
    $table->string('image')->nullable();
    $table->string('pdf_rules')->nullable();
    $table->boolean('status')->default(true);
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};