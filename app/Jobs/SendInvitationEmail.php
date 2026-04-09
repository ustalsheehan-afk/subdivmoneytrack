<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Invitation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendInvitationEmail implements ShouldQueue
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
    public function handle(): array
    {
        $platformName = config('app.name', 'Subdivision Dues System');
        $name = trim(implode(' ', array_filter([
            $this->invitation->first_name,
            $this->invitation->last_name,
        ])));
        $email = $this->invitation->email;
        $link = $this->link;

        $subject = "You're invited to register";
        $greetingName = $name !== '' ? $name : 'there';
        $message = "Hello {$greetingName},\n\nYou have been invited to register for {$platformName}.\n\nClick the link below to complete your registration:\n\n{$link}\n\nThis invitation will expire in 7 days.\n\nIf you did not expect this invitation, please ignore this message.";

        try {
            if (!$email) {
                Log::warning("Skipping invitation email for invitation {$this->invitation->id}: No email address");
                return [
                    'channel' => 'email',
                    'success' => false,
                    'error' => 'No email address provided.',
                ];
            }

            Mail::raw($message, function ($m) use ($email, $subject) {
                $m->to($email)->subject($subject);
            });

            Log::info("Invitation email sent to {$email}", [
                'invitation_id' => $this->invitation->id,
            ]);
            
            $this->invitation->update([
                'email_status' => Invitation::DELIVERY_SENT
            ]);

            return [
                'channel' => 'email',
                'success' => true,
                'error' => null,
            ];
        } catch (Throwable $e) {
            Log::error("Failed to send invitation email to {$email}: " . $e->getMessage());
            
            $this->invitation->update([
                'email_status' => Invitation::DELIVERY_FAILED
            ]);

            return [
                'channel' => 'email',
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
