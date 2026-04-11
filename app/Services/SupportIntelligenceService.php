<?php

namespace App\Services;

use App\Models\MessageThread;
use App\Models\Message;
use Carbon\Carbon;

class SupportIntelligenceService
{
    /**
     * Detect intent and priority based on message content
     */
    public function analyzeThread(MessageThread $thread, string $initialMessage)
    {
        $content = strtolower($initialMessage);
        
        // 1. Detect Intent
        $intent = 'general';
        if ($this->containsAny($content, ['leak', 'broken', 'repair', 'maintenance', 'plumbing', 'electrical', 'ac', 'aircon'])) {
            $intent = 'maintenance';
        } elseif ($this->containsAny($content, ['bill', 'payment', 'due', 'hoa', 'charge', 'penalty', 'receipt'])) {
            $intent = 'billing';
        } elseif ($this->containsAny($content, ['book', 'reserve', 'clubhouse', 'gym', 'pool', 'amenity', 'availability'])) {
            $intent = 'amenity';
        }

        // 2. Detect Priority
        $priority = 'medium';
        if ($this->containsAny($content, ['urgent', 'emergency', 'asap', 'immediately', 'flood', 'fire', 'leak', 'danger'])) {
            $priority = 'high';
            if ($this->containsAny($content, ['flood', 'fire', 'danger', 'major leak'])) {
                $priority = 'urgent';
            }
        } elseif ($this->containsAny($content, ['inquiry', 'question', 'info', 'hours'])) {
            $priority = 'low';
        }

        // 3. Update Thread
        $thread->update([
            'intent' => $intent,
            'priority' => $priority,
            'metadata' => array_merge($thread->metadata ?? [], [
                'analyzed_at' => now(),
                'sla_deadline' => $this->calculateSLADeadline($priority),
            ])
        ]);

        return $thread;
    }

    /**
     * Generate contextual reply suggestions
     */
    public function getSuggestions(MessageThread $thread)
    {
        $intent = $thread->intent ?? strtolower($thread->category) ?? 'general';
        $residentName = $thread->resident->first_name;

        $suggestions = [
            'maintenance' => [
                "Hello $residentName, I've forwarded your maintenance request to our facility team. They will visit your unit shortly.",
                "Can you please provide a photo of the issue so our team can bring the right tools?",
                "Our maintenance schedule is currently full, but we can send someone tomorrow morning. Does that work for you?",
                "The repair has been scheduled for tomorrow. Please ensure someone is available to grant access."
            ],
            'billing' => [
                "Hello $residentName, I'm checking your account records now. One moment please.",
                "You can view your latest statement in the 'Dues' section of the portal. Would you like me to send a direct link?",
                "I've verified your payment. Your status is now updated to 'Good Standing'.",
                "Your payment is currently being processed. It usually takes 24-48 hours to reflect in your account."
            ],
            'amenity' => [
                "Hello $residentName, let me check the availability for those dates. One moment.",
                "The clubhouse is available for your requested time! You can proceed with the reservation in the portal.",
                "I'm sorry, but that time slot is already booked. Would you like to check alternative dates?",
                "Your reservation request has been received and is currently under review by the management."
            ],
            'general' => [
                "Hello $residentName, thank you for reaching out. How can I assist you today?",
                "I've noted your concern and will get back to you as soon as I have an update.",
                "Is there anything else you'd like to know about our community guidelines?",
                "Thank you for your patience. We are currently looking into this and will provide an update shortly."
            ]
        ];

        return $suggestions[$intent] ?? $suggestions['general'];
    }

    /**
     * Get action buttons based on intent
     */
    public function getContextualActions(MessageThread $thread)
    {
        $actions = [
            'maintenance' => [
                ['label' => 'Assign Staff', 'action' => 'assign_staff', 'color' => 'blue'],
                ['label' => 'In Progress', 'action' => 'mark_in_progress', 'color' => 'amber'],
                ['label' => 'Completed', 'action' => 'mark_completed', 'color' => 'emerald'],
            ],
            'billing' => [
                ['label' => 'Send Link', 'action' => 'send_payment_link', 'color' => 'blue'],
                ['label' => 'Mark Paid', 'action' => 'mark_as_paid', 'color' => 'emerald'],
            ],
            'amenity' => [
                ['label' => 'Check Availability', 'action' => 'check_availability', 'color' => 'blue'],
                ['label' => 'Approve', 'action' => 'approve_reservation', 'color' => 'emerald'],
                ['label' => 'Reject', 'action' => 'reject_reservation', 'color' => 'red'],
            ],
            'general' => [
                ['label' => 'Escalate', 'action' => 'escalate', 'color' => 'amber'],
                ['label' => 'Close', 'action' => 'close_thread', 'color' => 'gray'],
            ]
        ];

        return $actions[$thread->intent ?? 'general'] ?? $actions['general'];
    }

    /**
     * Get static response templates based on category
     */
    public function getTemplatesByCategory(string $category)
    {
        $category = strtolower($category);
        $templates = [
            'maintenance' => [
                ['label' => 'Forwarded', 'text' => "Your request has been forwarded to the maintenance team."],
                ['label' => 'Schedule', 'text' => "When would be the best time for our team to visit your unit?"],
                ['label' => 'Completed', 'text' => "Our team has completed the requested repairs. Please let us know if everything is okay."],
            ],
            'billing' => [
                ['label' => 'Verifying', 'text' => "We are currently verifying your payment records. Please wait for an update."],
                ['label' => 'Statement', 'text' => "You can view your latest statement in the Dues section of the portal."],
                ['label' => 'Payment Rec', 'text' => "We have received your payment. Thank you!"],
            ],
            'amenity' => [
                ['label' => 'Checking', 'text' => "Checking availability for your requested date. One moment please."],
                ['label' => 'Approved', 'text' => "Your reservation request has been approved."],
                ['label' => 'Unavailable', 'text' => "Sorry, the requested slot is currently unavailable."],
            ],
            'general' => [
                ['label' => 'Acknowledged', 'text' => "We have received your message and will get back to you shortly."],
                ['label' => 'Inquiry', 'text' => "Thank you for your inquiry. How can we further assist you?"],
                ['label' => 'Closed', 'text' => "This inquiry has been resolved and will now be closed."],
            ]
        ];

        return $templates[$category] ?? $templates['general'];
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

    private function calculateSLADeadline(string $priority): Carbon
    {
        $hours = match($priority) {
            'urgent' => 1,
            'high' => 4,
            'medium' => 24,
            'low' => 48,
            default => 24
        };
        return now()->addHours($hours);
    }
}
