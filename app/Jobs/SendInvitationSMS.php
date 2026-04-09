<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Invitation;
use App\Services\SmsService;
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

        // Build SMS message
        $message = "{$platformName} invitation. Register here: {$link} This link expires in 7 days.";

        try {
            // Skip if no phone number
            if (!$phone) {
                Log::warning("Skipping SMS for invitation {$this->invitation->id}: No phone number");
                return;
            }

            // Send SMS via PhilSMS
            $smsService = new SmsService();
            $response = $smsService->send($phone, $message);

            $success = !empty($response['success']) || strtolower($response['status'] ?? '') === 'success';

            if ($success) {
                $this->invitation->update([
                    'sms_status' => Invitation::DELIVERY_SENT
                ]);
                Log::info("SMS sent to {$phone}", ['response' => $response]);
            } else {
                $this->invitation->update([
                    'sms_status' => Invitation::DELIVERY_FAILED
                ]);
                Log::error("SMS delivery failed for {$phone}", [
                    'response' => $response,
                    'invitation_id' => $this->invitation->id,
                ]);
            }
        } catch (Throwable $e) {
            Log::error("Exception sending SMS to {$phone}: " . $e->getMessage());
            
            $this->invitation->update([
                'sms_status' => Invitation::DELIVERY_FAILED
            ]);
            
            throw $e;
        }
    }
}
