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
    public function handle()
    {
        $platformName = config('app.name', 'Subdivision Dues System');
        $name = $this->invitation->name;
        $email = $this->invitation->email;
        $link = $this->link;

        $subject = "You're invited to register";
        $message = "Hello {$name},\n\nYou have been invited to register for {$platformName}.\n\nClick the link below to complete your registration:\n\n{$link}\n\nThis invitation will expire in 7 days.\n\nIf you did not expect this invitation, please ignore this message.";

        try {
            // Actual mail sending would go here:
            // Mail::raw($message, function($m) use ($email, $subject) {
            //     $m->to($email)->subject($subject);
            // });

            Log::info("Email sent to {$email}: [{$subject}] {$message}");
            
            $this->invitation->update([
                'email_status' => Invitation::DELIVERY_SENT
            ]);
        } catch (Throwable $e) {
            Log::error("Failed to send invitation email to {$email}: " . $e->getMessage());
            
            $this->invitation->update([
                'email_status' => Invitation::DELIVERY_FAILED
            ]);
            
            throw $e;
        }
    }
}
