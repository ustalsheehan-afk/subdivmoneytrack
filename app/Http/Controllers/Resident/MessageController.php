<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageTemplate;
use App\Models\MessageThread;
use App\Models\Resident;
use App\Models\User;
use App\Models\Notification;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;

class MessageController extends Controller
{
    public function index()
    {
        $resident = Auth::user()->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        $threads = MessageThread::where('resident_id', $resident->id)
            ->with(['latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('resident.messages.index', compact('threads'));
    }

    public function create(Request $request)
    {
        $moduleType = $request->query('module_type');
        $moduleId = $request->query('module_id');
        $subject = $request->query('subject');
        $category = $request->query('category', MessageTemplate::CATEGORY_GENERAL);
        $openTemplates = (bool) $request->boolean('open_templates');

        // Preload templates server-side so the dropdown works even if the templates API
        // is blocked by route/config caching or the browser cannot reach the endpoint.
        // Always provide up to 10 templates per category (DB-first, then defaults).
        $preloadedTemplates = [];
        $dbTemplates = collect();
        if (Schema::hasTable('message_templates')) {
            $dbTemplates = MessageTemplate::query()
                ->active()
                ->orderByDesc('use_count')
                ->orderByDesc('last_used_at')
                ->orderBy('title')
                ->get();
        }

        $merged = $this->mergeWithDefaultTemplates($dbTemplates, null);
        $preloadedTemplates = $merged
            ->groupBy('category')
            ->map(fn ($items) => $items->values())
            ->toArray();

        return view('resident.messages.create', compact(
            'moduleType',
            'moduleId',
            'subject',
            'category',
            'openTemplates',
            'preloadedTemplates'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => ['required', 'string', \Illuminate\Validation\Rule::in(array_keys(MessageTemplate::CATEGORY_LABELS))],
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'module_type' => 'nullable|string',
            'module_id' => 'nullable|integer',
            'template_id' => 'nullable|integer',
        ]);

        $resident = Auth::user()->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        $thread = DB::transaction(function () use ($request, $resident) {
            $thread = MessageThread::create([
                'resident_id' => $resident->id,
                'subject' => $request->subject,
                'category' => $request->category,
                'status' => 'pending',
                'module_type' => $request->module_type,
                'module_id' => $request->module_id,
                'last_message_at' => now(),
            ]);

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('messages', 'public');
            }

            $thread->messages()->create([
                'sender_type' => Resident::class,
                'sender_id' => $resident->id,
                'body' => $request->body,
                'attachment' => $attachmentPath,
            ]);

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            $resident_profile = Auth::user()->resident;
            foreach ($admins as $admin) {
                Notification::create([
                    'admin_id' => $admin->id,
                    'title' => '💬 New Support Message',
                    'message' => "{$resident_profile->full_name} has sent a new support message regarding '{$request->category}'.",
                    'type' => 'system',
                    'link' => route('admin.messages.index'),
                    'is_read' => false,
                ]);
            }

            return $thread;
        });

        if ($request->filled('template_id') && Schema::hasTable('message_templates')) {
            MessageTemplate::query()
                ->where('id', $request->integer('template_id'))
                ->where('category', $request->string('category')->toString())
                ->where('is_active', true)
                ->update([
                    'use_count' => DB::raw('use_count + 1'),
                    'last_used_at' => now(),
                ]);
        }

        return redirect()->route('resident.messages.show', $thread->id)->with('success', 'Message sent successfully.');
    }

    public function templatesApi(Request $request)
    {
        $category = $request->query('category');

        $dbTemplates = collect();
        if (Schema::hasTable('message_templates')) {
            $dbTemplates = MessageTemplate::query()
                ->active()
                ->when($category, function ($builder) use ($category) {
                    $builder->where('category', $category);
                })
                ->orderByDesc('use_count')
                ->orderByDesc('last_used_at')
                ->orderBy('title')
                ->get();
        }

        $templates = $this->mergeWithDefaultTemplates($dbTemplates, $category)->map(function (array $template) {
            return [
                'id' => $template['id'] ?? null,
                'category' => $template['category'],
                'category_label' => MessageTemplate::CATEGORY_LABELS[$template['category']] ?? ucfirst(str_replace('_', ' ', $template['category'])),
                'title' => $template['title'],
                'subject' => $template['subject'],
                'body' => $template['body'],
                'use_count' => (int) ($template['use_count'] ?? 0),
            ];
        })->values();

        return response()->json([
            'templates' => $templates,
            'grouped' => $templates->groupBy('category')->map(fn ($items) => $items->values()),
            'categories' => MessageTemplate::CATEGORY_LABELS,
        ]);
    }

    private function mergeWithDefaultTemplates(Collection $dbTemplates, ?string $category): Collection
    {
        $defaults = collect($this->defaultTemplates())
            ->when($category, fn ($items) => $items->where('category', $category)->values());

        $normalizedDb = $dbTemplates->map(function (MessageTemplate $template) {
            return [
                'id' => $template->id,
                'category' => $template->category,
                'title' => $template->title,
                'subject' => $template->subject,
                'body' => $template->body,
                'use_count' => (int) $template->use_count,
            ];
        });

        $groupedDb = $normalizedDb->groupBy('category');
        $groupedDefaults = $defaults->groupBy('category');

        $categories = collect(array_keys(MessageTemplate::CATEGORY_LABELS))
            ->when($category, fn ($items) => $items->filter(fn ($c) => $c === $category));

        return $categories->flatMap(function (string $categoryKey) use ($groupedDb, $groupedDefaults) {
            $existing = ($groupedDb->get($categoryKey, collect()))
                ->sortByDesc('use_count')
                ->values();

            $missingCount = max(0, 10 - $existing->count());
            if ($missingCount > 0) {
                $existingTitles = $existing->pluck('title')->map(fn ($v) => strtolower((string) $v))->all();
                $fallback = $groupedDefaults->get($categoryKey, collect())
                    ->filter(fn ($item) => ! in_array(strtolower((string) $item['title']), $existingTitles, true))
                    ->take($missingCount)
                    ->values();
                $existing = $existing->concat($fallback);
            }

            return $existing->take(10)->values();
        })->values();
    }

    private function defaultTemplates(): array
    {
        return [
            ['category' => 'general', 'title' => 'Request for Information', 'subject' => 'Request for Information', 'body' => 'Good day. I would like to request additional information regarding your services and current policies. Thank you.'],
            ['category' => 'general', 'title' => 'Clarification on Policy', 'subject' => 'Clarification on Subdivision Policy', 'body' => 'Hello. I would like clarification regarding a subdivision policy so I can ensure compliance.'],
            ['category' => 'general', 'title' => 'General Assistance Request', 'subject' => 'Request for General Assistance', 'body' => 'Hi. I need assistance regarding a general concern and would appreciate your guidance.'],
            ['category' => 'general', 'title' => 'Office Contact Inquiry', 'subject' => 'Office Contact Inquiry', 'body' => 'Good day. May I know the best contact person and channel for this concern?'],
            ['category' => 'general', 'title' => 'Follow-up on Previous Inquiry', 'subject' => 'Follow-up on Previous Inquiry', 'body' => 'Hello. I am following up on my previous inquiry. Kindly share updates when available.'],
            ['category' => 'general', 'title' => 'Inquiry About Services', 'subject' => 'Inquiry About Available Services', 'body' => 'Hi. I would like to inquire about available resident services and request procedures.'],
            ['category' => 'general', 'title' => 'Inquiry About Schedule', 'subject' => 'Inquiry About Office Schedule', 'body' => 'Good day. Please confirm your office schedule and support availability.'],
            ['category' => 'general', 'title' => 'Request for Guidelines', 'subject' => 'Request for Community Guidelines', 'body' => 'Hello. Kindly provide the latest community guidelines for my reference.'],
            ['category' => 'general', 'title' => 'Inquiry About Procedures', 'subject' => 'Inquiry About Processing Procedure', 'body' => 'Hi. I would like to understand the required procedure and expected timeline for this request.'],
            ['category' => 'general', 'title' => 'General Question', 'subject' => 'General Question', 'body' => 'Good day. I have a general question and would appreciate your assistance.'],

            ['category' => 'payment', 'title' => 'Proof of Payment Follow-up', 'subject' => 'Follow-up on Submitted Proof of Payment', 'body' => 'Good day. I submitted my proof of payment and would like to follow up on its verification status.'],
            ['category' => 'payment', 'title' => 'Payment Not Reflected', 'subject' => 'Payment Not Yet Reflected', 'body' => 'Hello. My payment is not yet reflected in the portal. Kindly assist in checking this.'],
            ['category' => 'payment', 'title' => 'Request Payment Breakdown', 'subject' => 'Request for Payment Breakdown', 'body' => 'Hi. Please provide a detailed breakdown of my current dues and charges.'],
            ['category' => 'payment', 'title' => 'Billing Discrepancy', 'subject' => 'Billing Discrepancy Concern', 'body' => 'Good day. I noticed a discrepancy in my billing and request clarification.'],
            ['category' => 'payment', 'title' => 'Late Payment Inquiry', 'subject' => 'Inquiry About Late Payment', 'body' => 'Hello. I would like to ask about late payment options and any corresponding penalties.'],
            ['category' => 'payment', 'title' => 'Payment Confirmation Request', 'subject' => 'Request for Payment Confirmation', 'body' => 'Hi. Kindly confirm whether my recent payment has been validated successfully.'],
            ['category' => 'payment', 'title' => 'Double Payment Concern', 'subject' => 'Concern About Possible Double Payment', 'body' => 'Good day. I may have paid twice by mistake. Please verify and advise on next steps.'],
            ['category' => 'payment', 'title' => 'Payment Method Inquiry', 'subject' => 'Inquiry About Payment Methods', 'body' => 'Hello. May I know the currently accepted payment methods and channels?'],
            ['category' => 'payment', 'title' => 'Outstanding Balance Clarification', 'subject' => 'Clarification on Outstanding Balance', 'body' => 'Hi. Please clarify how my outstanding balance was computed.'],
            ['category' => 'payment', 'title' => 'Request Official Receipt', 'subject' => 'Request for Official Receipt', 'body' => 'Good day. I would like to request a copy of my official receipt for recent payment.'],

            ['category' => 'complaint', 'title' => 'Service Complaint Submission', 'subject' => 'Service Complaint Submission', 'body' => 'Good day. I would like to submit a service complaint and request immediate review.'],
            ['category' => 'complaint', 'title' => 'Staff Behavior Concern', 'subject' => 'Complaint: Staff Behavior Concern', 'body' => 'Hello. I would like to report a concern regarding staff behavior and request action.'],
            ['category' => 'complaint', 'title' => 'Noise Complaint', 'subject' => 'Complaint: Noise Disturbance', 'body' => 'Hi. I would like to report recurring noise disturbance in our area.'],
            ['category' => 'complaint', 'title' => 'Facility Issue Report', 'subject' => 'Complaint: Facility Issue', 'body' => 'Good day. I am reporting a facility issue that needs attention.'],
            ['category' => 'complaint', 'title' => 'Neighbor Complaint', 'subject' => 'Complaint: Neighbor Concern', 'body' => 'Hello. I want to report a concern regarding a neighbor-related issue.'],
            ['category' => 'complaint', 'title' => 'Security Concern', 'subject' => 'Complaint: Security Concern', 'body' => 'Hi. I would like to report a security concern and request urgent review.'],
            ['category' => 'complaint', 'title' => 'Maintenance Delay Complaint', 'subject' => 'Complaint: Maintenance Delay', 'body' => 'Good day. I am filing a complaint regarding delay in maintenance response.'],
            ['category' => 'complaint', 'title' => 'Cleanliness Concern', 'subject' => 'Complaint: Cleanliness Concern', 'body' => 'Hello. I would like to report a cleanliness and sanitation issue in our area.'],
            ['category' => 'complaint', 'title' => 'Rule Violation Report', 'subject' => 'Complaint: Rule Violation Report', 'body' => 'Hi. I would like to report a possible community rule violation.'],
            ['category' => 'complaint', 'title' => 'Escalation Request', 'subject' => 'Request for Concern Escalation', 'body' => 'Good day. I request escalation of this complaint for immediate resolution.'],

            ['category' => 'reservation', 'title' => 'Reservation Request', 'subject' => 'Reservation Request', 'body' => 'Good day. I would like to request a reservation for the selected amenity.'],
            ['category' => 'reservation', 'title' => 'Reservation Availability Inquiry', 'subject' => 'Reservation Availability Inquiry', 'body' => 'Hello. Please confirm amenity availability for my preferred schedule.'],
            ['category' => 'reservation', 'title' => 'Cancel Reservation', 'subject' => 'Request to Cancel Reservation', 'body' => 'Hi. I would like to cancel my existing reservation. Kindly advise required steps.'],
            ['category' => 'reservation', 'title' => 'Modify Reservation', 'subject' => 'Request to Modify Reservation', 'body' => 'Good day. I request to modify my reservation details and preferred schedule.'],
            ['category' => 'reservation', 'title' => 'Reservation Confirmation', 'subject' => 'Reservation Confirmation Request', 'body' => 'Hello. I would like to confirm the status of my submitted reservation.'],
            ['category' => 'reservation', 'title' => 'Reservation Follow-up', 'subject' => 'Follow-up on Reservation', 'body' => 'Hi. I am following up on my reservation request and approval progress.'],
            ['category' => 'reservation', 'title' => 'Amenity Booking Inquiry', 'subject' => 'Amenity Booking Inquiry', 'body' => 'Good day. I need assistance regarding amenity booking process and requirements.'],
            ['category' => 'reservation', 'title' => 'Schedule Conflict Inquiry', 'subject' => 'Reservation Schedule Conflict', 'body' => 'Hello. I encountered a schedule conflict and need help with available alternatives.'],
            ['category' => 'reservation', 'title' => 'Reservation Approval Follow-up', 'subject' => 'Follow-up on Reservation Approval', 'body' => 'Hi. Kindly provide updates on the approval of my reservation request.'],
            ['category' => 'reservation', 'title' => 'Reservation Policy Question', 'subject' => 'Question About Reservation Policy', 'body' => 'Good day. I would like clarification on reservation policy details.'],

            ['category' => 'service_request', 'title' => 'Maintenance Request', 'subject' => 'Service Request: Maintenance', 'body' => 'Good day. I would like to submit a maintenance request for assistance.'],
            ['category' => 'service_request', 'title' => 'Repair Request', 'subject' => 'Service Request: Repair', 'body' => 'Hello. I need repair assistance and would like to request service support.'],
            ['category' => 'service_request', 'title' => 'Plumbing Issue', 'subject' => 'Service Request: Plumbing Issue', 'body' => 'Hi. I would like to report a plumbing issue and request immediate support.'],
            ['category' => 'service_request', 'title' => 'Electrical Issue', 'subject' => 'Service Request: Electrical Issue', 'body' => 'Good day. I am reporting an electrical issue that requires inspection and repair.'],
            ['category' => 'service_request', 'title' => 'Garbage Collection Request', 'subject' => 'Service Request: Garbage Collection', 'body' => 'Hello. I would like to request assistance regarding garbage collection concerns.'],
            ['category' => 'service_request', 'title' => 'Landscaping Request', 'subject' => 'Service Request: Landscaping', 'body' => 'Hi. I would like to request landscaping support for the area concerned.'],
            ['category' => 'service_request', 'title' => 'Pest Control Request', 'subject' => 'Service Request: Pest Control', 'body' => 'Good day. I would like to request pest control service for this issue.'],
            ['category' => 'service_request', 'title' => 'Cleaning Request', 'subject' => 'Service Request: Cleaning', 'body' => 'Hello. I would like to request cleaning service assistance.'],
            ['category' => 'service_request', 'title' => 'Facility Repair Follow-up', 'subject' => 'Follow-up on Facility Repair Request', 'body' => 'Hi. I am following up on a previously reported facility repair request.'],
            ['category' => 'service_request', 'title' => 'Urgent Service Request', 'subject' => 'Urgent Service Request', 'body' => 'Good day. This is an urgent service request and needs immediate attention.'],
        ];
    }

    public function show(MessageThread $thread)
    {
        $resident = Auth::user()->resident;

        if (!$resident || $thread->resident_id !== $resident->id) {
            abort(403);
        }
        
        $thread->load(['messages.sender']);
        
        // Mark admin messages as read
        $thread->messages()->whereIn('sender_type', [User::class, Admin::class])->update(['is_read' => true]);

        // Mark notifications as read
        Notification::where('resident_id', Auth::user()->id)
            ->where('link', 'like', '%' . route('resident.messages.show', $thread->id) . '%')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('resident.messages.show', compact('thread'));
    }

    public function reply(Request $request, MessageThread $thread)
    {
        $resident = Auth::user()->resident;

        if (!$resident || $thread->resident_id !== $resident->id) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::transaction(function () use ($request, $thread, $resident) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('messages', 'public');
            }

            $thread->messages()->create([
                'sender_type' => Resident::class,
                'sender_id' => $resident->id,
                'body' => $request->body,
                'attachment' => $attachmentPath,
            ]);

            $thread->update([
                'status' => 'pending', // Reset to pending for admin attention
                'last_message_at' => now(),
            ]);

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            $resident_profile = Auth::user()->resident;
            foreach ($admins as $admin) {
                Notification::create([
                    'admin_id' => $admin->id,
                    'title' => '💬 New Reply from Resident',
                    'message' => "{$resident_profile->full_name} replied to: '{$thread->subject}'.",
                    'type' => 'system',
                    'link' => route('admin.messages.index'),
                    'is_read' => false,
                ]);
            }
        });

        return back()->with('success', 'Reply sent successfully.');
    }
}
