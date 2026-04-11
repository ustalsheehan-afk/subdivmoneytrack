@extends('resident.layouts.app')

@section('title', 'Messages')
@section('page-title', 'Messages Center')

@section('content')
<div class="space-y-8" x-data="{ filter: 'all' }">
    <x-resident-hero-header 
        label="Inbox & Notifications" 
        icon="bi-chat-dots-fill"
        title="Messages" 
        description="Stay connected with the administration and keep track of your inquiries and subdivision alerts."
        :tabs="[
            ['id' => 'all', 'label' => 'All', 'icon' => 'bi-grid-fill', 'click' => 'filter = \'all\'', 'active_condition' => 'filter === \'all\''],
            ['id' => 'unread', 'label' => 'Unread', 'icon' => 'bi-envelope-fill', 'click' => 'filter = \'unread\'', 'active_condition' => 'filter === \'unread\''],
            ['id' => 'replied', 'label' => 'Replied', 'icon' => 'bi-reply-fill', 'click' => 'filter = \'replied\'', 'active_condition' => 'filter === \'replied\''],
        ]"
    >
        <x-slot name="actions">
            <a href="{{ route('resident.messages.create') }}" class="btn-premium">
                <i class="bi bi-plus-lg"></i>
                New Message
            </a>
        </x-slot>
    </x-resident-hero-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Inbox Section --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card overflow-hidden min-h-[500px]">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Inbox Conversations</h3>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase">
                            {{ $threads->count() }} Threads
                        </span>
                    </div>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($threads as $thread)
                        @php
                            $unreadCount = $thread->unreadMessagesCount([\App\Models\User::class, \App\Models\Admin::class]);
                            $latestMessage = $thread->latestMessage;
                            $isReplied = in_array($thread->status, ['replied', 'in_progress', 'closed'], true);
                        @endphp
                        <a href="{{ route('resident.messages.show', $thread->id) }}" 
                           class="block p-8 hover:bg-emerald-50/20 transition-all group relative"
                           x-show="filter === 'all' || 
                                  (filter === 'unread' && {{ $unreadCount > 0 ? 'true' : 'false' }}) || 
                                  (filter === 'replied' && {{ $isReplied ? 'true' : 'false' }})">
                            @if($unreadCount > 0)
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-12 bg-emerald-500 rounded-r-full"></div>
                            @endif
                            
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-black rounded-full uppercase tracking-widest">{{ $thread->category }}</span>
                                    <h4 class="text-sm font-black text-gray-900 group-hover:text-emerald-600 transition-colors uppercase tracking-tight">{{ $thread->subject }}</h4>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $thread->last_message_at->diffForHumans() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-[11px] text-gray-500 font-medium italic truncate max-w-[80%]">
                                    {{ $thread->latestMessage->body ?? 'No messages yet' }}
                                </p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $thread->status == 'closed' ? 'text-gray-400' : 'text-emerald-600' }}">
                                        {{ $thread->status }}
                                    </span>
                                    <i class="bi bi-chevron-right text-gray-300 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-20 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-emerald-600">
                                <i class="bi bi-chat-quote text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight mb-2 uppercase">No Conversations Found</h3>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest max-w-[250px] mx-auto leading-relaxed">
                                You haven't started any conversations yet. Click "New Message" above to get in touch with the administration.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Side: Quick Info / Activity --}}
        <div class="space-y-6">
            <div class="bg-[#0D1F1C] p-8 rounded-[2.5rem] shadow-xl shadow-emerald-900/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-110 transition-transform"></div>
                <h4 class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mb-6 relative z-10">Message Templates</h4>
                <div class="space-y-3 relative z-10">
                    <a href="{{ route('resident.messages.create', ['category' => 'payment', 'subject' => 'Payment Inquiry', 'open_templates' => 1]) }}" class="block p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all text-xs font-bold text-white uppercase tracking-widest">
                        Payment Inquiry
                    </a>
                    <a href="{{ route('resident.messages.create', ['category' => 'complaint', 'subject' => 'Service Complaint', 'open_templates' => 1]) }}" class="block p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all text-xs font-bold text-white uppercase tracking-widest">
                        Service Complaint
                    </a>
                    <a href="{{ route('resident.messages.create', ['category' => 'reservation', 'subject' => 'Booking Question', 'open_templates' => 1]) }}" class="block p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all text-xs font-bold text-white uppercase tracking-widest">
                        Booking Question
                    </a>
                </div>
            </div>

            <div class="glass-card p-8">
                <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-6">Need Support?</h4>
                <div class="p-6 bg-emerald-50/50 rounded-2xl border border-emerald-100/50 text-center">
                    <i class="bi bi-headset text-2xl text-emerald-600 mb-2 inline-block"></i>
                    <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Customer Support</p>
                    <p class="text-[11px] text-gray-500 font-bold mt-2">Open Mon-Fri • 8AM-5PM</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
