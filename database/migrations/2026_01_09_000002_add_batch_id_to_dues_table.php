<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->string('batch_id')->nullable()->after('id')->index();
        });

        DB::statement('UPDATE dues SET batch_id = id WHERE batch_id IS NULL');
    }

    public function down(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->dropIndex(['batch_id']);
            $table->dropColumn('batch_id');
        });
    }
};
