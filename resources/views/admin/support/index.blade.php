@extends('layouts.admin')

@section('title', 'Support Messages')
@section('page-title', 'Support')

@section('content')
<div class="space-y-8 animate-fade-in">

    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Support Messages
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Manage resident inquiries, concerns, and maintenance reports.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                    <i class="bi bi-headset text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- SUMMARY STATS --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Pending --}}
        <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
            <div class="w-16 h-16 rounded-[24px] bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-all duration-500 border border-amber-100/50 shadow-sm">
                <i class="bi bi-hourglass-split text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Pending Review</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">{{ $summary['pending'] }}</h3>
            </div>
        </div>
        
        {{-- Unread --}}
        <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
            <div class="w-16 h-16 rounded-[24px] bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-all duration-500 border border-emerald-100/50 shadow-sm">
                <i class="bi bi-chat-left-text text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-1">New Messages</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">{{ $summary['unread'] }}</h3>
            </div>
        </div>

        {{-- Replied --}}
        <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
            <div class="w-16 h-16 rounded-[24px] bg-gray-900 flex items-center justify-center text-brand-accent group-hover:scale-110 transition-all duration-500 border border-white/10 shadow-lg">
                <i class="bi bi-check-all text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Resolved</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">{{ $summary['replied'] }}</h3>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TOOLBAR SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-4">
        <form action="{{ route('admin.support.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex flex-wrap items-center gap-4 flex-1">
                {{-- Search --}}
                <div class="relative group flex-1 max-w-md">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search resident or message..." 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
                </div>

                {{-- Status Filter --}}
                <div class="relative group min-w-[160px]">
                    <select name="status" onchange="this.form.submit()" 
                        class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                        <option value="">ALL STATUSES</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>PENDING</option>
                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>REPLIED</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
                </div>

                {{-- Category Filter --}}
                <div class="relative group min-w-[200px]">
                    <select name="category" onchange="this.form.submit()" 
                        class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                        <option value="">ALL CATEGORIES</option>
                        <option value="General Inquiry" {{ request('category') == 'General Inquiry' ? 'selected' : '' }}>GENERAL INQUIRY</option>
                        <option value="Payment Concern" {{ request('category') == 'Payment Concern' ? 'selected' : '' }}>PAYMENT CONCERN</option>
                        <option value="Maintenance Follow-up" {{ request('category') == 'Maintenance Follow-up' ? 'selected' : '' }}>MAINTENANCE FOLLOW-UP</option>
                        <option value="Reservation Concern" {{ request('category') == 'Reservation Concern' ? 'selected' : '' }}>RESERVATION CONCERN</option>
                        <option value="Complaint / Report" {{ request('category') == 'Complaint / Report' ? 'selected' : '' }}>COMPLAINT / REPORT</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
                </div>
            </div>

            {{-- Reset Filters --}}
            @if(request()->anyFilled(['search', 'status', 'category', 'date']))
                <a href="{{ route('admin.support.index') }}" class="btn-secondary px-6">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset Filters
                </a>
            @endif
        </form>
    </div>

    {{-- ===================== --}}
    {{-- MESSAGE TABLE --}}
    {{-- ===================== --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Category</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Message Preview</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Received</th>
                        <th class="px-8 py-5 w-16"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($messages as $message)
                    <tr class="group hover:bg-emerald-50/30 transition-all duration-300 cursor-pointer border-l-4 border-transparent hover:border-emerald-500 {{ !$message->is_read_by_admin ? 'bg-emerald-50/10' : '' }}" 
                        onclick="openReplyModal({{ json_encode($message) }})">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-sm font-black text-gray-600 border border-gray-100 shadow-sm group-hover:scale-110 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all duration-500">
                                        {{ substr($message->resident?->first_name ?? '?', 0, 1) }}{{ substr($message->resident?->last_name ?? '?', 0, 1) }}
                                    </div>
                                    @if(!$message->is_read_by_admin)
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center shadow-sm border border-gray-100">
                                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-gray-900 group-hover:text-emerald-700 transition-colors truncate">{{ $message->resident?->full_name ?? 'Unknown Resident' }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Blk {{ $message->resident?->block ?? '-' }} • Lot {{ $message->resident?->lot ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black bg-gray-50 text-gray-500 uppercase tracking-widest border border-gray-100 group-hover:bg-emerald-50 group-hover:text-emerald-600 group-hover:border-emerald-100 transition-all">
                                {{ $message->category }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm text-gray-600 font-medium max-w-xs truncate group-hover:text-gray-900 transition-colors">{{ $message->message }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($message->status === 'replied')
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Replied
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-[11px] font-black text-gray-900 tracking-tight">{{ $message->created_at->format('M d, Y') }}</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase mt-0.5 tracking-widest">{{ $message->created_at->format('g:i A') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <i class="bi bi-chevron-right text-gray-300 group-hover:text-emerald-500 transition-all transform group-hover:translate-x-1"></i>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mb-6 mx-auto text-gray-200 shadow-inner">
                                <i class="bi bi-chat-dots text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tight">No messages found</h3>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Adjust filters to find support tickets</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $messages->links() }}
    </div>
</div>

{{-- REPLY MODAL --}}
<div id="replyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden animate-zoom-in border border-gray-100">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div id="modalResidentAvatar" class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-lg shadow-sm ring-4 ring-white"></div>
                <div>
                    <h4 id="modalResidentName" class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1"></h4>
                    <p id="modalCategoryLabel" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"></p>
                </div>
            </div>
            <button onclick="closeModal()" class="w-10 h-10 rounded-xl hover:bg-gray-50 text-gray-400 hover:text-brand-accent transition-all flex items-center justify-center">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
            {{-- Resident Message --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident Message</span>
                    <span id="modalDate" class="text-[10px] font-bold text-gray-400"></span>
                </div>
                <div class="p-6 bg-gray-50 border border-gray-100 rounded-[1.5rem] text-sm text-gray-700 leading-relaxed font-medium shadow-sm">
                    <p id="modalMessage"></p>
                    <div id="modalResidentAttachment" class="mt-4 hidden">
                        <img id="modalResidentImg" src="#" class="max-w-[150px] rounded-xl border border-gray-100 shadow-sm cursor-pointer hover:brightness-95 transition-all" onclick="openLightbox(this.src)">
                    </div>
                </div>
            </div>

            {{-- Admin Reply --}}
            <div id="adminReplyContainer" class="space-y-3 hidden">
                <div class="flex items-center justify-between px-1">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Office Response</span>
                    <span id="modalReplyDate" class="text-[10px] font-bold text-gray-400"></span>
                </div>
                <div class="p-6 bg-emerald-50/20 border border-emerald-100 rounded-[1.5rem] text-sm text-gray-700 leading-relaxed font-medium">
                    <p id="modalAdminReply"></p>
                    <div id="modalAdminAttachmentView" class="mt-4 hidden">
                        <img id="modalAdminImgView" src="#" class="max-w-[150px] rounded-xl border border-emerald-100 shadow-sm cursor-pointer hover:brightness-95 transition-all" onclick="openLightbox(this.src)">
                    </div>
                </div>
            </div>

            {{-- Reply Form --}}
            <form id="replyForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    {{-- Suggested Replies (Pills) --}}
                    <div id="suggestedRepliesSection" class="space-y-3">
                        <div class="flex items-center justify-between px-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Suggested Replies</label>
                            <span class="text-[10px] font-bold text-gray-400 italic opacity-60">Click a pill to auto-fill</span>
                        </div>
                        <div id="templatePillsContainer" class="flex flex-wrap gap-2 min-h-[40px]">
                            <!-- Pills injected via JS -->
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1 pt-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type Response</label>
                    </div>
                    <textarea name="admin_reply" id="admin_reply" rows="5" 
                        class="w-full px-6 py-5 rounded-[1.5rem] border border-gray-100 bg-gray-50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none resize-none shadow-sm" 
                        placeholder="Type your response here..." required></textarea>
                </div>

                {{-- ATTACHMENT SECTION --}}
                <div id="adminAttachmentSection" class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="bi bi-paperclip"></i> Attachment (Optional)
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="shrink-0 cursor-pointer group">
                            <input type="file" name="attachment" class="hidden" accept="image/*" onchange="previewAdminFile(this)">
                            <div class="px-5 py-2.5 rounded-xl border-2 border-dashed border-gray-200 text-gray-400 group-hover:border-emerald-400 group-hover:text-emerald-600 transition-all flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider">
                                <i class="bi bi-image"></i>
                                <span>Attach Photo</span>
                            </div>
                        </label>
                        <div id="adminFilePreview" class="hidden relative group">
                            <img id="adminPreviewImg" src="#" class="w-14 h-14 rounded-xl object-cover border border-gray-100 shadow-sm">
                            <button type="button" onclick="removeAdminFile()" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="closeModal()" class="btn-secondary px-8">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn-premium px-12">
                        <i class="bi bi-send-fill"></i>
                        Send Response
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- LIGHTBOX MODAL --}}
<div id="lightboxModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/95 backdrop-blur-md" onclick="closeLightbox()">
    <button class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all">
        <i class="bi bi-x-lg text-xl"></i>
    </button>
    <img id="lightboxImg" src="#" class="max-w-[90vw] max-h-[85vh] rounded-xl shadow-2xl animate-zoom-in object-contain">
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-4">
        <a id="lightboxDownload" href="#" download class="px-8 py-3 bg-white/10 hover:bg-white/20 text-white text-[11px] font-bold rounded-full flex items-center gap-2 transition-all uppercase tracking-widest backdrop-blur-sm border border-white/10">
            <i class="bi bi-download"></i>
            <span>Download Image</span>
        </a>
    </div>
</div>

<script>
    const categoryTemplates = {
        'Payment Concern': [
            { label: 'Payment Received', text: "Thank you for your payment. It is currently under verification. We will update you shortly." },
            { label: 'Confirmed', text: "Your payment has been successfully confirmed. Thank you for your prompt settlement." },
            { label: 'Clarification', text: "We are unable to verify your payment. Kindly provide a valid reference number or proof." },
            { label: 'Mismatch', text: "The submitted payment does not match the required amount. Please review and resubmit." },
            { label: 'Reminder', text: "This is a reminder that your payment is still pending. Kindly settle to avoid penalties." }
        ],
        'Maintenance Follow-up': [
            { label: 'Received', text: "Your request has been received and is currently under review." },
            { label: 'Scheduled', text: "A technician has been assigned and your request is scheduled." },
            { label: 'In Progress', text: "Our team is currently working on your concern." },
            { label: 'Resolved', text: "The issue has been successfully resolved. Please confirm if everything is working properly." },
            { label: 'More Details', text: "Kindly provide additional details so we can proceed with your request." }
        ],
        'Reservation Concern': [
            { label: 'Confirmed', text: "Your reservation has been successfully confirmed." },
            { label: 'Under Review', text: "Your request is under review. We will notify you once approved." },
            { label: 'Unavailable', text: "The selected schedule is unavailable. Please choose another time." },
            { label: 'Reminder', text: "This is a reminder for your upcoming reservation." },
            { label: 'Declined', text: "Your reservation request has been declined. Please contact us for alternatives." }
        ],
        'General Inquiry': [
            { label: 'Acknowledge', text: "Thank you for your inquiry. We will get back to you shortly." },
            { label: 'Information', text: "Please see the details below regarding your concern." },
            { label: 'More Info', text: "Kindly provide more information so we can assist you better." },
            { label: 'Forwarded', text: "Your inquiry has been forwarded to the appropriate team." },
            { label: 'Closing', text: "We hope your concern has been resolved. Let us know if you need further assistance." }
        ],
        'Complaint / Report': [
            { label: 'Apology', text: "We apologize for the inconvenience. Your concern is being reviewed." },
            { label: 'Investigating', text: "We are currently investigating the issue you reported." },
            { label: 'Action Taken', text: "Appropriate action has been taken. Thank you for your feedback." },
            { label: 'Resolved', text: "Your concern has been resolved. Please confirm if everything is okay." },
            { label: 'Escalated', text: "This matter has been escalated for further review." }
        ]
    };

    function applyTemplate(text) {
        document.getElementById('admin_reply').value = text;
    }

    function previewAdminFile(input) {
        const preview = document.getElementById('adminFilePreview');
        const img = document.getElementById('adminPreviewImg');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            img.src = reader.result;
            preview.classList.remove('hidden');
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            img.src = "";
            preview.classList.add('hidden');
        }
    }

    function removeAdminFile() {
        const input = document.querySelector('input[name="attachment"]');
        const preview = document.getElementById('adminFilePreview');
        input.value = "";
        preview.classList.add('hidden');
    }

    function openLightbox(src) {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImg');
        const download = document.getElementById('lightboxDownload');
        img.src = src;
        download.href = src;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightboxModal').classList.add('hidden');
        if (!document.getElementById('replyModal').classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    function openReplyModal(message) {
        // Mark as read via AJAX
        if (!message.is_read_by_admin) {
            fetch(`/admin/support/${message.id}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            });
        }

        document.getElementById('modalResidentName').textContent = message.resident.first_name + ' ' + message.resident.last_name;
        document.getElementById('modalCategoryLabel').textContent = message.category;
        document.getElementById('modalResidentAvatar').textContent = message.resident.first_name[0] + message.resident.last_name[0];
        document.getElementById('modalMessage').textContent = message.message;
        document.getElementById('modalDate').textContent = new Date(message.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric' });
        
        // Resident Attachment
        const resAttach = document.getElementById('modalResidentAttachment');
        if (message.resident_attachment) {
            document.getElementById('modalResidentImg').src = `/storage/${message.resident_attachment}`;
            resAttach.classList.remove('hidden');
        } else {
            resAttach.classList.add('hidden');
        }

        const form = document.getElementById('replyForm');
        form.action = `/admin/support/${message.id}/reply`;
        
        const textarea = document.getElementById('admin_reply');
        const submitBtn = document.getElementById('submitBtn');
        const suggestedSection = document.getElementById('suggestedRepliesSection');
        const pillsContainer = document.getElementById('templatePillsContainer');
        const adminAttachSection = document.getElementById('adminAttachmentSection');
        const adminAttachView = document.getElementById('modalAdminAttachmentView');

        // Setup Templates
        pillsContainer.innerHTML = '';
        if (message.status !== 'replied' && categoryTemplates[message.category]) {
            suggestedSection.classList.remove('hidden');
            categoryTemplates[message.category].forEach(template => {
                const pill = document.createElement('button');
                pill.type = 'button';
                pill.className = 'px-4 py-1.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-[#081412] hover:text-white hover:border-[#081412] transition-all shadow-sm uppercase tracking-wider';
                pill.textContent = template.label;
                pill.onclick = () => applyTemplate(template.text);
                pillsContainer.appendChild(pill);
            });
        } else {
            suggestedSection.classList.add('hidden');
        }

        // Setup Read-only / Reply state
        if (message.status === 'replied') {
            document.getElementById('adminReplyContainer').classList.remove('hidden');
            document.getElementById('modalAdminReply').textContent = message.admin_reply;
            
            const repliedAt = message.replied_at ? new Date(message.replied_at) : new Date();
            document.getElementById('modalReplyDate').textContent = repliedAt.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric' });
            
            // Admin Attachment View
            if (message.admin_attachment) {
                document.getElementById('modalAdminImgView').src = `/storage/${message.admin_attachment}`;
                adminAttachView.classList.remove('hidden');
            } else {
                adminAttachView.classList.add('hidden');
            }

            textarea.classList.add('hidden');
            adminAttachSection.classList.add('hidden');
            submitBtn.classList.add('hidden');
            
            // Hide "Type Response" label
            const typeResponseLabel = Array.from(document.querySelectorAll('label')).find(el => el.textContent.includes('Type Response'));
            if (typeResponseLabel) typeResponseLabel.parentElement.classList.add('hidden');
        } else {
            document.getElementById('adminReplyContainer').classList.add('hidden');
            textarea.value = '';
            textarea.classList.remove('hidden');
            textarea.readOnly = false;
            adminAttachSection.classList.remove('hidden');
            removeAdminFile();
            submitBtn.classList.remove('hidden');

            // Show "Type Response" label
            const typeResponseLabel = Array.from(document.querySelectorAll('label')).find(el => el.textContent.includes('Type Response'));
            if (typeResponseLabel) typeResponseLabel.parentElement.classList.remove('hidden');
        }

        document.getElementById('replyModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('replyModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
</script>

<style>
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-zoom-in {
        animation: zoomIn 0.2s ease-out forwards;
    }
</style>
@endsection
