@extends('resident.layouts.app')

@section('title', 'Messages & Support')
@section('page-title', 'Support')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
    
    {{-- SEND MESSAGE SECTION --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-slate-50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="bi bi-chat-left-text text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-tight">Send a Message</h2>
                <p class="text-xs text-slate-500 font-medium">We usually respond within 24 hours.</p>
            </div>
        </div>
        
        <form action="{{ route('resident.support.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Category</label>
                    <select name="category" class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-sm font-medium focus:bg-white focus:border-blue-500 transition-all outline-none appearance-none cursor-pointer" required>
                        <option value="" disabled selected>Select category</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Payment Concern">Payment Concern</option>
                        <option value="Maintenance Follow-up">Maintenance Follow-up</option>
                        <option value="Reservation Concern">Reservation Concern</option>
                        <option value="Complaint / Report">Complaint / Report</option>
                    </select>
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Your Concern</label>
                <textarea name="message" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-sm font-medium focus:bg-white focus:border-blue-500 transition-all outline-none resize-none" placeholder="Type your message or concern..." required></textarea>
            </div>

            {{-- ATTACHMENT SECTION --}}
            <div class="space-y-3">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="bi bi-paperclip"></i> Attachment (Optional)
                </label>
                <div class="flex items-center gap-4">
                    <label class="shrink-0 cursor-pointer group">
                        <input type="file" name="attachment" class="hidden" accept="image/*" onchange="previewFile(this)">
                        <div class="px-5 py-2.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 group-hover:border-blue-400 group-hover:text-blue-500 transition-all flex items-center gap-2 text-xs font-bold">
                            <i class="bi bi-image"></i>
                            <span>Attach Photo</span>
                        </div>
                    </label>
                    <div id="filePreview" class="hidden relative group">
                        <img id="previewImg" src="#" class="w-12 h-12 rounded-lg object-cover border border-slate-200 shadow-sm">
                        <button type="button" onclick="removeFile()" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-[#385780] text-white text-sm font-bold rounded-xl hover:bg-[#2B3A4F] transition-all shadow-lg shadow-blue-900/10 flex items-center gap-2">
                    <span>Send Message</span>
                    <i class="bi bi-send-fill text-xs"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- MESSAGE HISTORY --}}
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em]">Message History</h3>
        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-50 overflow-hidden">
            @forelse($messages as $message)
            <div class="group hover:bg-slate-50 transition-colors cursor-pointer" onclick="openMessageDetail({{ $message->id }})">
                <div class="p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 
                            {{ $message->status === 'replied' ? 'bg-emerald-50 text-emerald-500' : 'bg-orange-50 text-orange-500' }}">
                            <i class="bi {{ $message->status === 'replied' ? 'bi-check-circle' : 'bi-clock' }} text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-bold text-slate-900 truncate">{{ $message->category }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest 
                                    {{ $message->status === 'replied' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                    {{ strtoupper($message->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 truncate leading-relaxed font-medium">{{ $message->message }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-xs font-bold text-slate-400 mb-1">{{ $message->created_at->format('M d, Y') }}</div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-chat-dots text-slate-300 text-3xl"></i>
                </div>
                <p class="text-sm text-slate-500 font-medium">No messages found.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>
</div>

{{-- MESSAGE DETAIL MODAL --}}
<div id="messageDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div id="modalStatusIcon" class="w-8 h-8 rounded-lg flex items-center justify-center"></div>
                <h4 id="modalCategory" class="font-bold text-slate-900"></h4>
            </div>
            <button onclick="closeMessageDetail()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        
        <div class="p-8 space-y-8 overflow-y-auto max-h-[70vh] custom-scrollbar">
            {{-- User Message --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Your Message</span>
                    <span id="modalDate" class="text-[10px] font-bold text-slate-400"></span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl text-sm text-slate-700 leading-relaxed font-medium">
                    <p id="modalMessage"></p>
                    <div id="modalUserAttachment" class="mt-4 hidden">
                        <img id="modalUserImg" src="#" class="max-w-full rounded-xl border border-slate-100 shadow-sm cursor-pointer hover:brightness-95 transition-all" onclick="openLightbox(this.src)">
                    </div>
                </div>
            </div>

            {{-- Admin Reply --}}
            <div id="adminReplyContainer" class="space-y-3 hidden">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Office Response</span>
                    <span id="modalReplyDate" class="text-[10px] font-bold text-slate-400"></span>
                </div>
                <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-2xl text-sm text-slate-700 leading-relaxed font-medium">
                    <p id="modalAdminReply"></p>
                    <div id="modalAdminAttachment" class="mt-4 hidden">
                        <img id="modalAdminImg" src="#" class="max-w-full rounded-xl border border-blue-100 shadow-sm cursor-pointer hover:brightness-95 transition-all" onclick="openLightbox(this.src)">
                    </div>
                </div>
            </div>

            {{-- Pending State --}}
            <div id="pendingReplyState" class="p-6 text-center border-2 border-dashed border-slate-100 rounded-2xl hidden">
                <i class="bi bi-hourglass-split text-slate-300 text-2xl mb-2"></i>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Waiting for Response</p>
            </div>
        </div>
    </div>
</div>

{{-- LIGHTBOX MODAL --}}
<div id="lightboxModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/95 backdrop-blur-md" onclick="closeLightbox()">
    <button class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all">
        <i class="bi bi-x-lg"></i>
    </button>
    <img id="lightboxImg" src="#" class="max-w-[90vw] max-h-[85vh] rounded-xl shadow-2xl animate-zoom-in object-contain">
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-4">
        <a id="lightboxDownload" href="#" download class="px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-full flex items-center gap-2 transition-all">
            <i class="bi bi-download"></i>
            <span>Download</span>
        </a>
    </div>
</div>

<script>
    function previewFile(input) {
        const preview = document.getElementById('filePreview');
        const img = document.getElementById('previewImg');
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

    function removeFile() {
        const input = document.querySelector('input[name="attachment"]');
        const preview = document.getElementById('filePreview');
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
        if (!document.getElementById('messageDetailModal').classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    function openMessageDetail(id) {
        fetch(`/resident/support/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalCategory').textContent = data.category;
                document.getElementById('modalMessage').textContent = data.message;
                document.getElementById('modalDate').textContent = new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric' });
                
                // User Attachment
                const userAttach = document.getElementById('modalUserAttachment');
                if (data.resident_attachment) {
                    document.getElementById('modalUserImg').src = `/storage/${data.resident_attachment}`;
                    userAttach.classList.remove('hidden');
                } else {
                    userAttach.classList.add('hidden');
                }

                const statusIcon = document.getElementById('modalStatusIcon');
                if (data.status === 'replied') {
                    statusIcon.className = 'w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center';
                    statusIcon.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
                    
                    document.getElementById('adminReplyContainer').classList.remove('hidden');
                    document.getElementById('modalAdminReply').textContent = data.admin_reply;
                    document.getElementById('modalReplyDate').textContent = new Date(data.replied_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric' });
                    document.getElementById('pendingReplyState').classList.add('hidden');

                    // Admin Attachment
                    const adminAttach = document.getElementById('modalAdminAttachment');
                    if (data.admin_attachment) {
                        document.getElementById('modalAdminImg').src = `/storage/${data.admin_attachment}`;
                        adminAttach.classList.remove('hidden');
                    } else {
                        adminAttach.classList.add('hidden');
                    }
                } else {
                    statusIcon.className = 'w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center';
                    statusIcon.innerHTML = '<i class="bi bi-clock-fill"></i>';
                    
                    document.getElementById('adminReplyContainer').classList.add('hidden');
                    document.getElementById('pendingReplyState').classList.remove('hidden');
                }

                document.getElementById('messageDetailModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
    }

    function closeMessageDetail() {
        document.getElementById('messageDetailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMessageDetail();
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
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>
@endsection
