@extends('layouts.admin')

@section('title', 'Invitation Management')
@section('page-title', 'Manage Invitations')

@section('content')
@php
    $activeFilters = request()->only(['search', 'status', 'delivery']);
    $hasActiveFilters = collect($activeFilters)->filter(fn ($value) => filled($value))->isNotEmpty();
@endphp
<div class="space-y-8 animate-fade-in pb-20" x-data="{ 
    selectedId: {{ $invitations->first()->id ?? 'null' }},
    loading: false,
    invitation: null,
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

    @if($stats['expiring_soon'] > 0)
    <div class="glass-card border-amber-100 bg-amber-50/60 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700 border border-amber-200">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-amber-900">{{ $stats['expiring_soon'] }} invitation{{ $stats['expiring_soon'] > 1 ? 's' : '' }} will expire within 24 hours.</p>
                <p class="text-xs text-amber-700 mt-0.5">Resend or renew them before they expire.</p>
            </div>
        </div>
    </div>
    @endif

    <div class="glass-card p-8 relative overflow-hidden group rounded-2xl">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-slate-500/5 rounded-full blur-3xl group-hover:bg-slate-500/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 relative z-10">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 border border-gray-100">
                    <i class="bi bi-envelope-paper text-gray-500 text-xs"></i>
                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Resident invitations</span>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Manage Invitations</h1>
                    <p class="mt-2 text-gray-600 text-base max-w-2xl leading-relaxed">
                        Invite residents, review delivery status, and follow each registration from one clean admin view.
                    </p>
                </div>
            </div>


        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'All Invitations', 'value' => $stats['all'], 'icon' => 'bi-envelope-paper'],
            ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'bi-clock-history'],
            ['label' => 'Accepted', 'value' => $stats['accepted'], 'icon' => 'bi-check2-circle'],
            ['label' => 'Expired', 'value' => $stats['expired'], 'icon' => 'bi-exclamation-triangle']
        ] as $card)
        <div class="glass-card p-5 rounded-2xl">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-600 shrink-0">
                    <i class="bi {{ $card['icon'] }} text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ $card['label'] }}</p>
                    <span class="text-3xl font-black text-gray-900 tracking-tight">{{ number_format($card['value']) }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.invitations.index') }}" class="glass-card p-4 lg:p-5 rounded-2xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
            <div class="lg:col-span-5">
                <div class="relative group" title="Search by name, email, or phone">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-gray-600 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, or phone"
                           onkeypress="if(event.key === 'Enter') this.form.submit()"
                           class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:bg-white focus:border-gray-400 focus:ring-4 focus:ring-gray-100 transition-all outline-none">
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="relative group" title="Filter by Status">
                    <i class="bi bi-funnel absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-gray-600 transition-colors"></i>
                    <select name="status" onchange="this.form.submit()" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:bg-white focus:border-gray-400 focus:ring-4 focus:ring-gray-100 transition-all outline-none">
                        <option value="">All</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="accepted" @selected(request('status') === 'accepted')>Accepted</option>
                        <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="relative group" title="Filter by Delivery Status">
                    <i class="bi bi-truck absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-gray-600 transition-colors"></i>
                    <select name="delivery" onchange="this.form.submit()" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:bg-white focus:border-gray-400 focus:ring-4 focus:ring-gray-100 transition-all outline-none">
                        <option value="">All</option>
                        <option value="email_sent" @selected(request('delivery') === 'email_sent')>Email Sent</option>
                        <option value="sms_sent" @selected(request('delivery') === 'sms_sent')>SMS Sent</option>
                        <option value="pending" @selected(request('delivery') === 'pending')>Pending</option>
                        <option value="failed" @selected(request('delivery') === 'failed')>Failed</option>
                    </select>
                </div>
            </div>

            <div class="lg:col-span-3 flex items-center gap-3 lg:justify-end">
                <a href="{{ route('admin.invitations.index') }}" class="h-12 w-12 flex items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all shadow-sm" title="Reset Filters">
                    <i class="bi bi-arrow-counterclockwise text-lg"></i>
                </a>
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-invite-modal'))" class="h-12 w-12 flex items-center justify-center rounded-xl bg-[#081412] text-[#B6FF5C] hover:bg-[#0f2520] transition-all shadow-sm" title="Invite Resident">
                    <i class="bi bi-person-plus-fill text-lg"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="flex items-center justify-between text-sm text-gray-500">
        <span>{{ number_format($filteredCount) }} invitation{{ $filteredCount === 1 ? '' : 's' }} shown</span>
        @if($hasActiveFilters)
            <span class="inline-flex items-center gap-2 text-gray-600">
                <i class="bi bi-funnel"></i>
                Filtered view active
            </span>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start min-h-[700px]">
        <div class="lg:w-[64%] min-w-0">
            <div class="glass-card rounded-2xl overflow-hidden flex flex-col">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30 border-b border-gray-100/50">
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] text-left">Resident</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] text-center">Status</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] text-center">Delivery</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] text-center">Expires</th>
                                <th class="px-5 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em]"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100/50">
                            @forelse($invitations as $invite)
                            @php
                                $isExpired = $invite->isExpired();
                                $status = $isExpired && $invite->status === 'pending' ? 'expired' : $invite->status;
                                $statusConfig = [
                                    'pending'   => ['bg' => 'bg-amber-50/50', 'text' => 'text-amber-600', 'dot' => 'bg-amber-400'],
                                    'accepted'  => ['bg' => 'bg-emerald-50/50', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-400'],
                                    'expired'   => ['bg' => 'bg-red-50/50', 'text' => 'text-red-600', 'dot' => 'bg-red-400'],
                                    'cancelled' => ['bg' => 'bg-gray-50/50', 'text' => 'text-gray-500', 'dot' => 'bg-gray-300'],
                                ];
                                $deliveryLabel = match (true) {
                                    $invite->email_status === \App\Models\Invitation::DELIVERY_SENT && $invite->sms_status === \App\Models\Invitation::DELIVERY_SENT => 'Email + SMS',
                                    $invite->email_status === \App\Models\Invitation::DELIVERY_SENT => 'Email',
                                    $invite->sms_status === \App\Models\Invitation::DELIVERY_SENT => 'SMS',
                                    $invite->email_status === \App\Models\Invitation::DELIVERY_FAILED || $invite->sms_status === \App\Models\Invitation::DELIVERY_FAILED => 'Failed',
                                    default => 'Pending',
                                };
                                $deliveryConfig = match ($deliveryLabel) {
                                    'Email + SMS', 'Email', 'SMS' => ['bg' => 'bg-blue-50/50', 'text' => 'text-blue-600'],
                                    'Failed' => ['bg' => 'bg-red-50/50', 'text' => 'text-red-600'],
                                    default => ['bg' => 'bg-gray-50/50', 'text' => 'text-gray-500'],
                                };
                                $conf = $statusConfig[$status] ?? $statusConfig['pending'];
                                $initials = strtoupper(substr($invite->first_name, 0, 1) . substr($invite->last_name, 0, 1));
                            @endphp
                            <tr class="group cursor-pointer transition-all duration-150" :class="selectedId === {{ $invite->id }} ? 'bg-gray-50/80' : 'hover:bg-gray-50/50'" @click="selectInvitation({{ $invite->id }})">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-[10px] shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[13px] font-semibold text-gray-900 leading-tight truncate">{{ $invite->first_name }} {{ $invite->last_name }}</p>
                                            <p class="text-[11px] text-gray-400 truncate">{{ $invite->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $conf['bg'] }} {{ $conf['text'] }}">
                                        <span class="w-1 h-1 rounded-full {{ $conf['dot'] }}"></span>
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $deliveryConfig['bg'] }} {{ $deliveryConfig['text'] }}">
                                        {{ $deliveryLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-[12px] text-gray-400 font-medium">
                                        {{ $invite->expires_at->format('M d') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-gray-500 transition-colors text-xs"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                            <i class="bi bi-envelope-paper text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-[13px] text-gray-900 font-bold">No invitations found</p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">Try different filters or invite a new resident.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($invitations->hasPages())
                <div class="px-5 py-3 border-t border-gray-50 bg-gray-50/20">
                    {{ $invitations->links() }}
                </div>
                @endif
            </div>
        </div>

        <div class="lg:w-[36%] space-y-4 lg:sticky lg:top-32 h-fit">
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="bi bi-person-circle text-xs"></i>
                        </div>
                        <span class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.1em]">Selected Invitation</span>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-[0.1em] flex items-center gap-1.5">
                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live
                    </span>
                </div>

                <div class="p-5">
                    <div x-show="loading" class="py-12 flex justify-center">
                        <div class="w-6 h-6 border-2 border-gray-100 border-t-gray-900 rounded-full animate-spin"></div>
                    </div>

                    <template x-if="invitation && !loading">
                        <div class="space-y-5">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-emerald-400 text-sm font-bold shrink-0" x-text="invitation.first_name[0] + invitation.last_name[0]"></div>
                                    <div class="min-w-0">
                                        <h2 class="text-[15px] font-bold text-gray-900 tracking-tight leading-none mb-1 truncate" x-text="invitation.first_name + ' ' + invitation.last_name"></h2>
                                        <p class="text-[11px] text-gray-400 truncate" x-text="invitation.email"></p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest bg-gray-50 text-gray-500 border border-gray-100" x-text="invitation.is_expired ? 'Expired' : invitation.status"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <div class="rounded-xl border border-gray-50 bg-gray-50/30 p-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em]">Phone</p>
                                    <p class="mt-0.5 text-[12px] font-semibold text-gray-900" x-text="invitation.phone"></p>
                                </div>
                                <div class="rounded-xl border border-gray-50 bg-gray-50/30 p-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em]">Expires</p>
                                    <p class="mt-0.5 text-[12px] font-semibold text-gray-900" x-text="invitation.expires_at"></p>
                                </div>
                                <div class="rounded-xl border border-gray-50 bg-gray-50/30 p-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em]">Last Sent</p>
                                    <p class="mt-0.5 text-[12px] font-semibold text-gray-900" x-text="invitation.last_sent"></p>
                                </div>
                                <div class="rounded-xl border border-gray-50 bg-gray-50/30 p-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em]">Days Left</p>
                                    <p class="mt-0.5 text-[12px] font-semibold text-gray-900" x-text="invitation.is_expired ? 'Expired' : (invitation.days_left !== null ? invitation.days_left : 'N/A')"></p>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-white p-3.5 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em]">Email Status</span>
                                    <span class="text-[10px] font-semibold text-gray-700" x-text="invitation.email_status"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em]">SMS Status</span>
                                    <span class="text-[10px] font-semibold text-gray-700" x-text="invitation.sms_status"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="!invitation && !loading" class="py-12 text-center">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto mb-3">
                            <i class="bi bi-cursor text-xl"></i>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Select an invitation to view details</p>
                    </div>
                </div>
            </div>

            <template x-if="invitation">
                <div class="space-y-4">
                    <div class="glass-card rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/30 flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="bi bi-lightning-charge-fill text-xs"></i>
                            </div>
                            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.1em]">Actions</span>
                        </div>

                        <div class="p-5 space-y-2.5">
                            <button type="button" @click="copyInviteLink(invitation.registration_link)" class="w-full h-10 rounded-xl bg-gray-900 text-emerald-400 text-[10px] font-bold uppercase tracking-widest hover:bg-black transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-link-45deg"></i>
                                Copy Link
                            </button>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button type="button" @click="resendInvite(invitation.id)" :disabled="invitation.status === 'accepted' || invitation.is_expired" class="h-10 rounded-xl bg-white border border-gray-200 text-gray-600 text-[9px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-all disabled:opacity-50">
                                    Resend
                                </button>
                                <button type="button" @click="renewInvite(invitation.id)" :disabled="invitation.status === 'accepted'" class="h-10 rounded-xl bg-white border border-gray-200 text-gray-600 text-[9px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-all disabled:opacity-50">
                                    Renew
                                </button>
                            </div>
                            <button type="button" @click="cancelInvite(invitation.id)" :disabled="invitation.status === 'accepted' || invitation.status === 'cancelled'" class="w-full h-10 rounded-xl bg-red-50 text-red-600 text-[9px] font-bold uppercase tracking-widest hover:bg-red-100 transition-all disabled:opacity-50">
                                Cancel Invitation
                            </button>
                        </div>
                    </div>

                    <div class="glass-card rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/30 flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="bi bi-clock-history text-xs"></i>
                            </div>
                            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.1em]">Activity</span>
                        </div>

                        <div class="p-5 space-y-4">
                            <template x-for="(item, index) in invitation.activity" :key="index">
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 rounded-full mt-1.5" :class="item.icon_bg"></div>
                                    <div>
                                        <p class="text-[12px] font-semibold text-gray-900 leading-tight" x-text="item.title"></p>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5" x-text="item.time"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

{{-- INVITE MODAL --}}
@push('modals')
<template x-teleport="body">
<div id="invite-modal"
    x-data="{
        show: false,
        open() { this.show = true; document.body.style.overflow = 'hidden'; },
        close() { this.show = false; document.body.style.overflow = 'auto'; }
    }"
    x-show="show"
    x-on:open-invite-modal.window="open()"
    x-on:close-invite-modal.window="close()"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    x-cloak>
    
    {{-- Backdrop --}}
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="$dispatch('close-invite-modal')"></div>

    {{-- Modal Panel --}}
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden relative z-10 border border-gray-100">
        
        <div class="px-10 py-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center text-emerald-400 shadow-lg shadow-gray-900/20">
                    <i class="bi bi-person-plus-fill text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Invite Resident</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Register a new community member</p>
                </div>
            </div>
            <button @click="$dispatch('close-invite-modal')" 
                    class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-100 transition-all shadow-sm flex items-center justify-center">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="create-invite-form" action="{{ route('admin.invitations.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">First Name</label>
                    <input type="text" name="first_name" required placeholder="Enter first name"
                           class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Last Name</label>
                    <input type="text" name="last_name" required placeholder="Enter last name"
                           class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                </div>
            </div>
            
            <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <div class="relative group">
                    <i class="bi bi-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <input type="email" name="email" required placeholder="name@example.com"
                           class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                </div>
            </div>
            
            <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone Number (Optional)</label>
                <div class="relative group">
                    <i class="bi bi-phone absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <input type="text" name="phone" placeholder="+63 9xx xxx xxxx"
                           class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                </div>
            </div>

            <div class="pt-6 flex items-center gap-4">
                <button type="button" @click="$dispatch('close-invite-modal')" 
                        class="flex-1 px-8 py-5 bg-white border border-gray-200 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-8 py-5 btn-premium">
                    Send Invitation
                </button>
            </div>
        </form>
    </div>
</div>
</template>
@endpush

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E2E8F0; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
[x-cloak] { display: none !important; }
</style>

<script>
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

// document.getElementById('create-invite-form')?.addEventListener('submit', async (e) => {
//     e.preventDefault();
//     const formData = new FormData(e.target);
//     try {
//         const response = await fetch("{{ route('admin.invitations.store') }}", {
//             method: 'POST',
//             headers: {
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
//                 'Accept': 'application/json'
//             },
//             body: formData
//         });
//         const data = await response.json();
//         if (data.success) location.reload();
//         else alert(data.message || 'Error');
//     } catch (e) { alert('An error occurred'); }
// });
</script>
@endsection
