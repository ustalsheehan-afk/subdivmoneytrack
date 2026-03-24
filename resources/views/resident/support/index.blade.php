@extends('resident.layouts.app')

@section('title', 'Messages & Support')
@section('page-title', 'Support')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-5xl mx-auto px-6 py-8 flex flex-col gap-10 pb-24 animate-fade-in">
        
        {{-- Header --}}
        <div class="relative overflow-hidden bg-[#081412] rounded-[32px] p-10 shadow-2xl group">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-1000"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 relative z-10">
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                        <i class="bi bi-chat-left-text-fill text-emerald-400 text-xs"></i>
                        <span class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.2em]">Support Center</span>
                    </div>
                    <h1 class="text-4xl font-black text-white tracking-tight leading-none">Messages & Support</h1>
                    <p class="text-[13px] font-medium text-white/50">
                        We're here to help. Send us a message and we'll get back to you soon.
                    </p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[24px] shadow-sm flex items-center gap-4 animate-fade-in">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="bi bi-check-lg text-xl"></i>
                </div>
                <p class="text-sm font-black text-emerald-800 uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        {{-- SEND MESSAGE SECTION --}}
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden transition-all duration-500 hover:shadow-xl group/form">
            <div class="p-8 border-b border-gray-50 flex items-center gap-4 bg-gray-50/30">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 border border-emerald-400 group-hover/form:scale-110 transition-transform">
                    <i class="bi bi-chat-dots-fill text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight leading-tight">Send a Message</h2>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Average response time: 24 hours</p>
                </div>
            </div>
            
            <form action="{{ route('resident.support.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="w-8 h-px bg-gray-200"></span>
                            01. Select Category
                        </label>
                        <div class="relative">
                            <select name="category" class="w-full p-6 text-sm font-black border-2 border-gray-50 rounded-[24px] focus:border-emerald-500 focus:ring-0 bg-gray-50 hover:bg-white transition-all appearance-none cursor-pointer outline-none" required>
                                <option value="" disabled selected>Select concern type</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Payment Concern">Payment Concern</option>
                                <option value="Maintenance Follow-up">Maintenance Follow-up</option>
                                <option value="Reservation Concern">Reservation Concern</option>
                                <option value="Complaint / Report">Complaint / Report</option>
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    {{-- ATTACHMENT SECTION --}}
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="w-8 h-px bg-gray-200"></span>
                            02. Attachment (Optional)
                        </label>
                        <div class="flex items-center gap-4">
                            <label class="flex-1 cursor-pointer group/upload">
                                <input type="file" name="attachment" class="hidden" accept="image/*" onchange="previewFile(this)">
                                <div class="p-5 rounded-[24px] border-2 border-dashed border-gray-100 bg-gray-50 text-gray-400 group-hover/upload:border-emerald-500/30 group-hover/upload:bg-white group-hover/upload:text-emerald-500 transition-all flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest">
                                    <i class="bi bi-camera-fill text-lg"></i>
                                    <span>Upload Photo</span>
                                </div>
                            </label>
                            <div id="filePreview" class="hidden relative group/preview">
                                <img id="previewImg" src="#" class="w-20 h-20 rounded-[20px] object-cover border-4 border-white shadow-xl">
                                <button type="button" onclick="removeFile()" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 text-white rounded-xl flex items-center justify-center text-sm shadow-lg hover:scale-110 transition-transform">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <span class="w-8 h-px bg-gray-200"></span>
                        03. Your Concern
                    </label>
                    <textarea name="message" rows="4" class="w-full p-8 rounded-[32px] border-2 border-gray-50 bg-gray-50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none resize-none leading-relaxed" placeholder="Type your message or concern here..." required></textarea>
                </div>
                
                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-10 py-5 bg-[#081412] text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_25px_rgba(16,185,129,0.2)] transition-all flex items-center gap-3 group/btn border border-white/5">
                        <span>Send Message</span>
                        <i class="bi bi-send-fill text-emerald-400 group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- MESSAGE HISTORY --}}
        <div class="space-y-6">
            <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center gap-3">
                <span class="w-8 h-px bg-gray-200"></span>
                Message History
            </h3>
            
            <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm divide-y divide-gray-50 overflow-hidden">
                @forelse($messages as $message)
                <div class="group hover:bg-emerald-50/30 transition-all duration-300 cursor-pointer border-l-4 border-transparent hover:border-emerald-500" onclick="openMessageDetail({{ $message->id }})">
                    <div class="p-8 flex items-center justify-between gap-6">
                        <div class="flex items-center gap-6 flex-1 min-w-0">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm border border-gray-50
                                {{ $message->status === 'replied' ? 'bg-emerald-50 text-emerald-500' : 'bg-orange-50 text-orange-500' }}">
                                <i class="bi {{ $message->status === 'replied' ? 'bi-chat-left-check-fill' : 'bi-clock-fill' }} text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0 space-y-1.5">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-black text-gray-900 truncate tracking-tight group-hover:text-emerald-700 transition-colors">{{ $message->category }}</span>
                                    <span class="badge-standard 
                                        {{ $message->status === 'replied' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                        {{ strtoupper($message->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 truncate leading-relaxed font-medium">{{ $message->message }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 flex items-center gap-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $message->created_at->format('M d, Y') }}</p>
                                <p class="text-[9px] font-black text-emerald-400/60 uppercase tracking-widest">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-300 group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-sm">
                                <i class="bi bi-chevron-right text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-24 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-[40px] flex items-center justify-center mx-auto mb-8 text-gray-200 shadow-inner">
                        <i class="bi bi-chat-square-dots text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">No messages yet</h3>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] mt-4">Your conversation history will appear here</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>

{{-- MESSAGE DETAIL MODAL --}}
<div id="messageDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-6 bg-[#081412]/80 backdrop-blur-md">
    <div class="bg-white w-full max-w-2xl rounded-[40px] shadow-2xl overflow-hidden animate-zoom-in relative">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>

        <div class="p-8 border-b border-gray-50 flex items-center justify-between relative z-10 bg-gray-50/50">
            <div class="flex items-center gap-4">
                <div id="modalStatusIcon" class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"></div>
                <div>
                    <h4 id="modalCategory" class="text-lg font-black text-gray-900 tracking-tight"></h4>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Message Thread</p>
                </div>
            </div>
            <button onclick="closeMessageDetail()" class="w-12 h-12 rounded-2xl hover:bg-red-50 text-gray-400 hover:text-red-500 transition-all flex items-center justify-center border border-transparent hover:border-red-100">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <div class="p-10 space-y-10 overflow-y-auto max-h-[75vh] custom-scrollbar relative z-10">
            {{-- User Message --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Your Message</span>
                    <span id="modalDate" class="text-[10px] font-black text-gray-400 uppercase tracking-widest tabular-nums"></span>
                </div>
                <div class="p-8 bg-gray-50 rounded-[32px] border border-gray-100 shadow-inner">
                    <p id="modalMessage" class="text-[15px] text-gray-700 leading-relaxed font-medium"></p>
                    <div id="modalUserAttachment" class="mt-8 hidden">
                        <div class="relative group/modalimg inline-block rounded-[24px] overflow-hidden border-4 border-white shadow-2xl">
                            <img id="modalUserImg" src="#" class="max-w-full h-48 object-cover cursor-pointer hover:scale-105 transition-all duration-700" onclick="openLightbox(this.src)">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/modalimg:opacity-100 transition-opacity pointer-events-none flex items-center justify-center">
                                <i class="bi bi-zoom-in text-white text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Admin Reply --}}
            <div id="adminReplyContainer" class="space-y-4 hidden animate-fade-in">
                <div class="flex items-center justify-between px-2">
                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Office Response</span>
                    <span id="modalReplyDate" class="text-[10px] font-black text-gray-400 uppercase tracking-widest tabular-nums"></span>
                </div>
                <div class="p-8 bg-blue-50/50 border border-blue-100 rounded-[32px] shadow-lg shadow-blue-500/5">
                    <p id="modalAdminReply" class="text-[15px] text-gray-700 leading-relaxed font-medium"></p>
                    <div id="modalAdminAttachment" class="mt-8 hidden">
                        <div class="relative group/modalimg inline-block rounded-[24px] overflow-hidden border-4 border-white shadow-2xl">
                            <img id="modalAdminImg" src="#" class="max-w-full h-48 object-cover cursor-pointer hover:scale-105 transition-all duration-700" onclick="openLightbox(this.src)">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/modalimg:opacity-100 transition-opacity pointer-events-none flex items-center justify-center">
                                <i class="bi bi-zoom-in text-white text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending State --}}
            <div id="pendingReplyState" class="p-10 text-center border-2 border-dashed border-gray-100 rounded-[32px] bg-gray-50/30 hidden">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                    <i class="bi bi-hourglass-split text-orange-400 text-3xl animate-spin-slow"></i>
                </div>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">Waiting for Response</p>
            </div>
        </div>
    </div>
</div>

{{-- LIGHTBOX MODAL --}}
<div id="lightboxModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#081412]/95 backdrop-blur-xl" onclick="closeLightbox()">
    <button class="absolute top-8 right-8 w-14 h-14 bg-white/10 hover:bg-white/20 text-white rounded-2xl flex items-center justify-center transition-all border border-white/10">
        <i class="bi bi-x-lg text-xl"></i>
    </button>
    <img id="lightboxImg" src="#" class="max-w-[90vw] max-h-[80vh] rounded-[32px] shadow-2xl animate-zoom-in object-contain border-4 border-white/10">
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2">
        <a id="lightboxDownload" href="#" download class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-black text-[11px] font-black rounded-2xl flex items-center gap-3 transition-all shadow-2xl shadow-emerald-500/20 uppercase tracking-widest">
            <i class="bi bi-download text-lg"></i>
            <span>Download Image</span>
        </a>
    </div>
</div>

<style>
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-zoom-in { animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    .animate-spin-slow { animation: spin 3s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    
    .badge-standard {
        @apply inline-flex items-center px-4 py-1.5 text-[10px] font-black rounded-xl uppercase tracking-widest shadow-sm;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>

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
                    statusIcon.className = 'w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm';
                    statusIcon.innerHTML = '<i class="bi bi-check-circle-fill text-xl"></i>';
                    
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
                    statusIcon.className = 'w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center border border-orange-100 shadow-sm';
                    statusIcon.innerHTML = '<i class="bi bi-clock-fill text-xl"></i>';
                    
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!document.getElementById('lightboxModal').classList.contains('hidden')) {
                closeLightbox();
            } else {
                closeMessageDetail();
            }
        }
    });
</script>
@endsection
