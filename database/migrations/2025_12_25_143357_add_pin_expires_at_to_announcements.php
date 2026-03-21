<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
{
    Schema::table('announcements', function (Blueprint $table) {

        if (!Schema::hasColumn('announcements', 'category')) {
            $table->string('category')->after('title');
        }

        if (!Schema::hasColumn('announcements', 'pin_expires_at')) {
            $table->date('pin_expires_at')->nullable()->after('is_pinned');
        }
    });
}


    public function down(): void
{
    Schema::table('announcements', function (Blueprint $table) {

        if (Schema::hasColumn('announcements', 'category')) {
            $table->dropColumn('category');
        }

        if (Schema::hasColumn('announcements', 'pin_expires_at')) {
            $table->dropColumn('pin_expires_at');
        }
    });
}

};
