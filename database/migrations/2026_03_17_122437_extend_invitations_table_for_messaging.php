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
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('email_status')->default('pending')->after('status');
            $table->string('sms_status')->default('pending')->after('email_status');
            $table->timestamp('last_sent_at')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn(['email_status', 'sms_status', 'last_sent_at']);
        });
    }
};
