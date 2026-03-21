<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->unsignedBigInteger('resident_id')->after('id');
            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn('resident_id');
        });
    }
};
