<?php $__env->startSection('title', 'Resident Support'); ?>
<?php $__env->startSection('page-title', 'Resident Support'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in" x-data="residentSupport()">
    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Resident Support
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Real-time communication and resident inquiry center.
                </p>
            </div>

            
            <div class="flex items-center gap-2 p-1 bg-gray-100 rounded-2xl border border-gray-200 shadow-inner">
                <template x-for="s in ['all', 'pending', 'replied', 'closed']">
                    <button @click="statusFilter = s; fetchThreads()" 
                        :class="statusFilter === s ? 'bg-gray-900 text-emerald-400 shadow-lg' : 'text-gray-500 hover:text-emerald-600'" 
                        class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                        x-text="s">
                    </button>
                </template>
            </div>
        </div>
    </div>

    
    
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-280px)] min-h-[600px] items-stretch">
        
        
        <div class="lg:col-span-4 flex flex-col h-full overflow-hidden">
            <div class="glass-card flex-1 overflow-hidden flex flex-col shadow-xl border border-gray-100">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <div class="relative group">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs group-focus-within:text-emerald-500 transition-colors"></i>
                        <input type="text" x-model="search" @input.debounce.300ms="fetchThreads()" 
                               placeholder="Search resident or subject..." 
                               class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto divide-y divide-gray-50 custom-scrollbar">
                    <template x-for="thread in threads" :key="thread.id">
                        <div @click="loadThread(thread.id)" 
                             :class="selectedThreadId == thread.id ? 'bg-emerald-50/50 border-emerald-500' : 'hover:bg-gray-50 border-transparent'"
                             class="p-6 cursor-pointer transition-all relative group border-l-4">
                            
                            <div class="flex items-center gap-4 mb-3">
                                <div class="relative shrink-0">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-900 flex items-center justify-center text-sm font-black text-emerald-400 shadow-lg shadow-gray-900/10" x-text="thread.initials"></div>
                                    <template x-if="thread.unread">
                                        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center shadow-lg border border-emerald-50">
                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                        </div>
                                    </template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="text-sm font-black text-gray-900 truncate uppercase tracking-tight" x-text="thread.resident_name"></h4>
                                        <span class="text-[9px] font-bold text-gray-300 whitespace-nowrap uppercase" x-text="thread.time"></span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span x-show="thread.priority !== 'medium'" :class="{
                                            'bg-red-50 text-red-600 border-red-100': thread.priority === 'urgent',
                                            'bg-amber-50 text-amber-600 border-amber-100': thread.priority === 'high',
                                            'bg-blue-50 text-blue-600 border-blue-100': thread.priority === 'medium',
                                            'bg-gray-50 text-gray-600 border-gray-100': thread.priority === 'low'
                                        }" class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full border" x-text="thread.priority"></span>
                                        
                                        
                                        <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50/50 px-2 py-0.5 rounded-full border border-emerald-100/50" x-text="thread.category"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-xs text-gray-500 font-medium truncate" :class="thread.unread ? 'font-bold text-gray-900' : ''" x-text="thread.preview"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-8 flex flex-col h-full overflow-hidden">
            <div class="glass-card flex-1 flex flex-col relative overflow-hidden shadow-2xl border border-gray-100 h-full">
                <template x-if="!selectedThreadId">
                    <div class="flex-1 flex flex-col items-center justify-center p-20 text-center opacity-40">
                        <div class="w-24 h-24 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                            <i class="bi bi-chat-left-text text-4xl text-gray-300"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase tracking-tight text-gray-400">Select a Conversation</h2>
                        <p class="text-sm text-gray-400 mt-2">Choose a resident from the list to start messaging</p>
                    </div>
                </template>

                <template x-if="selectedThreadId && loadingThread">
                    <div class="flex-1 flex flex-col items-center justify-center p-20 text-center">
                        <div class="w-16 h-16 border-4 border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin mb-6"></div>
                        <h2 class="text-lg font-black uppercase tracking-tight text-gray-400 animate-pulse">Loading Conversation...</h2>
                    </div>
                </template>

                <template x-if="selectedThreadId && currentThread">
                    <div class="flex-1 flex flex-col h-full overflow-hidden animate-fade-in" x-init="scrollToBottom()">
                        
                        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-900 text-emerald-400 flex items-center justify-center text-lg font-black italic shadow-xl">
                                    <span x-text="currentThread.initials"></span>
                                </div>
                                <div>
                                    <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight" x-text="currentThread.name"></h2>
                                    <div class="flex items-center gap-3 mt-0.5">
                                        <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100" x-text="currentThread.category"></span>
                                        <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest" x-text="currentThread.status"></span>
                                        <template x-if="currentThread.assigned_to">
                                            <div class="flex items-center gap-1.5 ml-1">
                                                <i class="bi bi-person-check text-emerald-500 text-[10px]"></i>
                                                <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest" x-text="currentThread.assigned_to"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <template x-for="action in currentThread.actions" :key="action.action">
                                    <button @click="performAction(action.action)" 
                                            :class="`bg-${action.color}-50 text-${action.color}-600 border-${action.color}-100 hover:bg-${action.color}-100 shadow-sm`"
                                            class="px-3 py-1.5 rounded-xl text-[8px] font-black uppercase tracking-widest border transition-all active:scale-95">
                                        <span x-text="action.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        
                        <div class="px-6 py-2 bg-white border-b border-gray-100 flex items-center gap-4 overflow-x-auto no-scrollbar shrink-0">
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Resident Context:</span>
                            </div>
                            
                            
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 rounded-lg border border-gray-100 shrink-0">
                                <i class="bi bi-house-door text-gray-400 text-[10px]"></i>
                                <span class="text-[9px] font-bold text-gray-700" x-text="currentThread.unit"></span>
                            </div>

                            
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg border shrink-0" 
                                 :class="currentThread.resident_context.payment_status === 'Good Standing' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-red-50 border-red-100 text-red-700'">
                                <i class="bi text-[10px]" :class="currentThread.resident_context.payment_status === 'Good Standing' ? 'bi-check-circle' : 'bi-exclamation-circle'"></i>
                                <span class="text-[9px] font-black uppercase tracking-tight" x-text="currentThread.resident_context.payment_status"></span>
                            </div>

                            
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 rounded-lg border border-gray-100 shrink-0" x-show="currentThread.resident_context.total_balance > 0">
                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Balance:</span>
                                <span class="text-[9px] font-black text-red-600" x-text="'₱' + currentThread.resident_context.total_balance.toLocaleString()"></span>
                            </div>

                            
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 rounded-lg border border-gray-100 shrink-0">
                                <i class="bi bi-tools text-gray-400 text-[10px]"></i>
                                <span class="text-[9px] font-bold text-gray-600" x-text="currentThread.resident_context.past_requests_count + ' Requests'"></span>
                            </div>

                            
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg border shrink-0"
                                 :class="currentThread.resident_context.violations_count > 0 ? 'bg-amber-50 border-amber-100 text-amber-700' : 'bg-gray-50 border-gray-100 text-gray-400'">
                                <i class="bi bi-shield-exclamation text-[10px]"></i>
                                <span class="text-[9px] font-bold" x-text="currentThread.resident_context.violations_count + ' Violations'"></span>
                            </div>
                        </div>

                        
                        <div class="flex-1 p-6 overflow-y-auto space-y-6 bg-gray-50/10 custom-scrollbar" id="threadContainer">
                            <template x-for="msg in currentThread.messages" :key="msg.id">
                                <div class="flex flex-col" :class="msg.is_admin ? 'items-end' : 'items-start'">
                                    <template x-if="msg.is_internal">
                                        <div class="w-full flex justify-center my-4">
                                            <div class="px-5 py-2 bg-amber-50 border border-amber-100 rounded-full flex items-center gap-3 shadow-sm">
                                                <i class="bi bi-shield-lock-fill text-amber-600 text-xs"></i>
                                                <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest">Internal Note: <span x-text="msg.body" class="font-bold"></span></span>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <template x-if="!msg.is_internal">
                                        <div class="max-w-[85%]">
                                            <div class="flex items-center gap-2 mb-1 px-2" :class="msg.is_admin ? 'flex-row-reverse' : ''">
                                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest" x-text="msg.is_admin ? 'Support Admin' : currentThread.firstName"></span>
                                                <span class="text-[7px] text-gray-300 font-bold" x-text="msg.time"></span>
                                            </div>
                                            <div :class="msg.is_admin ? 'bg-gray-900 text-white rounded-tr-none shadow-gray-900/10' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none shadow-sm'"
                                                 class="px-5 py-3 rounded-2xl text-xs font-medium shadow-lg leading-relaxed">
                                                <p x-text="msg.body" class="whitespace-pre-wrap"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        
                        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex flex-col gap-4 shrink-0 overflow-hidden">
                            
                            <div class="relative group">
                                <div class="overflow-x-auto whitespace-nowrap flex gap-3 no-scrollbar scroll-smooth pb-1" id="suggestionScroll">
                                    <div class="flex items-center gap-2 opacity-50 shrink-0">
                                        <i class="bi bi-stars text-emerald-500 text-sm"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Smart Replies:</span>
                                    </div>
                                    <template x-for="sug in currentThread.suggestions" :key="sug">
                                        <button @click="replyBody = sug" 
                                                class="px-6 py-2.5 bg-white border border-emerald-100 rounded-full text-[11px] font-bold text-emerald-800 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all shadow-sm shrink-0 active:scale-95 whitespace-nowrap">
                                            <span x-text="sug.length > 80 ? sug.substring(0, 80) + '...' : sug"></span>
                                        </button>
                                    </template>
                                </div>
                                
                                <div class="absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-gray-50/80 to-transparent pointer-events-none"></div>
                            </div>

                            
                            <div class="relative group border-t border-gray-100 pt-3">
                                <div class="overflow-x-auto whitespace-nowrap flex gap-3 no-scrollbar scroll-smooth pb-1">
                                    <div class="flex items-center gap-2 opacity-50 shrink-0">
                                        <i class="bi bi-journal-text text-blue-500 text-sm"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Common Templates:</span>
                                    </div>
                                    <template x-for="temp in currentThread.templates" :key="temp.label">
                                        <button @click="replyBody = temp.text" 
                                                class="px-6 py-2.5 bg-white border border-blue-100 rounded-full text-[11px] font-bold text-blue-800 hover:bg-blue-500 hover:text-white hover:border-blue-500 transition-all shadow-sm shrink-0 active:scale-95 whitespace-nowrap">
                                            <span x-text="temp.label"></span>
                                        </button>
                                    </template>
                                </div>
                                
                                <div class="absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-gray-50/80 to-transparent pointer-events-none"></div>
                            </div>
                        </div>

                        
                        <div class="px-6 py-4 bg-white border-t border-gray-100 shrink-0">
                            <div class="relative group">
                                <div class="absolute left-4 top-4">
                                    <button @click="isInternal = !isInternal" 
                                            :class="isInternal ? 'text-amber-500 bg-amber-50 border-amber-200 shadow-lg' : 'text-gray-300 hover:text-emerald-500 hover:bg-gray-50'"
                                            class="w-10 h-10 rounded-xl border border-transparent flex items-center justify-center transition-all" title="Toggle Internal Note">
                                        <i class="bi" :class="isInternal ? 'bi-shield-lock-fill' : 'bi-shield-lock'" class="text-lg"></i>
                                    </button>
                                </div>
                                <textarea x-model="replyBody" :placeholder="isInternal ? 'Write an internal coordination note...' : 'Type your message here...'" 
                                    class="w-full pl-16 pr-32 py-4 rounded-2xl bg-gray-50 border border-gray-200 text-xs font-medium focus:bg-white focus:border-emerald-500 outline-none transition-all resize-none shadow-inner" rows="2"></textarea>
                                
                                <button @click="sendReply" :disabled="!replyBody.trim() || sending" 
                                    class="absolute right-3 bottom-3 px-6 h-10 bg-gray-900 text-emerald-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:shadow-2xl hover:shadow-emerald-500/20 disabled:opacity-50 transition-all flex items-center gap-2 active:scale-95">
                                    <span x-show="!sending">Send</span>
                                    <i x-show="!sending" class="bi bi-send-fill text-xs"></i>
                                    <i x-show="sending" class="bi bi-arrow-repeat animate-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function residentSupport() {
        return {
            statusFilter: 'all',
            categoryFilter: 'all',
            search: '',
            threads: [],
            selectedThreadId: null,
            currentThread: null,
            replyBody: '',
            isInternal: false,
            sending: false,
            loading: false,
            loadingThread: false,

            init() {
                this.fetchThreads();
                setInterval(() => this.fetchThreads(), 30000);
            },

            async fetchThreads() {
                this.loading = true;
                try {
                    const response = await fetch(`<?php echo e(route('admin.messages.index')); ?>?status=${this.statusFilter}&category=${this.categoryFilter}&search=${this.search}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    this.threads = data.threads;
                    
                    // Auto-select first thread if none selected and threads exist
                    if (this.threads.length > 0 && !this.selectedThreadId) {
                        this.loadThread(this.threads[0].id);
                    }
                } finally {
                    this.loading = false;
                }
            },

            async loadThread(id) {
                if (this.loadingThread && this.selectedThreadId === id) return;
                
                this.selectedThreadId = id;
                this.loadingThread = true;
                // Don't clear currentThread immediately to avoid flicker if it's the same thread
                // but we do it here for fresh loads
                if (!this.currentThread || this.currentThread.id !== id) {
                    this.currentThread = null;
                }
                
                try {
                    const response = await fetch(`<?php echo e(url('admin/messages/support')); ?>/${id}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'Failed to load thread');
                    }
                    this.currentThread = await response.json();
                    this.scrollToBottom();
                } catch (e) {
                    console.error('Thread Load Error:', e);
                    // Only alert if it's not a background refresh
                    alert('Error loading conversation: ' + e.message);
                } finally {
                    this.loadingThread = false;
                }
            },

            async sendReply() {
                if (!this.replyBody.trim() || this.sending) return;
                this.sending = true;

                try {
                    const response = await fetch(`<?php echo e(url('admin/messages/support')); ?>/${this.selectedThreadId}/reply`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ 
                            body: this.replyBody,
                            is_internal: this.isInternal
                        })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        this.currentThread.messages.push(data.message);
                        if (!this.isInternal) this.currentThread.status = 'replied';
                        this.replyBody = '';
                        this.isInternal = false;
                        this.scrollToBottom();
                        this.fetchThreads();
                    }
                } finally {
                    this.sending = false;
                }
            },

            async performAction(action) {
                const response = await fetch(`<?php echo e(url('admin/messages/support')); ?>/${this.selectedThreadId}/action`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ action: action })
                });

                if (response.ok) {
                    this.loadThread(this.selectedThreadId);
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
<?php $__env->stopPush(); ?>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\messages\index.blade.php ENDPATH**/ ?>