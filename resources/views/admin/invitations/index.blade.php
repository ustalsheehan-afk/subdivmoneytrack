@extends('layouts.admin')

@section('title', 'Invitation Management')
@section('page-title', 'Manage Invitations')

@section('content')
@php
    $btn_primary = "inline-flex items-center justify-center gap-2 px-4 h-10 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm";
    $btn_secondary = "inline-flex items-center justify-center gap-2 px-4 h-10 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm";
    $btn_tertiary = "inline-flex items-center justify-center gap-2 px-3 py-1.5 bg-transparent text-gray-500 rounded-md text-xs font-medium hover:bg-gray-100 hover:text-gray-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed";
@endphp

<div class="h-[calc(100vh-8rem)] flex flex-col space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
           
            <p class="text-sm text-gray-500">Manage and track resident registration invitations.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @class([$btn_secondary])>
                <i class="bi bi-funnel text-sm"></i> Filter
            </button>
            <button @class([$btn_secondary])>
                <i class="bi bi-download text-sm"></i> Export
            </button>
            <button onclick="openInviteModal()" @class([$btn_primary])>
                <i class="bi bi-plus-lg"></i> Invite Resident
            </button>
        </div>
    </div>

    {{-- Main Content - Split Layout --}}
    <div class="flex-1 grid grid-cols-12 gap-6 overflow-hidden min-h-0">
        {{-- Left Column --}}
        <div class="col-span-8 flex flex-col gap-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach([
                    ['label' => 'All Invitations', 'value' => $stats['all'], 'icon' => 'bi-envelope-paper', 'color' => 'text-gray-500'],
                    ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'bi-clock-history', 'color' => 'text-amber-500'],
                    ['label' => 'Accepted', 'value' => $stats['accepted'], 'icon' => 'bi-check2-all', 'color' => 'text-emerald-500']
                ] as $card)
                <div class="bg-white p-5 rounded-xl border border-gray-200 flex items-center gap-4 shadow-sm h-[92px]">
                    <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center {{ $card['color'] }}">
                        <i class="bi {{ $card['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">{{ $card['label'] }}</p>
                        <span class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Table Section (Left Side) --}}
            <div class="flex-1 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
                    <div class="relative w-full max-w-xs">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="invitation-search" placeholder="Search residents..." class="w-full px-4 py-2 bg-gray-50 border-transparent rounded-lg text-sm focus:ring-2 focus:ring-gray-200 transition-all focus:outline-none">
                    </div>
                    <div class="text-xs text-gray-400 font-medium">Showing <span id="visible-count">{{ $invitations->count() }}</span> results</div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-gray-50/80 backdrop-blur-sm z-10">
                            <tr>
                                <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Resident</th>
                                <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Channels</th>
                                <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Expires</th>
                                <th class="px-5 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($invitations as $invite)
                            <tr class="hover:bg-gray-50 transition-colors group cursor-pointer" id="invite-row-{{$invite->id}}" onclick="selectInvitation({{ $invite->id }}, this)">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($invite->first_name, 0, 1) . substr($invite->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ $invite->first_name }} {{ $invite->last_name }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $invite->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $isExpired = $invite->isExpired();
                                        $status = $isExpired && $invite->status === 'pending' ? 'expired' : $invite->status;
                                        $badgeClasses = [
                                            'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'accepted'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'expired'   => 'bg-red-50 text-red-700 border-red-200',
                                            'cancelled' => 'bg-gray-50 text-gray-700 border-gray-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeClasses[$status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5 {{ $invite->email_status === 'sent' ? 'text-blue-600' : 'text-gray-300' }}">
                                            <i class="bi bi-envelope-fill text-xs"></i>
                                            <span class="text-[10px] font-bold uppercase">Email</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 {{ $invite->sms_status === 'sent' ? 'text-emerald-600' : 'text-gray-300' }}">
                                            <i class="bi bi-chat-left-dots-fill text-xs"></i>
                                            <span class="text-[10px] font-bold uppercase">SMS</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $hoursRemaining = now()->diffInHours($invite->expires_at, false);
                                        $isExpiringSoon = $hoursRemaining > 0 && $hoursRemaining <= 48 && $status === 'pending';
                                    @endphp
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold {{ $isExpiringSoon ? 'text-amber-600' : 'text-gray-700' }}">
                                            {{ $invite->expires_at->format('M d, Y') }}
                                        </span>
                                        @if($isExpiringSoon)
                                            <span class="text-[10px] font-bold text-amber-500 uppercase flex items-center gap-1">
                                                <i class="bi bi-exclamation-circle-fill text-[8px]"></i> Expiring Soon
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-gray-500 transition-colors"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="bi bi-envelope-paper text-4xl mb-3 opacity-50"></i>
                                        <p class="text-sm font-medium">No invitations found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 border-t border-gray-100 bg-white">
                    {{-- Pagination removed for internal scrolling --}}
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-span-4 flex flex-col gap-5">
            <div class="bg-white p-5 rounded-xl border border-gray-200 flex items-center gap-4 shadow-sm h-[92px]">
                <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-red-500">
                    <i class="bi bi-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Expired</p>
                    <span class="text-2xl font-bold text-gray-900">{{ $stats['expired'] }}</span>
                </div>
            </div>
            
            @if($invitations->isNotEmpty())
            {{-- Details Panel Section (Right Side) --}}
            <div class="flex-1 flex flex-col gap-4 overflow-y-auto custom-scrollbar pr-1">
                {{-- Resident Profile Card --}}
                <div class="collapsible-section open bg-white border border-gray-200 rounded-xl shadow-sm transition-all duration-300">
                    <button class="collapsible-header w-full px-5 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors rounded-t-xl">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-person-circle text-gray-400"></i> Resident Profile
                        </h3>
                        <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="collapsible-content p-6 text-center">
                        <div id="panel-avatar" class="w-20 h-20 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center font-bold text-2xl mx-auto mb-4 border-2 border-white shadow-sm">
                            {{ strtoupper(substr($invitations->first()->first_name ?? 'R', 0, 1) . substr($invitations->first()->last_name ?? '', 0, 1)) }}
                        </div>
                        <h2 id="panel-name" class="text-xl font-bold text-gray-900 mb-1">{{ ($invitations->first()->first_name ?? 'Resident') . ' ' . ($invitations->first()->last_name ?? 'Name') }}</h2>
                        <p id="panel-email" class="text-sm text-gray-400">{{ $invitations->first()->email ?? '' }}</p>
                    </div>
                </div>

                {{-- Actions Panel --}}
                <div class="collapsible-section open bg-white border border-gray-200 rounded-xl shadow-sm transition-all duration-300">
                    <button class="collapsible-header w-full px-5 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors rounded-t-xl">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-lightning-charge text-gray-400"></i> Actions
                        </h3>
                        <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="collapsible-content p-5 space-y-3">
                        <button id="btn-copy" onclick="copyInviteLink('{{ route('register.invitation', ['token' => $invitations->first()->token ?? 'token']) }}')" @class([$btn_primary, 'w-full'])>
                            <i class="bi bi-link-45deg text-lg"></i> Copy Invitation Link
                        </button>
                        <div class="grid grid-cols-2 gap-3">
                            <button id="btn-resend" onclick="resendInvite({{ $invitations->first()->id ?? 0 }})" @class([$btn_secondary])>
                                <i class="bi bi-send"></i> Resend
                            </button>
                            <button id="btn-renew" onclick="renewInvite({{ $invitations->first()->id ?? 0 }})" @class([$btn_secondary])>
                                <i class="bi bi-arrow-clockwise"></i> Renew
                            </button>
                        </div>
                        <div class="grid gap-3 pt-3 border-t border-gray-100">
                            <button id="btn-cancel" onclick="cancelInvite({{ $invitations->first()->id ?? 0 }})" @class([$btn_secondary, 'text-red-600 border-red-100 hover:bg-red-50 w-full'])>
                                <i class="bi bi-trash"></i> Cancel Invitation
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Message Preview Panel --}}
                <div class="collapsible-section open bg-white border border-gray-200 rounded-xl shadow-sm transition-all duration-300">
                    <button class="collapsible-header w-full px-5 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors rounded-t-xl">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-eye text-gray-400"></i> Message Preview
                        </h3>
                        <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="collapsible-content p-5">
                        <div class="flex p-1 bg-gray-100 rounded-lg mb-4">
                            <button onclick="switchTab('email')" id="tab-btn-email" class="flex-1 px-3 py-1 bg-white text-gray-900 rounded-md text-[10px] font-bold shadow-sm transition-all">EMAIL</button>
                            <button onclick="switchTab('sms')" id="tab-btn-sms" class="flex-1 px-3 py-1 text-gray-500 rounded-md text-[10px] font-bold transition-all">SMS</button>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div id="preview-email" class="text-xs text-gray-600 space-y-3">
                                <p>Hello <span class="font-bold text-gray-900 preview-name">{{ ($invitations->first()->first_name ?? 'Resident') . ' ' . ($invitations->first()->last_name ?? '') }}</span>,</p>
                                <p>You're invited to register for {{ config('app.name') }}.</p>
                                <p class="font-mono text-blue-600 underline break-all preview-link">{{ route('register.invitation', ['token' => $invitations->first()->token ?? 'token']) }}</p>
                            </div>
                            <div id="preview-sms" class="hidden text-xs text-gray-600">
                                <p class="leading-relaxed">Hello <span class="font-bold text-gray-900 preview-name">{{ ($invitations->first()->first_name ?? 'Resident') . ' ' . ($invitations->first()->last_name ?? '') }}</span>, register for {{ config('app.name') }} here: <span class="font-mono text-blue-600 underline break-all preview-link">{{ route('register.invitation', ['token' => $invitations->first()->token ?? 'token']) }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Activity Timeline Panel --}}
                <div class="collapsible-section open bg-white border border-gray-200 rounded-xl shadow-sm mb-2 transition-all duration-300">
                    <button class="collapsible-header w-full px-5 py-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 hover:bg-gray-100/50 transition-colors rounded-t-xl">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-clock-history text-gray-400"></i> Activity Timeline
                        </h3>
                        <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="collapsible-content p-6">
                        <div id="timeline-content" class="space-y-6 relative before:absolute before:inset-y-0 before:left-2 before:w-0.5 before:bg-gray-100">
                            {{-- Timeline items will be injected by JS --}}
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="flex-1 bg-white border border-gray-200 rounded-xl shadow-sm flex items-center justify-center p-8 text-center">
                <div class="space-y-3">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto text-gray-300">
                        <i class="bi bi-person-plus text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">No invitation selected</p>
                        <p class="text-xs text-gray-400 mt-1">Select an invitation from the list or create a new one to see details.</p>
                    </div>
                    <button onclick="openInviteModal()" @class([$btn_primary, 'mt-4'])>
                        Invite Resident
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MODALS --}}
@push('modals')
<div id="invite-modal" class="fixed inset-0 z-[60] invisible opacity-0 transition-all duration-300 ease-in-out bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-out" id="invite-modal-panel">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Invite New Resident</h2>
            <button onclick="closeInviteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="create-invite-form" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">First Name</label>
                    <input type="text" name="first_name" required class="w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-900 transition-all focus:outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Last Name</label>
                    <input type="text" name="last_name" required class="w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-900 transition-all focus:outline-none">
                </div>
            </div>
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" required class="w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-900 transition-all focus:outline-none">
            </div>
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Phone Number (Optional)</label>
                <input type="text" name="phone" class="w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-900 transition-all focus:outline-none">
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="button" onclick="closeInviteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition-all shadow-md active:scale-95">Send Invitation</button>
            </div>
        </form>
    </div>
</div>
@endpush

<style>
.custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
tr.selected { background-color: #f8fafc !important; border-left: 3px solid #111827 !important; }
.collapsible-section:not(.open) .collapsible-content { display: none; }
.collapsible-section.open .collapsible-content { display: block !important; visibility: visible !important; height: auto !important; }
.collapsible-section.open .bi-chevron-down { transform: rotate(180deg); }
</style>

<script>
let currentInvitationId = {{ $invitations->first()->id ?? 'null' }};

document.addEventListener('DOMContentLoaded', () => {
    // Collapsible Logic
    document.querySelectorAll('.collapsible-header').forEach(header => {
        header.addEventListener('click', () => {
            const section = header.parentElement;
            section.classList.toggle('open');
        });
    });

    // Search function
    document.getElementById('invitation-search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;
        rows.forEach(row => {
            const name = row.querySelector('td:first-child').textContent.toLowerCase();
            if (name.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        document.getElementById('visible-count').textContent = visibleCount;
    });

    // Auto-select first item if exists
    const firstRow = document.querySelector('tbody tr:not(.empty-row)');
    if (firstRow && currentInvitationId) {
        selectInvitation(currentInvitationId, firstRow);
    }

    // CREATE INVITATION FORM HANDLER
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
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error creating invitation');
            }
        } catch (e) {
            alert('An error occurred');
        }
    });
});

function openInviteModal() {
    const modal = document.getElementById('invite-modal');
    const panel = document.getElementById('invite-modal-panel');
    modal.classList.remove('invisible', 'opacity-0');
    setTimeout(() => {
        panel.classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closeInviteModal() {
    const modal = document.getElementById('invite-modal');
    const panel = document.getElementById('invite-modal-panel');
    panel.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('invisible', 'opacity-0');
    }, 300);
}

function switchTab(type) {
    const emailBtn = document.getElementById('tab-btn-email');
    const smsBtn = document.getElementById('tab-btn-sms');
    const emailPreview = document.getElementById('preview-email');
    const smsPreview = document.getElementById('preview-sms');

    if (type === 'email') {
        emailBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
        emailBtn.classList.remove('text-gray-500');
        smsBtn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
        smsBtn.classList.add('text-gray-500');
        emailPreview.classList.remove('hidden');
        smsPreview.classList.add('hidden');
    } else {
        smsBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
        smsBtn.classList.remove('text-gray-500');
        emailBtn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
        emailBtn.classList.add('text-gray-500');
        smsPreview.classList.remove('hidden');
        emailPreview.classList.add('hidden');
    }
}

async function selectInvitation(id, rowElement) {
    currentInvitationId = id;
    document.querySelectorAll('tbody tr').forEach(tr => tr.classList.remove('selected'));
    rowElement.classList.add('selected');

    try {
        const response = await fetch(`/admin/invitations/${id}`);
        const result = await response.json();

        if (result.success) {
            const data = result.data;
            const fullName = `${data.first_name} ${data.last_name}`;
            
            // Update basic info
            document.getElementById('panel-name').textContent = fullName;
            document.getElementById('panel-email').textContent = data.email;
            document.getElementById('panel-avatar').textContent = (data.first_name[0] + data.last_name[0]).toUpperCase();
            
            // Update buttons
            const resendBtn = document.getElementById('btn-resend');
            resendBtn.disabled = data.is_expired;
            
            resendBtn.setAttribute('onclick', `resendInvite(${data.id})`);
            document.getElementById('btn-renew').setAttribute('onclick', `renewInvite(${data.id})`);
            document.getElementById('btn-copy').setAttribute('onclick', `copyInviteLink('${data.registration_link}')`);
            document.getElementById('btn-cancel').setAttribute('onclick', `cancelInvite(${data.id})`);

            // Update message previews
            document.querySelectorAll('.preview-name').forEach(el => el.textContent = fullName);
            document.querySelectorAll('.preview-link').forEach(el => el.textContent = data.registration_link);

            // Update activity timeline
            const timeline = document.getElementById('timeline-content');
            timeline.innerHTML = '';
            data.activity.forEach(item => {
                const activityEl = `
                    <div class="flex items-start gap-4 relative">
                        <div class="w-4 h-4 rounded-full ${item.icon_bg} mt-1 ring-4 ring-white z-10 border-2 border-white shadow-sm"></div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">${item.title}</p>
                            <p class="text-[11px] text-gray-400 font-medium">${item.time}</p>
                        </div>
                    </div>`;
                timeline.insertAdjacentHTML('beforeend', activityEl);
            });

        }
    } catch (e) {
        console.error('Error fetching invitation details:', e);
    }
}

async function resendInvite(id) {
    if(!confirm('Resend the current invitation link?')) return;
    try {
        const response = await fetch(`/admin/invitations/${id}/resend`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
        const data = await response.json();
        alert(data.message || 'An error occurred');
        if(data.success) location.reload();
    } catch (e) { alert('An error occurred'); }
}

async function renewInvite(id) {
    if(!confirm('Generate a NEW link and refresh the 7-day expiry?')) return;
    try {
        const response = await fetch(`/admin/invitations/${id}/renew`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
        const result = await response.json();
        if(result.success) {
            navigator.clipboard.writeText(result.link).then(() => {
                alert('Invitation renewed! The NEW link has been copied to your clipboard.');
                location.reload();
            });
        } else { alert(result.message || 'Failed to renew'); }
    } catch (e) { alert('An error occurred'); }
}

async function cancelInvite(id) {
    if(!confirm('Are you sure you want to cancel this invitation?')) return;
    try {
        const response = await fetch(`/admin/invitations/${id}/cancel`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
        const data = await response.json();
        if(data.success) location.reload();
        else alert(data.message || 'Failed to cancel');
    } catch (e) { alert('An error occurred'); }
}

function copyInviteLink(link) {
    navigator.clipboard.writeText(link).then(() => alert('Invitation link copied to clipboard!'));
}
</script>
@endsection
