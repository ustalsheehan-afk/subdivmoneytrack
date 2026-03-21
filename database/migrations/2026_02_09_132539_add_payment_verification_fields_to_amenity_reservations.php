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
        Schema::table('amenity_reservations', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('payment_method');
            $table->string('payment_reference_no')->nullable()->after('payment_proof');
            $table->text('rejection_reason')->nullable()->after('payment_reference_no');
            $table->timestamp('verified_at')->nullable()->after('rejection_reason');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amenity_reservations', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'payment_proof', 
                'payment_reference_no', 
                'rejection_reason', 
                'verified_at', 
                'verified_by'
            ]);
        });
    }
};
