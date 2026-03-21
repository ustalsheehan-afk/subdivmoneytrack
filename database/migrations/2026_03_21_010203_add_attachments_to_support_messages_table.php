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
        Schema::table('support_messages', function (Blueprint $table) {
            $table->string('resident_attachment')->nullable()->after('message');
            $table->string('admin_attachment')->nullable()->after('admin_reply');
        });
    }

    public function down()
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn(['resident_attachment', 'admin_attachment']);
        });
    }
};
