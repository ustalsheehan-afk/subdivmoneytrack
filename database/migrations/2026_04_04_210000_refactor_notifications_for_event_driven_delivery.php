<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('admin_id');
            $table->string('role', 20)->nullable()->after('user_id');
            $table->string('category')->nullable()->after('type');
            $table->string('entity_type')->nullable()->after('category');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            $table->string('deduplication_key')->nullable()->after('entity_id');

            $table->index(['user_id', 'role']);
            $table->index(['role', 'type', 'entity_type', 'entity_id'], 'notifications_event_lookup_idx');
            $table->index('deduplication_key');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'role']);
            $table->dropIndex('notifications_event_lookup_idx');
            $table->dropIndex(['deduplication_key']);
            $table->dropColumn([
                'user_id',
                'role',
                'category',
                'entity_type',
                'entity_id',
                'deduplication_key',
            ]);
        });
    }
};
