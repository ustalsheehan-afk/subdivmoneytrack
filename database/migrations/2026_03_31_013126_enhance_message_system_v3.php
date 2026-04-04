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
        Schema::table('message_threads', function (Blueprint $table) {
            if (!Schema::hasColumn('message_threads', 'priority')) {
                $table->string('priority')->default('medium')->after('status'); // low, medium, high, urgent
            }
            if (!Schema::hasColumn('message_threads', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->after('priority');
            }
            if (!Schema::hasColumn('message_threads', 'intent')) {
                $table->string('intent')->nullable()->after('category'); // amenity, maintenance, billing, general
            }
            if (!Schema::hasColumn('message_threads', 'metadata')) {
                $table->json('metadata')->nullable()->after('module_id'); // For suggested actions, SLA info, etc.
            }
        });

        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'is_internal')) {
                $table->boolean('is_internal')->default(false)->after('is_read');
            }
            if (!Schema::hasColumn('messages', 'metadata')) {
                $table->json('metadata')->nullable()->after('is_internal'); // For message status (sent, seen), typing, etc.
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_threads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['priority', 'assigned_to', 'intent', 'metadata']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['is_internal', 'metadata']);
        });
    }
};
