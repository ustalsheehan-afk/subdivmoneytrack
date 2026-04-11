@extends('resident.layouts.app')

@section('title', 'New Message')
@section('page-title', 'New Message')

@section('content')
<div class="max-w-5xl mx-auto pb-20" x-data="messageComposer({
    initialCategory: @json(old('category', $category ?? 'general')),
    initialSubject: @json(old('subject', $subject)),
    initialBody: @json(old('body')),
    openTemplates: @json((bool) ($openTemplates ?? false)),
    endpoint: @json(url('/api/message-templates'))
})" x-init="init()">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('resident.messages.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:bg-emerald-50 transition-all">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight">New Message</h3>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mt-1">Dynamic templates + manual message input</p>
        </div>
    </div>

    <form action="{{ route('resident.messages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if($moduleType)
            <input type="hidden" name="module_type" value="{{ $moduleType }}">
            <input type="hidden" name="module_id" value="{{ $moduleId }}">
        @endif

        <input type="hidden" name="template_id" :value="selectedTemplateId">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Category</label>
                        <div class="relative">
                            <select name="category" x-model="selectedCategory" @change="onCategoryChange" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-bold appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none">
                                <option value="general">General Inquiry</option>
                                <option value="payment">Payment Concern</option>
                                <option value="complaint">Complaint</option>
                                <option value="reservation">Reservation</option>
                                <option value="service_request">Service Request</option>
                            </select>
                            <i class="bi bi-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-4 space-y-3">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block">Template</label>
                        <div class="relative">
                            <select x-model="selectedTemplateKey" @change="applySelectedTemplate()" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm font-semibold appearance-none focus:border-emerald-500 focus:ring-emerald-500/20 outline-none">
                                <option value="">Select a template...</option>
                                <template x-for="template in currentTemplates" :key="templateKey(template)">
                                    <option :value="templateKey(template)" x-text="template.title"></option>
                                </template>
                            </select>
                            <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>

                        <div class="flex flex-wrap gap-2" x-show="quickTemplates.length > 0" x-cloak>
                            <template x-for="template in quickTemplates" :key="'quick-' + templateKey(template)">
                                <button type="button" @click="selectTemplate(template)" class="px-3 py-2 rounded-xl border border-emerald-100 bg-white text-[10px] font-black uppercase tracking-widest text-emerald-700 hover:bg-emerald-50">
                                    <span x-text="template.title"></span>
                                </button>
                            </template>
                        </div>

                        <p x-show="!templatesLoading && currentTemplates.length === 0" class="text-xs text-gray-500" x-cloak>No templates available. You can still write manually.</p>
                        <p x-show="templatesError" class="text-xs text-amber-700" x-text="templatesError" x-cloak></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Subject</label>
                        <input type="text" name="subject" x-model="subject" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all outline-none" placeholder="Enter message subject..." required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Message Body</label>
                        <textarea name="body" rows="8" x-model="body" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all outline-none resize-none" placeholder="Describe your inquiry in detail..." required></textarea>
                        <p class="text-[11px] text-gray-500">Template text is editable. You can freely add or remove anything.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6" x-data="{
                    attachmentName: '',
                    attachmentPreview: '',
                    attachmentType: '',
                    setAttachment(event) {
                        const file = event.target.files[0];

                        if (!file) {
                            this.clearAttachment();
                            return;
                        }

                        if (this.attachmentPreview) {
                            URL.revokeObjectURL(this.attachmentPreview);
                        }

                        this.attachmentName = file.name;
                        this.attachmentType = file.type || '';
                        this.attachmentPreview = URL.createObjectURL(file);
                    },
                    clearAttachment() {
                        if (this.attachmentPreview) {
                            URL.revokeObjectURL(this.attachmentPreview);
                        }

                        this.attachmentName = '';
                        this.attachmentPreview = '';
                        this.attachmentType = '';
                    }
                }">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Attachment (Optional)</label>
                        <label for="resident-message-attachment" class="block w-full px-6 py-4 rounded-2xl border border-dashed border-gray-200 bg-gray-50/50 hover:bg-emerald-50/50 hover:border-emerald-200 transition-all cursor-pointer text-center group">
                            <input id="resident-message-attachment" x-ref="attachmentInput" type="file" name="attachment" accept="image/*,.pdf" class="sr-only" @change="setAttachment($event)">
                            <i class="bi bi-paperclip text-lg text-gray-400 group-hover:text-emerald-600"></i>
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1 group-hover:text-emerald-600">Choose File</span>
                        </label>
                        <div class="mt-3 space-y-3" x-show="attachmentName" x-cloak>
                            <div class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
                                <template x-if="attachmentType.startsWith('image/')">
                                    <img :src="attachmentPreview" alt="Attachment preview" class="w-full max-h-56 object-cover rounded-xl border border-gray-100">
                                </template>
                                <template x-if="!attachmentType.startsWith('image/')">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                            <i class="bi bi-paperclip text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-900 truncate" x-text="attachmentName"></p>
                                            <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Ready to upload</p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <button type="button" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-red-500" @click="clearAttachment(); $refs.attachmentInput.value = ''">Remove file</button>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <button type="submit" class="w-full py-5 rounded-2xl bg-[#0D1F1C] text-[#B6FF5C] font-black uppercase tracking-widest hover:shadow-[0_0_20px_rgba(182,255,92,0.3)] transition-all active:scale-95">Send Message</button>
                    <a href="{{ route('resident.messages.index') }}" class="block w-full py-5 text-center text-gray-500 text-sm font-bold hover:text-gray-700 transition-all">Cancel / Back</a>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
function messageComposer(config) {
    return {
        endpoint: config.endpoint,
        selectedCategory: config.initialCategory || 'general',
        subject: config.initialSubject || '',
        body: config.initialBody || '',
        selectedTemplateId: '',
        selectedTemplateKey: '',
        groupedTemplates: {},
        fallbackTemplates: {
            general: [
                { id: null, category: 'general', title: 'Request for Information', subject: 'Request for Information', body: 'Good day. I would like to request additional information regarding your services and current policies. Thank you.' },
                { id: null, category: 'general', title: 'Clarification on Policy', subject: 'Clarification on Policy', body: 'Hello. I would like clarification regarding a subdivision policy so I can ensure compliance.' },
                { id: null, category: 'general', title: 'General Assistance Request', subject: 'General Assistance Request', body: 'Hi. I need assistance regarding a general concern and would appreciate your guidance.' },
                { id: null, category: 'general', title: 'Office Contact Inquiry', subject: 'Office Contact Inquiry', body: 'Good day. May I know the best contact person and channel for this concern?' },
                { id: null, category: 'general', title: 'Follow-up on Previous Inquiry', subject: 'Follow-up on Previous Inquiry', body: 'Hello. I am following up on my previous inquiry. Kindly share updates when available.' },
                { id: null, category: 'general', title: 'Inquiry About Services', subject: 'Inquiry About Services', body: 'Hi. I would like to inquire about available resident services and request procedures.' },
                { id: null, category: 'general', title: 'Inquiry About Schedule', subject: 'Inquiry About Schedule', body: 'Good day. Please confirm your office schedule and support availability.' },
                { id: null, category: 'general', title: 'Request for Guidelines', subject: 'Request for Guidelines', body: 'Hello. Kindly provide the latest community guidelines for my reference.' },
                { id: null, category: 'general', title: 'Inquiry About Procedures', subject: 'Inquiry About Procedures', body: 'Hi. I would like to understand the required procedure and expected timeline for this request.' },
                { id: null, category: 'general', title: 'General Question', subject: 'General Question', body: 'Good day. I have a general question and would appreciate your assistance.' }
            ],
            payment: [
                { id: null, category: 'payment', title: 'Proof of Payment Follow-up', subject: 'Proof of Payment Follow-up', body: 'Good day. I submitted my proof of payment and would like to follow up on its verification status.' },
                { id: null, category: 'payment', title: 'Payment Not Reflected', subject: 'Payment Not Reflected', body: 'Hello. My payment is not yet reflected in the portal. Kindly assist in checking this.' },
                { id: null, category: 'payment', title: 'Request Payment Breakdown', subject: 'Request Payment Breakdown', body: 'Hi. Please provide a detailed breakdown of my current dues and charges.' },
                { id: null, category: 'payment', title: 'Billing Discrepancy', subject: 'Billing Discrepancy', body: 'Good day. I noticed a discrepancy in my billing and request clarification.' },
                { id: null, category: 'payment', title: 'Late Payment Inquiry', subject: 'Late Payment Inquiry', body: 'Hello. I would like to ask about late payment options and any corresponding penalties.' },
                { id: null, category: 'payment', title: 'Payment Confirmation Request', subject: 'Payment Confirmation Request', body: 'Hi. Kindly confirm whether my recent payment has been validated successfully.' },
                { id: null, category: 'payment', title: 'Double Payment Concern', subject: 'Double Payment Concern', body: 'Good day. I may have paid twice by mistake. Please verify and advise on next steps.' },
                { id: null, category: 'payment', title: 'Payment Method Inquiry', subject: 'Payment Method Inquiry', body: 'Hello. May I know the currently accepted payment methods and channels?' },
                { id: null, category: 'payment', title: 'Outstanding Balance Clarification', subject: 'Outstanding Balance Clarification', body: 'Hi. Please clarify how my outstanding balance was computed.' },
                { id: null, category: 'payment', title: 'Request Official Receipt', subject: 'Request Official Receipt', body: 'Good day. I would like to request a copy of my official receipt for recent payment.' }
            ],
            complaint: [
                { id: null, category: 'complaint', title: 'Service Complaint Submission', subject: 'Service Complaint Submission', body: 'Good day. I would like to submit a service complaint and request immediate review.' },
                { id: null, category: 'complaint', title: 'Staff Behavior Concern', subject: 'Staff Behavior Concern', body: 'Hello. I would like to report a concern regarding staff behavior and request action.' },
                { id: null, category: 'complaint', title: 'Noise Complaint', subject: 'Noise Complaint', body: 'Hi. I would like to report recurring noise disturbance in our area.' },
                { id: null, category: 'complaint', title: 'Facility Issue Report', subject: 'Facility Issue Report', body: 'Good day. I am reporting a facility issue that needs attention.' },
                { id: null, category: 'complaint', title: 'Neighbor Complaint', subject: 'Neighbor Complaint', body: 'Hello. I want to report a concern regarding a neighbor-related issue.' },
                { id: null, category: 'complaint', title: 'Security Concern', subject: 'Security Concern', body: 'Hi. I would like to report a security concern and request urgent review.' },
                { id: null, category: 'complaint', title: 'Maintenance Delay Complaint', subject: 'Maintenance Delay Complaint', body: 'Good day. I am filing a complaint regarding delay in maintenance response.' },
                { id: null, category: 'complaint', title: 'Cleanliness Concern', subject: 'Cleanliness Concern', body: 'Hello. I would like to report a cleanliness and sanitation issue in our area.' },
                { id: null, category: 'complaint', title: 'Rule Violation Report', subject: 'Rule Violation Report', body: 'Hi. I would like to report a possible community rule violation.' },
                { id: null, category: 'complaint', title: 'Escalation Request', subject: 'Escalation Request', body: 'Good day. I request escalation of this complaint for immediate resolution.' }
            ],
            reservation: [
                { id: null, category: 'reservation', title: 'Reservation Request', subject: 'Reservation Request', body: 'Good day. I would like to request a reservation for the selected amenity.' },
                { id: null, category: 'reservation', title: 'Reservation Availability Inquiry', subject: 'Reservation Availability Inquiry', body: 'Hello. Please confirm amenity availability for my preferred schedule.' },
                { id: null, category: 'reservation', title: 'Cancel Reservation', subject: 'Cancel Reservation', body: 'Hi. I would like to cancel my existing reservation. Kindly advise required steps.' },
                { id: null, category: 'reservation', title: 'Modify Reservation', subject: 'Modify Reservation', body: 'Good day. I request to modify my reservation details and preferred schedule.' },
                { id: null, category: 'reservation', title: 'Reservation Confirmation', subject: 'Reservation Confirmation', body: 'Hello. I would like to confirm the status of my submitted reservation.' },
                { id: null, category: 'reservation', title: 'Reservation Follow-up', subject: 'Reservation Follow-up', body: 'Hi. I am following up on my reservation request and approval progress.' },
                { id: null, category: 'reservation', title: 'Amenity Booking Inquiry', subject: 'Amenity Booking Inquiry', body: 'Good day. I need assistance regarding amenity booking process and requirements.' },
                { id: null, category: 'reservation', title: 'Schedule Conflict Inquiry', subject: 'Schedule Conflict Inquiry', body: 'Hello. I encountered a schedule conflict and need help with available alternatives.' },
                { id: null, category: 'reservation', title: 'Reservation Approval Follow-up', subject: 'Reservation Approval Follow-up', body: 'Hi. Kindly provide updates on the approval of my reservation request.' },
                { id: null, category: 'reservation', title: 'Reservation Policy Question', subject: 'Reservation Policy Question', body: 'Good day. I would like clarification on reservation policy details.' }
            ],
            service_request: [
                { id: null, category: 'service_request', title: 'Maintenance Request', subject: 'Maintenance Request', body: 'Good day. I would like to submit a maintenance request for assistance.' },
                { id: null, category: 'service_request', title: 'Repair Request', subject: 'Repair Request', body: 'Hello. I need repair assistance and would like to request service support.' },
                { id: null, category: 'service_request', title: 'Plumbing Issue', subject: 'Plumbing Issue', body: 'Hi. I would like to report a plumbing issue and request immediate support.' },
                { id: null, category: 'service_request', title: 'Electrical Issue', subject: 'Electrical Issue', body: 'Good day. I am reporting an electrical issue that requires inspection and repair.' },
                { id: null, category: 'service_request', title: 'Garbage Collection Request', subject: 'Garbage Collection Request', body: 'Hello. I would like to request assistance regarding garbage collection concerns.' },
                { id: null, category: 'service_request', title: 'Landscaping Request', subject: 'Landscaping Request', body: 'Hi. I would like to request landscaping support for the area concerned.' },
                { id: null, category: 'service_request', title: 'Pest Control Request', subject: 'Pest Control Request', body: 'Good day. I would like to request pest control service for this issue.' },
                { id: null, category: 'service_request', title: 'Cleaning Request', subject: 'Cleaning Request', body: 'Hello. I would like to request cleaning service assistance.' },
                { id: null, category: 'service_request', title: 'Facility Repair Follow-up', subject: 'Facility Repair Follow-up', body: 'Hi. I am following up on a previously reported facility repair request.' },
                { id: null, category: 'service_request', title: 'Urgent Service Request', subject: 'Urgent Service Request', body: 'Good day. This is an urgent service request and needs immediate attention.' }
            ]
        },
        currentTemplates: [],
        quickTemplates: [],
        templatesLoading: false,
        templatesError: '',

        async init() {
            await this.preloadTemplates();
            this.onCategoryChange();
        },

        async preloadTemplates() {
            this.templatesLoading = true;
            this.templatesError = '';

            try {
                const response = await fetch(this.endpoint, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Unable to load templates. You can still write manually.');
                }

                const payload = await response.json();
                const grouped = payload.grouped || {};
                const normalized = {};
                Object.keys(grouped).forEach((key) => {
                    normalized[key] = Array.isArray(grouped[key])
                        ? grouped[key]
                        : Object.values(grouped[key] || {});
                });
                this.groupedTemplates = Object.keys(normalized).length > 0 ? normalized : this.fallbackTemplates;
            } catch (error) {
                this.templatesError = error.message || 'Template service unavailable. You can still write manually.';
                this.groupedTemplates = this.fallbackTemplates;
            } finally {
                this.templatesLoading = false;
            }
        },

        onCategoryChange() {
            const currentTemplates = this.groupedTemplates[this.selectedCategory] || [];
            this.currentTemplates = currentTemplates;
            this.quickTemplates = currentTemplates.slice(0, 3);
            this.selectedTemplateKey = '';
            this.selectedTemplateId = '';
        },

        templateKey(template) {
            return `${template.category || this.selectedCategory}::${template.id ?? 'x'}::${template.title}`;
        },

        applySelectedTemplate() {
            const selected = this.currentTemplates.find((template) => this.templateKey(template) === this.selectedTemplateKey);
            if (selected) {
                this.selectTemplate(selected);
            }
        },

        selectTemplate(template) {
            this.selectedTemplateKey = this.templateKey(template);
            this.selectedTemplateId = template.id;
            this.subject = template.subject || this.subject;
            this.body = template.body || this.body;
        }
    };
}
</script>
@endsection
