@extends('layouts.admin')

@section('title', 'Support Messages')
@section('page-title', 'Support')

@section('content')
<div class="space-y-8 pb-20">
    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center">
                <i class="bi bi-clock-history text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Pending</p>
                <h4 class="text-2xl font-black text-gray-900">{{ $summary['pending'] }}</h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <i class="bi bi-eye text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Unread</p>
                <h4 class="text-2xl font-black text-gray-900">{{ $summary['unread'] }}</h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                <i class="bi bi-check2-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Replied</p>
                <h4 class="text-2xl font-black text-gray-900">{{ $summary['replied'] }}</h4>
            </div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.support.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resident or message..." 
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm focus:bg-white focus:border-blue-500 transition-all outline-none">
            </div>
            <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm focus:bg-white focus:border-blue-500 transition-all outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
            </select>
            <select name="category" class="px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm focus:bg-white focus:border-blue-500 transition-all outline-none">
                <option value="">All Categories</option>
                <option value="General Inquiry" {{ request('category') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                <option value="Payment Concern" {{ request('category') == 'Payment Concern' ? 'selected' : '' }}>Payment Concern</option>
                <option value="Maintenance Follow-up" {{ request('category') == 'Maintenance Follow-up' ? 'selected' : '' }}>Maintenance Follow-up</option>
                <option value="Reservation Concern" {{ request('category') == 'Reservation Concern' ? 'selected' : '' }}>Reservation Concern</option>
                <option value="Complaint / Report" {{ request('category') == 'Complaint / Report' ? 'selected' : '' }}>Complaint / Report</option>
            </select>
            <div class="flex gap-2">
                <select name="date" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm focus:bg-white focus:border-blue-500 transition-all outline-none">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    <i class="bi bi-filter"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- MESSAGE TABLE --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Resident</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Category</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Message</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($messages as $message)
                    <tr class="group hover:bg-gray-50/50 transition-colors cursor-pointer {{ !$message->is_read_by_admin ? 'bg-blue-50/30' : '' }}" 
                        onclick="openReplyModal({{ json_encode($message) }})">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                    {{ substr($message->resident->first_name, 0, 1) }}{{ substr($message->resident->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        {{ $message->resident->full_name }}
                                        @if(!$message->is_read_by_admin)
                                            <span class="w-2 h-2 rounded-full bg-blue-500 shadow-sm shadow-blue-200 animate-pulse"></span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] font-medium text-gray-400 tracking-wider">B{{ $message->resident->block }} / L{{ $message->resident->lot }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">
                                {{ $message->category }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-500 max-w-xs truncate font-medium">{{ $message->message }}</td>
                        <td class="px-8 py-6">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border
                                {{ $message->status === 'replied' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                                {{ $message->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-[11px] font-bold text-gray-400 uppercase">{{ $message->created_at->format('M d, H:i') }}</td>
                        <td class="px-8 py-6 text-right">
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-blue-600 hover:text-white text-gray-400 transition-all flex items-center justify-center">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-gray-400 font-medium tracking-wide">No support messages match your filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 bg-gray-50/50">
            {{ $messages->links() }}
        </div>
    </div>
</div>

{{-- REPLY MODAL --}}
<div id="replyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div id="modalResidentAvatar" class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-lg shadow-sm"></div>
                <div>
                    <h4 id="modalResidentName" class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1"></h4>
                    <p id="modalCategoryLabel" class="text-[10px] font-bold text-blue-500 uppercase tracking-widest"></p>
                </div>
            </div>
            <button onclick="closeModal()" class="w-10 h-10 rounded-xl hover:bg-gray-100 text-gray-400 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
            {{-- Resident Message --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident Message</span>
                    <span id="modalDate" class="text-[10px] font-bold text-gray-400"></span>
                </div>
                <div class="p-6 bg-gray-50 border border-gray-100 rounded-[1.5rem] text-sm text-gray-700 leading-relaxed font-medium shadow-inner">
                    <p id="modalMessage"></p>
                    <div id="modalResidentAttachment" class="mt-4 hidden">
                        <img id="modalResidentImg" src="#" class="max-w-[150px] rounded-xl border border-gray-100 shadow-sm cursor-pointer hover:brightness-95 transition-all" onclick="openLightbox(this.src)">
                    </div>
                </div>
            </div>

            {{-- Admin Reply --}}
            <div id="adminReplyContainer" class="space-y-3 hidden">
                <div class="flex items-center justify-between px-1">
                    <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Office Response</span>
                    <span id="modalReplyDate" class="text-[10px] font-bold text-gray-400"></span>
                </div>
                <div class="p-6 bg-blue-50/30 border border-blue-100 rounded-[1.5rem] text-sm text-gray-700 leading-relaxed font-medium">
                    <p id="modalAdminReply"></p>
                    <div id="modalAdminAttachmentView" class="mt-4 hidden">
                        <img id="modalAdminImgView" src="#" class="max-w-[150px] rounded-xl border border-blue-100 shadow-sm cursor-pointer hover:brightness-95 transition-all" onclick="openLightbox(this.src)">
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
                            <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Suggested Replies</label>
                            <span class="text-[10px] font-bold text-gray-400 italic">Click a pill to auto-fill</span>
                        </div>
                        <div id="templatePillsContainer" class="flex flex-wrap gap-2 min-h-[40px]">
                            <!-- Pills injected via JS -->
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1 pt-2">
                        <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Office Response</label>
                    </div>
                    <textarea name="admin_reply" id="admin_reply" rows="5" 
                        class="w-full px-6 py-5 rounded-[1.5rem] border border-gray-100 bg-gray-50 text-sm font-medium focus:bg-white focus:border-blue-500 transition-all outline-none resize-none shadow-sm" 
                        placeholder="Type your response here..." required></textarea>
                </div>

                {{-- ATTACHMENT SECTION --}}
                <div id="adminAttachmentSection" class="space-y-3">
                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest flex items-center gap-2">
                        <i class="bi bi-paperclip"></i> Attachment (Optional)
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="shrink-0 cursor-pointer group">
                            <input type="file" name="attachment" class="hidden" accept="image/*" onchange="previewAdminFile(this)">
                            <div class="px-5 py-2.5 rounded-xl border-2 border-dashed border-gray-200 text-gray-400 group-hover:border-blue-400 group-hover:text-blue-500 transition-all flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider">
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
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl text-gray-400 text-sm font-bold hover:bg-gray-50 transition-all uppercase tracking-widest">Cancel</button>
                    <button type="submit" id="submitBtn" class="px-12 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 uppercase tracking-widest">Send Response</button>
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
            suggestedSection.style.display = 'block';
            categoryTemplates[message.category].forEach(template => {
                const pill = document.createElement('button');
                pill.type = 'button';
                pill.className = 'px-4 py-1.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm';
                pill.textContent = template.label;
                pill.onclick = () => applyTemplate(template.text);
                pillsContainer.appendChild(pill);
            });
        } else {
            suggestedSection.style.display = 'none';
        }

        // Setup Read-only / Reply state
        if (message.status === 'replied') {
            document.getElementById('adminReplyContainer').classList.remove('hidden');
            document.getElementById('modalAdminReply').textContent = message.admin_reply;
            document.getElementById('modalReplyDate').textContent = new Date(message.replied_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric' });
            
            // Admin Attachment View
            if (message.admin_attachment) {
                document.getElementById('modalAdminImgView').src = `/storage/${message.admin_attachment}`;
                adminAttachView.classList.remove('hidden');
            } else {
                adminAttachView.classList.add('hidden');
            }

            textarea.style.display = 'none';
            adminAttachSection.style.display = 'none';
            submitBtn.style.display = 'none';
            document.querySelector('label[for="admin_reply"]')?.parentElement.style.setProperty('display', 'none');
            document.getElementById('replyModal').querySelector('label[class*="text-blue-500"]').style.display = 'none';
        } else {
            document.getElementById('adminReplyContainer').classList.add('hidden');
            textarea.value = '';
            textarea.style.display = 'block';
            textarea.readOnly = false;
            textarea.classList.remove('bg-gray-100');
            adminAttachSection.style.display = 'block';
            removeAdminFile();
            submitBtn.style.display = 'block';
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
