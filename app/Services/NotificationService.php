<?php

namespace App\Services;

use App\Models\Invitation;
use App\Jobs\SendInvitationEmail;
use App\Jobs\SendInvitationSMS;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Send an invitation via Email and SMS using background jobs.
     */
    public function sendInvitation(Invitation $invitation, string $registrationLink)
    {
        // 1. Dispatch Email Job
        if ($invitation->email) {
            SendInvitationEmail::dispatch($invitation, $registrationLink);
        }

        // 2. Dispatch SMS Job
        if ($invitation->phone) {
            SendInvitationSMS::dispatch($invitation, $registrationLink);
        }

        // 3. Update last sent timestamp
        $invitation->update([
            'last_sent_at' => Carbon::now()
        ]);
    }
}
