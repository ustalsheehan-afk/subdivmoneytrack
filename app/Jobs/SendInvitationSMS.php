<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Invitation;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendInvitationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invitation;
    protected $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation, string $link)
    {
        $this->invitation = $invitation;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $platformName = config('app.name', 'Subdivision Dues System');
        $phone = $this->invitation->phone;
        $link = $this->link;

        $message = "{$platformName} invitation. Register here: {$link} This link expires in 7 days.";

        try {
            // Actual SMS sending would go here (e.g. Twilio, Chikka, etc.)
            Log::info("SMS sent to {$phone}: {$message}");
            
            $this->invitation->update([
                'sms_status' => Invitation::DELIVERY_SENT
            ]);
        } catch (Throwable $e) {
            Log::error("Failed to send invitation SMS to {$phone}: " . $e->getMessage());
            
            $this->invitation->update([
                'sms_status' => Invitation::DELIVERY_FAILED
            ]);
            
            throw $e;
        }
    }
}
