<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SupportMessage;
use App\Models\MessageThread;
use App\Models\Message;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Get all SupportMessages
        $supportMessages = DB::table('support_messages')->get();

        foreach ($supportMessages as $oldMsg) {
            // 2. Create a MessageThread for each SupportMessage
            $threadId = DB::table('message_threads')->insertGetId([
                'resident_id' => $oldMsg->resident_id,
                'subject' => $oldMsg->category . ' Inquiry',
                'category' => $oldMsg->category,
                'status' => $oldMsg->status,
                'last_message_at' => $oldMsg->replied_at ?? $oldMsg->created_at,
                'created_at' => $oldMsg->created_at,
                'updated_at' => $oldMsg->updated_at,
            ]);

            // 3. Create the initial message from the resident
            DB::table('messages')->insert([
                'message_thread_id' => $threadId,
                'sender_type' => Resident::class,
                'sender_id' => $oldMsg->resident_id,
                'body' => $oldMsg->message,
                'attachment' => $oldMsg->resident_attachment,
                'is_read' => true, // Old system didn't have per-message unread for residents
                'created_at' => $oldMsg->created_at,
                'updated_at' => $oldMsg->created_at,
            ]);

            // 4. Create the reply from admin if it exists
            if ($oldMsg->admin_reply) {
                DB::table('messages')->insert([
                    'message_thread_id' => $threadId,
                    'sender_type' => User::class,
                    'sender_id' => $oldMsg->replied_by ?? 1, // Fallback to first user if null
                    'body' => $oldMsg->admin_reply,
                    'attachment' => $oldMsg->admin_attachment,
                    'is_read' => true,
                    'created_at' => $oldMsg->replied_at ?? $oldMsg->created_at,
                    'updated_at' => $oldMsg->replied_at ?? $oldMsg->created_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No easy way to reverse this without clearing the message tables
    }
};
