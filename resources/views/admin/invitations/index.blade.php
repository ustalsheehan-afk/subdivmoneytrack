@extends('layouts.admin')

@section('title', 'Invitation Management')
@section('page-title', 'Manage Invitations')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-8 pb-12" x-data="{ 
    selectedId: {{ $invitations->first()->id ?? 'null' }},
    loading: false,
    invitation: null,
    search: '',
    previewTab: 'email',
    sections: {
        profile: true,
        actions: true,
        preview: true,
        timeline: true
    },
    async selectInvitation(id) {
        if (this.selectedId === id && this.invitation) return;
        this.selectedId = id;
        this.loading = true;
        try {
            const response = await fetch(`/admin/invitations/${id}`);
            const result = await response.json();
            if (result.success) {
                this.invitation = result.data;
            }
        } catch (e) {
            console.error('Error fetching invitation:', e);
        } finally {
            this.loading = false;
        }
    },
    init() {
        if (this.selectedId) {
            this.selectInvitation(this.selectedId);
        }
    }
}">
    {{-- TOP SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach([
            ['label' => 'All Invitations', 'value' => $stats['all'], 'icon' => 'bi-envelope-paper', 'color' => 'emerald', 'accent' => 'border-t-emerald-500'],
            ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'bi-clock-history', 'color' => 'amber', 'accent' => 'border-t-amber-500'],
            ['label' => 'Accepted', 'value' => $stats['accepted'], 'icon' => 'bi-check2-all', 'color' => 'blue', 'accent' => 'border-t-blue-500'],
            ['label' => 'Expired', 'value' => $stats['expired'], 'icon' => 'bi-exclamation-triangle', 'color' => 'red', 'accent' => 'border-t-red-500']
        ] as $card)
        <div class="glass-card p-6 relative overflow-hidden group hover:scale-[1.02] transition-all duration-300 border-t-2 {{ $card['accent'] }}">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-{{ $card['color'] }}-50 border border-{{ $card['color'] }}-100 flex items-center justify-center text-{{ $card['color'] }}-500 shadow-sm group-hover:bg-{{ $card['color'] === 'emerald' ? 'emerald-600' : ($card['color'] === 'amber' ? 'amber-500' : ($card['color'] === 'blue' ? 'blue-500' : 'red-500')) }} group-hover:text-white transition-all duration-500">
                    <i class="bi {{ $card['icon'] }} text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">{{ $card['label'] }}</p>
                    <span class="text-2xl font-black text-gray-900 tracking-tight">{{ number_format($card['value']) }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- HEADER ACTIONS & SEARCH --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="relative flex-1 max-w-2xl group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="bi bi-search text-gray-400 group-focus-within:text-[#B6FF5C] transition-colors"></i>
            </div>
            <input type="text" x-model="search" placeholder="Search by name, email or status..." 
                   class="w-full pl-11 pr-4 py-4 bg-white border border-gray-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-[#B6FF5C]/10 focus:border-[#B6FF5C] focus:outline-none transition-all shadow-sm">
        </div>

        <div class="flex items-center gap-3">
            <button class="px-6 py-3.5 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm flex items-center gap-2">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <button class="px-6 py-3.5 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm flex items-center gap-2">
                <i class="bi bi-download"></i> Export
            </button>
            <button onclick="openInviteModal()" class="px-8 py-3.5 bg-[#081412] text-[#B6FF5C] rounded-2xl text-xs font-black uppercase tracking-widest hover:shadow-[0_0_20px_rgba(182,255,92,0.3)] transition-all flex items-center gap-2 border border-[#B6FF5C]/20 active:scale-95">
                <i class="bi bi-plus-lg text-sm"></i> Invite Resident
            </button>
        </div>
    </div>

    {{-- SPLIT LAYOUT: MASTER-DETAIL --}}
    <div class="flex flex-col lg:flex-row gap-8 items-stretch min-h-[700px]">
        
        {{-- LEFT PANEL: INVITATIONS LIST (65%) --}}
        <div class="lg:w-[65%] flex flex-col gap-6">
            <div class="glass-card bg-white border border-gray-100 rounded-[12px] shadow-sm overflow-hidden flex flex-col flex-1">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Resident</th>
                                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Channels</th>
                                <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Expires</th>
                                <th class="px-8 py-5 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($invitations as $invite)
                            <tr class="group cursor-pointer transition-all duration-300 relative"
                                :class="selectedId === {{ $invite->id }} ? 'bg-[#B6FF5C]/5' : 'hover:bg-gray-50/80'"
                                @click="selectInvitation({{ $invite->id }})"
                                x-show="'{{ strtolower($invite->first_name . ' ' . $invite->last_name . ' ' . $invite->email . ' ' . $invite->status) }}'.includes(search.toLowerCase())">
                                
                                {{-- Active Indicator --}}
                                <div x-show="selectedId === {{ $invite->id }}" 
                                     class="absolute left-0 top-0 bottom-0 w-1 bg-[#B6FF5C] transition-all duration-300"></div>

                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-900 flex items-center justify-center text-[#B6FF5C] font-black text-sm shadow-lg shadow-gray-900/10 group-hover:scale-110 transition-transform duration-300">
                                            {{ strtoupper(substr($invite->first_name, 0, 1) . substr($invite->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-gray-900 leading-none mb-1 group-hover:text-[#081412] transition-colors">{{ $invite->first_name }} {{ $invite->last_name }}</p>
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ $invite->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $isExpired = $invite->isExpired();
                                        $status = $isExpired && $invite->status === 'pending' ? 'expired' : $invite->status;
                                        $statusConfig = [
                                            'pending'   => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100'],
                                            'accepted'  => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100'],
                                            'expired'   => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100'],
                                            'cancelled' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-500', 'border' => 'border-gray-100'],
                                        ];
                                        $conf = $statusConfig[$status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $conf['bg'] }} {{ $conf['text'] }} {{ $conf['border'] }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <i class="bi bi-envelope-fill text-sm {{ $invite->email_status === 'sent' ? 'text-blue-500' : 'text-gray-200' }}" title="Email"></i>
                                        <i class="bi bi-chat-left-dots-fill text-sm {{ $invite->sms_status === 'sent' ? 'text-emerald-500' : 'text-gray-200' }}" title="SMS"></i>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-gray-900 uppercase tracking-tighter">
                                            {{ $invite->expires_at->format('M d, Y') }}
                                        </span>
                                        @if($status === 'pending')
                                            <span class="text-[9px] font-bold {{ $isExpired ? 'text-red-400' : 'text-amber-500' }} uppercase tracking-widest mt-0.5">
                                                {{ $isExpired ? 'Expired' : $invite->expires_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-300 group-hover:text-[#081412] group-hover:bg-[#B6FF5C]/10 transition-all duration-300">
                                        <i class="bi bi-chevron-right"></i>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-32 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center text-gray-200">
                                            <i class="bi bi-envelope-paper text-4xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-gray-900 font-extrabold">No invitations found</h3>
                                            <p class="text-sm text-gray-400 mt-1">Start by inviting your residents to the platform.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: DETAIL VIEW (35%) --}}
        <div class="lg:w-[35%] space-y-4 lg:sticky lg:top-32 h-fit">
            
            {{-- PROFILE SECTION --}}
            <div class="glass-card bg-white border border-gray-100 rounded-[12px] shadow-sm overflow-hidden">
                <button @click="sections.profile = !sections.profile" 
                        class="w-full px-6 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-person-circle text-gray-400"></i>
                        <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Resident Profile</span>
                    </div>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="sections.profile ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="sections.profile" x-collapse>
                    <div x-show="loading" class="p-8 flex justify-center">
                        <div class="w-8 h-8 border-3 border-gray-100 border-t-[#B6FF5C] rounded-full animate-spin"></div>
                    </div>

                    <div class="p-8 text-center" x-show="invitation && !loading">
                        <div class="relative inline-block mb-6">
                            <div class="w-20 h-20 rounded-3xl bg-gray-900 flex items-center justify-center text-[#B6FF5C] text-2xl font-black shadow-xl shadow-gray-900/10" 
                                 x-text="invitation.first_name[0] + invitation.last_name[0]">
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-7 h-7 rounded-xl bg-white border-4 border-white shadow-lg flex items-center justify-center">
                                <i class="bi bi-patch-check-fill text-emerald-500 text-sm" x-show="invitation.status === 'accepted'"></i>
                                <i class="bi bi-clock-fill text-amber-500 text-sm" x-show="invitation.status === 'pending' && !invitation.is_expired"></i>
                                <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm" x-show="invitation.is_expired"></i>
                            </div>
                        </div>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight leading-none mb-2" x-text="invitation.first_name + ' ' + invitation.last_name"></h2>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest" x-text="invitation.email"></p>
                    </div>

                    <div class="p-8 text-center" x-show="!invitation && !loading">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Select an invitation</p>
                    </div>
                </div>
            </div>

            {{-- ACTIONS SECTION --}}
            <div class="glass-card bg-white border border-gray-100 rounded-[12px] shadow-sm overflow-hidden" x-show="invitation">
                <button @click="sections.actions = !sections.actions" 
                        class="w-full px-6 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-lightning-charge-fill text-gray-400"></i>
                        <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Quick Actions</span>
                    </div>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="sections.actions ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="sections.actions" x-collapse class="p-6 space-y-3">
                    <button @click="copyInviteLink(invitation.registration_link)" 
                            class="w-full py-3.5 bg-[#081412] text-[#B6FF5C] rounded-xl text-[10px] font-black uppercase tracking-widest hover:shadow-[0_0_15px_rgba(182,255,92,0.2)] transition-all flex items-center justify-center gap-2 border border-[#B6FF5C]/20 active:scale-95">
                        <i class="bi bi-link-45deg text-base"></i>
                        Copy Link
                    </button>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="resendInvite(invitation.id)" :disabled="invitation.status === 'accepted' || invitation.is_expired"
                                class="py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                            <i class="bi bi-send"></i> Resend
                        </button>
                        <button @click="renewInvite(invitation.id)" :disabled="invitation.status === 'accepted'"
                                class="py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                            <i class="bi bi-arrow-clockwise"></i> Renew
                        </button>
                    </div>
                    <button @click="cancelInvite(invitation.id)" :disabled="invitation.status === 'accepted' || invitation.status === 'cancelled'"
                            class="w-full py-3.5 bg-red-50 border border-red-100 text-red-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-100 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                </div>
            </div>

            {{-- MESSAGE PREVIEW SECTION --}}
            <div class="glass-card bg-white border border-gray-100 rounded-[12px] shadow-sm overflow-hidden" x-show="invitation">
                <button @click="sections.preview = !sections.preview" 
                        class="w-full px-6 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-eye-fill text-gray-400"></i>
                        <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Message Preview</span>
                    </div>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="sections.preview ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="sections.preview" x-collapse class="p-6">
                    <div class="flex bg-gray-100 p-1 rounded-xl mb-4 border border-gray-200 shadow-inner">
                        <button @click="previewTab = 'email'" 
                                :class="previewTab === 'email' ? 'bg-white text-gray-900 shadow-sm border border-gray-100' : 'text-gray-500'"
                                class="flex-1 px-3 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                            EMAIL
                        </button>
                        <button @click="previewTab = 'sms'" 
                                :class="previewTab === 'sms' ? 'bg-white text-gray-900 shadow-sm border border-gray-100' : 'text-gray-500'"
                                class="flex-1 px-3 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                            SMS
                        </button>
                    </div>

                    <div class="bg-gray-50/80 rounded-2xl p-6 border border-gray-100 shadow-sm min-h-[140px] flex flex-col justify-center">
                        {{-- Email Content --}}
                        <div x-show="previewTab === 'email'" class="space-y-4 animate-fade-in">
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Hello <span class="font-extrabold text-gray-900" x-text="invitation.first_name + ' ' + invitation.last_name"></span>,
                            </p>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                You're invited to register for <span class="font-bold text-gray-800" x-text="invitation.platform_name"></span>.
                            </p>
                            <p class="text-[11px] font-mono text-blue-500 underline break-all opacity-80" x-text="invitation.registration_link"></p>
                        </div>
                        
                        {{-- SMS Content --}}
                        <div x-show="previewTab === 'sms'" class="animate-fade-in">
                            <p class="text-xs text-gray-600 leading-relaxed italic">
                                "Hello <span class="font-extrabold text-gray-900" x-text="invitation.first_name"></span>, register for <span class="font-bold text-gray-800" x-text="invitation.platform_name"></span> here: <span class="text-blue-500 underline break-all" x-text="invitation.registration_link"></span>"
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TIMELINE SECTION --}}
            <div class="glass-card bg-white border border-gray-100 rounded-[12px] shadow-sm overflow-hidden" x-show="invitation">
                <button @click="sections.timeline = !sections.timeline" 
                        class="w-full px-6 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-clock-history text-gray-400"></i>
                        <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Status Timeline</span>
                    </div>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="sections.timeline ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="sections.timeline" x-collapse class="p-8">
                    <div class="space-y-8 relative before:absolute before:inset-y-0 before:left-[7px] before:w-[2px] before:bg-gray-50">
                        <template x-for="(item, index) in invitation.activity" :key="index">
                            <div class="flex items-start gap-4 relative">
                                <div class="w-4 h-4 rounded-full border-4 border-white shadow-sm z-10" :class="item.icon_bg"></div>
                                <div class="flex-1">
                                    <p class="text-xs font-black text-gray-900 leading-none mb-1" x-text="item.title"></p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest" x-text="item.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

{{-- INVITE MODAL --}}
@push('modals')
<div id="invite-modal" class="fixed inset-0 z-[100] invisible opacity-0 transition-all duration-300 ease-in-out bg-gray-900/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-out" id="invite-modal-panel">
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-[#B6FF5C]">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight">Invite Resident</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Register a new community member</p>
                </div>
            </div>
            <button onclick="closeInviteModal()" class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-100 transition-all shadow-sm">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="create-invite-form" class="p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">First Name</label>
                    <input type="text" name="first_name" required 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-[#B6FF5C]/10 focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Last Name</label>
                    <input type="text" name="last_name" required 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-[#B6FF5C]/10 focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all">
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <input type="email" name="email" required 
                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-[#B6FF5C]/10 focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all">
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone Number (Optional)</label>
                <input type="text" name="phone" 
                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-[#B6FF5C]/10 focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all">
            </div>

            <div class="pt-4 flex items-center gap-4">
                <button type="button" onclick="closeInviteModal()" 
                        class="flex-1 px-6 py-4 bg-white border border-gray-200 text-gray-500 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-6 py-4 bg-[#081412] text-[#B6FF5C] rounded-2xl text-xs font-black uppercase tracking-widest hover:shadow-[0_0_20px_rgba(182,255,92,0.3)] transition-all border border-[#B6FF5C]/20 shadow-xl active:scale-95">
                    Send Invitation
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E2E8F0; border-radius: 20px; }
.custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
[x-cloak] { display: none !important; }
</style>

<script>
function openInviteModal() {
    const modal = document.getElementById('invite-modal');
    const panel = document.getElementById('invite-modal-panel');
    modal.classList.remove('invisible', 'opacity-0');
    setTimeout(() => {
        panel.classList.remove('scale-95', 'opacity-0');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeInviteModal() {
    const modal = document.getElementById('invite-modal');
    const panel = document.getElementById('invite-modal-panel');
    panel.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('invisible', 'opacity-0');
        document.body.style.overflow = 'auto';
    }, 300);
}

// Global functions for Alpine to call
async function resendInvite(id) {
    if(!confirm('Resend the current invitation link?')) return;
    try {
        const response = await fetch(`/admin/invitations/${id}/resend`, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json' 
            } 
        });
        const data = await response.json();
        if(data.success) location.reload();
        else alert(data.message || 'Error');
    } catch (e) { alert('An error occurred'); }
}

async function renewInvite(id) {
    if(!confirm('Generate a NEW link and refresh the 7-day expiry?')) return;
    try {
        const response = await fetch(`/admin/invitations/${id}/renew`, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json' 
            } 
        });
        const result = await response.json();
        if(result.success) {
            navigator.clipboard.writeText(result.link).then(() => {
                alert('Invitation renewed! New link copied to clipboard.');
                location.reload();
            });
        } else { alert(result.message || 'Failed to renew'); }
    } catch (e) { alert('An error occurred'); }
}

async function cancelInvite(id) {
    if(!confirm('Are you sure you want to cancel this invitation?')) return;
    try {
        const response = await fetch(`/admin/invitations/${id}/cancel`, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json' 
            } 
        });
        const data = await response.json();
        if(data.success) location.reload();
        else alert(data.message || 'Failed to cancel');
    } catch (e) { alert('An error occurred'); }
}

function copyInviteLink(link) {
    navigator.clipboard.writeText(link).then(() => alert('Registration link copied!'));
}

document.getElementById('create-invite-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    try {
        const response = await fetch("{{ route('admin.invitations.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        const data = await response.json();
        if (data.success) location.reload();
        else alert(data.message || 'Error');
    } catch (e) { alert('An error occurred'); }
});
</script>
@endsection
