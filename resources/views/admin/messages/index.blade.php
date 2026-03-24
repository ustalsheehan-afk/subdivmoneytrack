@extends('layouts.admin')

@section('title', 'Resident Support')
@section('page-title', 'Resident Support')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="residentSupport()">
    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Resident Support
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Real-time communication and resident inquiry center.
                </p>
            </div>

            {{-- Status Pills --}}
            <div class="flex items-center gap-2 p-1.5 bg-gray-100 rounded-[20px] border border-gray-200 shadow-inner">
                <template x-for="s in ['all', 'pending', 'replied', 'closed']">
                    <button @click="statusFilter = s; fetchThreads()" 
                        :class="statusFilter === s ? 'bg-brand-darker text-brand-accent shadow-lg' : 'text-gray-500 hover:text-brand-accent hover:bg-brand-darker hover:shadow-lg'" 
                        class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all"
                        x-text="s">
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TOOLBAR SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        {{-- Search Bar --}}
        <div class="flex-1 max-w-md">
            <div class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input type="text" x-model="search" @input.debounce.300ms="fetchThreads()" 
                    placeholder="Search resident or subject..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
            </div>
        </div>

        {{-- Refresh Button --}}
        <button @click="fetchThreads()" class="btn-secondary h-12">
            <i class="bi bi-arrow-clockwise" :class="loading ? 'animate-spin' : ''"></i>
            Refresh Inbox
        </button>
    </div>

    {{-- ===================== --}}
    {{-- MAIN CONTENT: SPLIT PANEL --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-10 gap-8 min-h-[750px]">
        
        {{-- LEFT PANEL: THREAD LIST (35%) --}}
        <div class="lg:col-span-3 flex flex-col gap-6">
            <div class="glass-card flex-1 overflow-hidden flex flex-col shadow-xl">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Active Threads</span>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100" x-text="threads.length"></span>
                </div>

                <div class="flex-1 overflow-y-auto divide-y divide-gray-50 custom-scrollbar">
                    <template x-for="thread in threads" :key="thread.id">
                        <div @click="loadThread(thread.id)" 
                             :class="selectedThreadId == thread.id ? 'bg-emerald-50/50 border-emerald-500' : 'hover:bg-gray-50 border-transparent'"
                             class="p-6 cursor-pointer transition-all relative group border-l-4">
                            
                            <div class="flex items-center gap-4 mb-4">
                                <div class="relative shrink-0">
                                    <div class="w-14 h-14 rounded-[20px] bg-white border border-gray-100 flex items-center justify-center text-sm font-black text-emerald-600 shadow-sm group-hover:scale-110 transition-all duration-500" x-text="thread.initials"></div>
                                    <template x-if="thread.unread">
                                        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center shadow-lg border border-emerald-50">
                                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                        </div>
                                    </template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="text-sm font-black text-gray-900 truncate group-hover:text-emerald-700 transition-colors uppercase tracking-tight" x-text="thread.resident_name"></h4>
                                        <span class="text-[9px] font-bold text-gray-300 whitespace-nowrap uppercase tracking-widest" x-text="thread.time"></span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50/50 px-2 py-0.5 rounded border border-emerald-100/50" x-text="thread.category"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] text-gray-500 font-medium truncate max-w-[70%] italic" x-text="thread.preview"></p>
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full" 
                                        :class="{
                                            'bg-amber-500 animate-pulse': thread.status === 'pending',
                                            'bg-emerald-500': thread.status === 'replied',
                                            'bg-gray-300': thread.status === 'closed'
                                        }"></div>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest" x-text="thread.status"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="threads.length === 0">
                        <div class="p-20 text-center opacity-40">
                            <div class="w-20 h-20 bg-gray-50 rounded-[32px] flex items-center justify-center mx-auto mb-6 shadow-inner">
                                <i class="bi bi-chat-dots text-4xl text-gray-300"></i>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">No conversations found</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: MESSAGE VIEW (65%) --}}
        <div class="lg:col-span-7">
            <div class="glass-card h-full flex flex-col relative overflow-hidden shadow-2xl border-emerald-500/10">
                <template x-if="!selectedThreadId">
                    <div class="flex-1 flex flex-col items-center justify-center p-20 text-center group">
                        <div class="relative mb-8">
                            <div class="w-24 h-24 bg-gray-50 rounded-[40px] flex items-center justify-center border border-gray-100 shadow-inner group-hover:scale-110 transition-all duration-700">
                                <i class="bi bi-chat-left-dots text-5xl text-gray-200"></i>
                            </div>
                            <div class="absolute -right-2 -bottom-2 w-10 h-10 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg border-4 border-white animate-bounce">
                                <i class="bi bi-plus-lg"></i>
                            </div>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-3">Select a Conversation</h2>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Choose a resident from the list to start messaging</p>
                    </div>
                </template>

                <template x-if="selectedThreadId">
                    <div class="flex-1 flex flex-col h-full animate-fade-in">
                        {{-- Thread Header --}}
                        <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 rounded-[24px] bg-gray-900 text-brand-accent flex items-center justify-center text-xl font-black italic shadow-2xl shadow-emerald-900/20 border border-white/10">
                                    <span x-text="currentThread.initials"></span>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight" x-text="currentThread.name"></h2>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full shadow-sm" x-text="currentThread.category"></span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full" :class="currentThread.status === 'pending' ? 'bg-amber-500' : 'bg-emerald-500'"></div>
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest" x-text="currentThread.status"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button @click="closeThread" x-show="currentThread.status !== 'closed'" 
                                class="btn-secondary text-red-500 hover:bg-red-50 hover:text-red-600 border-red-50">
                                <i class="bi bi-check-circle"></i>
                                Close Thread
                            </button>
                        </div>

                        {{-- Chat Bubbles --}}
                        <div class="flex-1 p-10 overflow-y-auto space-y-10 bg-gray-50/10 custom-scrollbar" id="threadContainer">
                            <template x-for="msg in currentThread.messages" :key="msg.id">
                                <div class="flex" :class="msg.is_admin ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-[85%] group">
                                        <div class="flex items-center gap-3 mb-3 px-4" :class="msg.is_admin ? 'flex-row-reverse' : ''">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest" x-text="msg.is_admin ? 'Support Admin' : currentThread.firstName"></span>
                                            <span class="text-[9px] text-gray-300 font-bold uppercase tracking-widest" x-text="msg.time"></span>
                                        </div>
                                        <div :class="msg.is_admin ? 'bg-gray-900 text-white rounded-tr-none shadow-2xl shadow-emerald-900/10' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none shadow-md'"
                                             class="px-10 py-6 rounded-[32px] text-base font-medium transition-all hover:shadow-xl leading-relaxed relative">
                                            
                                            <p x-text="msg.body" class="whitespace-pre-wrap"></p>
                                            
                                            <template x-if="msg.attachment">
                                                <div class="mt-6 pt-6 border-t" :class="msg.is_admin ? 'border-white/10' : 'border-gray-50'">
                                                    <a :href="msg.attachment" target="_blank" class="inline-flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-xl text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:bg-emerald-50 transition-all border border-gray-100">
                                                        <i class="bi bi-paperclip text-sm"></i> 
                                                        View Attachment
                                                    </a>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Reply Box --}}
                        <div class="p-8 bg-white border-t border-gray-100 space-y-6">
                            <div class="relative group/reply">
                                <textarea x-model="replyBody" rows="4" 
                                    @keydown.enter.prevent="if(!event.shiftKey) sendReply()"
                                    placeholder="Type your professional response here..." 
                                    class="w-full pl-10 pr-48 py-8 rounded-[32px] bg-gray-50 border border-gray-200 text-base font-medium focus:bg-white focus:border-emerald-500 focus:ring-[12px] focus:ring-emerald-500/5 transition-all outline-none resize-none placeholder:text-gray-300 shadow-inner"></textarea>
                                
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center gap-4">
                                    {{-- Quick Response Tool --}}
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" type="button" class="w-14 h-14 flex items-center justify-center rounded-[20px] bg-white border border-gray-200 text-gray-400 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm" title="Quick Responses">
                                            <i class="bi bi-lightning-charge-fill text-xl"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute bottom-full right-0 mb-6 w-80 bg-gray-900 rounded-[32px] shadow-2xl border border-white/10 p-6 z-50 animate-zoom-in">
                                            <div class="flex items-center gap-3 mb-6">
                                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                                    <i class="bi bi-lightning-charge text-emerald-500 text-sm"></i>
                                                </div>
                                                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Smart Responses</p>
                                            </div>
                                            <div class="space-y-3">
                                                <button @click="replyBody = 'Thank you for reaching out. We are currently reviewing your concern and will get back to you shortly.'; open = false" class="w-full text-left p-5 rounded-2xl bg-white/5 hover:bg-white/10 text-[11px] text-gray-300 font-bold transition-all border border-white/5">Acknowledge Receipt</button>
                                                <button @click="replyBody = 'Your payment has been successfully verified. Thank you for your cooperation.'; open = false" class="w-full text-left p-5 rounded-2xl bg-white/5 hover:bg-white/10 text-[11px] text-gray-300 font-bold transition-all border border-white/5">Payment Verified</button>
                                                <button @click="replyBody = 'Could you please provide additional details or a photo of the issue so we can assist you better?'; open = false" class="w-full text-left p-5 rounded-2xl bg-white/5 hover:bg-white/10 text-[11px] text-gray-300 font-bold transition-all border border-white/5">Request Details</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button @click="sendReply" :disabled="!replyBody.trim() || sending" 
                                        class="px-10 h-16 bg-gray-900 text-brand-accent rounded-[20px] text-[11px] font-black uppercase tracking-widest hover:shadow-2xl hover:shadow-emerald-900/20 disabled:opacity-50 transition-all flex items-center gap-4 active:scale-95 group/btn">
                                        <span x-show="!sending">Send Reply</span>
                                        <i x-show="!sending" class="bi bi-send-fill group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                                        <i x-show="sending" class="bi bi-arrow-repeat animate-spin"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between px-6">
                                <div class="flex items-center gap-4">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                        <kbd class="px-2 py-1 bg-gray-100 rounded text-[8px]">Enter</kbd> to Send
                                    </span>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                        <kbd class="px-2 py-1 bg-gray-100 rounded text-[8px]">Shift + Enter</kbd> for New Line
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function residentSupport() {
        return {
            tab: 'inbox',
            statusFilter: 'all',
            search: '',
            threads: [],
            selectedThreadId: null,
            currentThread: null,
            replyBody: '',
            sending: false,

            init() {
                this.fetchThreads();
            },

            async fetchThreads() {
                const response = await fetch(`{{ route('admin.messages.index') }}?status=${this.statusFilter}&search=${this.search}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                this.threads = data.threads;
            },

            async loadThread(id) {
                this.selectedThreadId = id;
                const response = await fetch(`{{ url('admin/messages/support') }}/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.currentThread = await response.json();
                this.scrollToBottom();
            },

            async sendReply() {
                if (!this.replyBody.trim() || this.sending) return;
                this.sending = true;

                try {
                    const response = await fetch(`{{ url('admin/messages/support') }}/${this.selectedThreadId}/reply`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ body: this.replyBody })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        this.currentThread.messages.push(data.message);
                        this.currentThread.status = 'replied';
                        this.replyBody = '';
                        this.scrollToBottom();
                        this.fetchThreads(); // Refresh list to update status/preview
                    }
                } finally {
                    this.sending = false;
                }
            },

            async closeThread() {
                if (!confirm('Are you sure you want to close this conversation?')) return;
                
                const response = await fetch(`{{ url('admin/messages/support') }}/${this.selectedThreadId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: 'closed' })
                });

                if (response.ok) {
                    this.currentThread.status = 'closed';
                    this.fetchThreads();
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const el = document.getElementById('threadContainer');
                    if (el) el.scrollTop = el.scrollHeight;
                });
            }
        }
    }
</script>
@endpush
@endsection
