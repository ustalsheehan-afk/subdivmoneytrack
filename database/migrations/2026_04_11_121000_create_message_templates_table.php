<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50);
            $table->string('title', 150);
            $table->string('subject', 255);
            $table->text('body');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('use_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index(['category', 'is_active']);
            $table->index(['is_active', 'use_count']);
        });

        DB::table('message_templates')->insert([
            // General Inquiry
            ['category' => 'general', 'title' => 'General Information Request', 'subject' => 'General Inquiry', 'body' => 'Good day. I would like to request more information regarding subdivision services and policies. Thank you.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'general', 'title' => 'Office Hours Confirmation', 'subject' => 'Inquiry About Office Hours', 'body' => 'Hello. May I confirm the current office hours and best time to visit for assistance?', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'general', 'title' => 'Contact Person Request', 'subject' => 'Request for Contact Information', 'body' => 'Hi. Please provide the appropriate contact person for my concern so I can coordinate properly.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'general', 'title' => 'Document Request', 'subject' => 'Request for Copy of Document', 'body' => 'Good day. I would like to request a copy of the relevant subdivision document for my reference.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'general', 'title' => 'Follow-up Inquiry', 'subject' => 'Follow-up on Previous Inquiry', 'body' => 'Hello. I am following up on my previous inquiry. Kindly share any updates when available.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],

            // Payment Concern
            ['category' => 'payment', 'title' => 'Request Payment Breakdown', 'subject' => 'Request for Payment Breakdown', 'body' => 'Good day. I would like to request a detailed breakdown of my current dues and charges.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'payment', 'title' => 'Proof of Payment Follow-up', 'subject' => 'Follow-up on Submitted Proof of Payment', 'body' => 'Hello. I submitted my proof of payment and would like to follow up on its verification status.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'payment', 'title' => 'Billing Clarification', 'subject' => 'Billing Clarification Request', 'body' => 'Hi. I need clarification regarding the billing amount reflected in my account.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'payment', 'title' => 'Penalty Inquiry', 'subject' => 'Inquiry About Penalty Charges', 'body' => 'Good day. Please clarify the penalty charges applied to my account and how they were computed.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'payment', 'title' => 'Payment Posting Delay', 'subject' => 'Payment Not Yet Reflected', 'body' => 'Hello. My payment is not yet reflected in the portal. Kindly assist in checking the status.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],

            // Complaint
            ['category' => 'complaint', 'title' => 'Noise Complaint', 'subject' => 'Complaint: Noise Disturbance', 'body' => 'Good day. I would like to report recurring noise disturbance in our area, especially during quiet hours.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'complaint', 'title' => 'Neighbor Conduct Concern', 'subject' => 'Complaint: Resident Conduct', 'body' => 'Hello. I would like to raise a concern regarding inappropriate resident behavior that affects the community.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'complaint', 'title' => 'Cleanliness Issue', 'subject' => 'Complaint: Cleanliness and Sanitation', 'body' => 'Hi. I am reporting a cleanliness issue in our area and requesting immediate attention.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'complaint', 'title' => 'Security Concern', 'subject' => 'Complaint: Security Concern', 'body' => 'Good day. I want to report a security concern and request that this be reviewed as soon as possible.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'complaint', 'title' => 'Facility Misuse Report', 'subject' => 'Complaint: Misuse of Community Facility', 'body' => 'Hello. I would like to report misuse of a community facility and request appropriate action.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],

            // Reservation
            ['category' => 'reservation', 'title' => 'Booking Availability Check', 'subject' => 'Reservation Availability Inquiry', 'body' => 'Good day. I would like to check availability for the selected amenity on my preferred schedule.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'reservation', 'title' => 'Reservation Confirmation Follow-up', 'subject' => 'Follow-up on Reservation Confirmation', 'body' => 'Hello. I recently submitted a reservation request and would like to follow up on confirmation.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'reservation', 'title' => 'Reservation Reschedule', 'subject' => 'Request to Reschedule Reservation', 'body' => 'Hi. I would like to request a reschedule of my existing reservation due to schedule conflict.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'reservation', 'title' => 'Reservation Cancellation', 'subject' => 'Request to Cancel Reservation', 'body' => 'Good day. I would like to cancel my reservation. Please advise if there are any required steps.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'reservation', 'title' => 'Reservation Payment Inquiry', 'subject' => 'Reservation Payment Inquiry', 'body' => 'Hello. I need assistance regarding payment requirements for my reservation request.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],

            // Service Request
            ['category' => 'service_request', 'title' => 'Maintenance Follow-up', 'subject' => 'Follow-up on Service Request', 'body' => 'Good day. I would like to follow up on my submitted service request and current processing status.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'service_request', 'title' => 'Urgent Repair Request', 'subject' => 'Urgent Service Request', 'body' => 'Hello. I am requesting urgent repair assistance due to an issue that needs immediate attention.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'service_request', 'title' => 'Water Service Concern', 'subject' => 'Service Request: Water Concern', 'body' => 'Hi. I would like to report a water-related concern and request maintenance support.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'service_request', 'title' => 'Electrical Concern', 'subject' => 'Service Request: Electrical Concern', 'body' => 'Good day. I am reporting an electrical issue and requesting inspection and repair.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'service_request', 'title' => 'Common Area Repair', 'subject' => 'Service Request: Common Area Repair', 'body' => 'Hello. I would like to report a repair issue in a common area and request assistance.', 'is_active' => true, 'use_count' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_templates');
    }
};
