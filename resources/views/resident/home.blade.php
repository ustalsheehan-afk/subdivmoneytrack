@extends('resident.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
        <div class="relative h-40 sm:h-48 lg:h-56 overflow-hidden bg-[#081412] rounded-[1.5rem]">
            <video class="absolute inset-0 w-full h-full object-cover opacity-50" autoplay muted loop playsinline>
                <source src="{{ asset('videos/subdivision-hero.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-black/20"></div>

            <div class="relative z-10 h-full flex flex-col justify-end p-4 sm:p-6 lg:p-10">
                <p class="text-[10px] font-black text-white/70 uppercase tracking-[0.25em] mb-2">
                    {{ now()->format('l, F j, Y') }}
                </p>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Welcome back, {{ $resident->first_name }}.
                </h1>
                <p class="text-sm sm:text-[13px] text-white/75 font-medium mt-1 max-w-2xl">
                    Your community portal for payments, requests, and updates.
                </p>
            </div>
        </div>

        {{-- 1. UPCOMING PAYMENT (Priority Action) --}}
        @if($summary['next_due_date'])
        <section class="glass-card p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700 shrink-0">
                    <i class="bi bi-clock-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Upcoming Payment</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight leading-none mb-1">
                        ₱{{ number_format($summary['next_due_amount'], 2) }}
                    </h2>
                    <p class="text-sm font-bold text-gray-700">
                        {{ $summary['next_due_title'] }} <span class="text-gray-300 mx-1">•</span>
                        <span class="text-gray-500 font-bold">Due {{ $summary['next_due_date']->format('F j, Y') }}</span>
                    </p>
                    <p class="text-[12px] font-bold text-gray-500 mt-2">
                        Total balance: <span class="text-gray-900 font-black">₱{{ number_format($summary['outstanding_dues'], 2) }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('resident.payments.pay', $summary['next_due_id']) }}" class="btn-premium w-full lg:w-auto justify-center">
                Pay Now
            </a>
        </section>
        @endif

        {{-- 2. QUICK ACTIONS (Uniform Cinematic Design) --}}
        <section class="space-y-4">
            <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.25em] px-1">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                {{-- Pay Dues --}}
                <a href="{{ route('resident.payments.index') }}"
                   class="glass-card p-6 flex flex-col items-center justify-center text-center gap-4 group relative overflow-hidden">
                    
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-[#081412] group-hover:text-[var(--brand-accent)] group-hover:border-[rgba(182,255,92,0.25)] transition-all duration-300 shadow-sm">
                        <i class="bi bi-credit-card text-2xl"></i>
                    </div>
                    
                    <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Pay Dues</p>
                </a>

                {{-- Submit Request --}}
                <a href="{{ route('resident.requests.create') }}"
                   class="glass-card p-6 flex flex-col items-center justify-center text-center gap-4 group relative overflow-hidden">
                    
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-[#081412] group-hover:text-[var(--brand-accent)] group-hover:border-[rgba(182,255,92,0.25)] transition-all duration-300 shadow-sm">
                        <i class="bi bi-file-earmark-text text-2xl"></i>
                    </div>
                    
                    <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Submit Request</p>
                </a>

                {{-- Reservations --}}
                <a href="{{ route('resident.amenities.index') }}"
                   class="glass-card p-6 flex flex-col items-center justify-center text-center gap-4 group relative overflow-hidden">
                    
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-[#081412] group-hover:text-[var(--brand-accent)] group-hover:border-[rgba(182,255,92,0.25)] transition-all duration-300 shadow-sm">
                        <i class="bi bi-calendar-event text-2xl"></i>
                    </div>
                    
                    <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Reservations</p>
                </a>

            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- LEFT COLUMN: Recent Activity --}}
            <div class="lg:col-span-7 glass-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.25em] flex items-center gap-2">
                        <i class="bi bi-clock-history text-emerald-600"></i> Recent Activity
                    </h3>
                    <a href="#" class="meta font-normal uppercase tracking-widest hover:text-[#1F2937] transition-colors">View all <i class="bi bi-chevron-right ml-1"></i></a>
                </div>
                
                <div class="space-y-7 relative flex-1">
                    <div class="absolute left-[19px] top-2 bottom-2 w-px bg-slate-100"></div>
                    
                    @forelse($activityTimeline as $item)
                    <div class="relative flex gap-4 items-start group">
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 shrink-0 relative z-10 group-hover:border-slate-300 transition-colors">
                            <i class="bi {{ $item['icon'] }} text-sm"></i>
                        </div>
                        <div class="flex-1 pt-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-[15px] font-semibold text-[#1F2937] leading-relaxed">
                                    {{ $item['title'] }} 
                                    @if(isset($item['description_short']))
                                        <span class="text-[#4B5563] font-normal">— {{ $item['description_short'] }}</span>
                                    @endif
                                </h4>
                                @if(isset($item['tag']))
                                <span class="px-2 py-0.5 rounded bg-slate-50 meta font-semibold uppercase tracking-widest border border-slate-100">
                                    {{ $item['tag'] }}
                                </span>
                                @endif
                            </div>
                            <p class="meta font-normal leading-relaxed">
                                {{ $item['date']->format('M d') }} · {{ $item['date']->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="py-10 text-center meta font-normal">No recent activity.</div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT COLUMN: My Requests + Announcements --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- My Requests --}}
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.25em] flex items-center gap-2">
                            <i class="bi bi-file-earmark-text text-emerald-600"></i> My Requests
                        </h3>
                        <a href="{{ route('resident.requests.index') }}" class="meta font-normal uppercase tracking-widest hover:text-[#1F2937] transition-colors">All <i class="bi bi-chevron-right ml-1"></i></a>
                    </div>
                    <div class="space-y-5">
                        @forelse($recentRequests->take(3) as $request)
                        <a href="{{ route('resident.requests.show', $request->id) }}" class="flex items-center justify-between group no-underline rounded-3xl bg-white border border-gray-100 p-4 hover:bg-slate-50 transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-100 transition-colors">
                                    <i class="bi bi-tools text-base"></i>
                                </div>
                                <div>
                                    <p class="text-[15px] font-semibold text-[#1F2937] leading-none mb-1.5">{{ ucfirst($request->type) }}</p>
                                    <p class="meta font-normal">Submitted {{ $request->created_at->format('M d') }}</p>
                                </div>
                            </div>
                            @php
                                $statusInfo = match($request->status) {
                                    'pending' => ['cls' => 'bg-[#FFFBEB] text-[#92400E] border-[#FEF3C7]', 'label' => 'PENDING'],
                                    'approved', 'resolved', 'completed' => ['cls' => 'bg-[#F0FDF4] text-[#166534] border-[#DCFCE7]', 'label' => 'COMPLETED'],
                                    'in_progress' => ['cls' => 'bg-[#EFF6FF] text-[#1E40AF] border-[#DBEAFE]', 'label' => 'IN PROGRESS'],
                                    'rejected' => ['cls' => 'bg-[#FEF2F2] text-[#991B1B] border-[#FEE2E2]', 'label' => 'REJECTED'],
                                    default => ['cls' => 'bg-slate-50 meta border-slate-100', 'label' => strtoupper($request->status)],
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[12px] font-semibold uppercase tracking-widest border {{ $statusInfo['cls'] }}">
                                {{ $statusInfo['label'] }}
                            </span>
                        </a>
                        @empty
                        <p class="meta font-normal">No requests found.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Announcements --}}
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.25em] flex items-center gap-2">
                            <i class="bi bi-megaphone text-emerald-600"></i> Announcements
                        </h3>
                        <a href="{{ route('resident.announcements.index') }}" class="meta font-normal uppercase tracking-widest hover:text-[#1F2937] transition-colors">All <i class="bi bi-chevron-right ml-1"></i></a>
                    </div>
                    <div class="space-y-5">
                        @forelse($recentAnnouncements->take(2) as $announcement)
                        @php
                            $isUrgent = in_array(strtolower($announcement->priority ?? ''), ['urgent', 'high']) || strtolower($announcement->category) === 'emergency';
                        @endphp
                        <a href="{{ route('resident.announcements.show', $announcement->id) }}" class="block space-y-2 rounded-3xl bg-white border border-gray-100 p-4 hover:bg-slate-50 transition-all duration-200 no-underline">
                            <div class="flex justify-between items-start">
                                @if($isUrgent)
                                    <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-600 text-[12px] font-semibold uppercase tracking-widest border border-rose-100">URGENT</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-semibold uppercase tracking-widest border border-slate-200">{{ strtoupper($announcement->category ?? 'Update') }}</span>
                                @endif
                                <span class="meta font-normal">{{ $announcement->date_posted?->format('M d, Y') ?? 'Today' }}</span>
                            </div>
                            <p class="text-[15px] font-semibold text-[#1F2937] leading-relaxed">{{ $announcement->title }}</p>
                            <p class="text-[14px] text-[#4B5563] line-clamp-2 leading-relaxed font-normal">{{ strip_tags($announcement->content) }}</p>
                        </a>
                        @empty
                        <p class="meta font-normal">No announcements found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. UPCOMING EVENTS --}}
        <section class="glass-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.25em] flex items-center gap-2">
                    <i class="bi bi-calendar-event text-emerald-600"></i> Upcoming Events
                </h3>
                <a href="{{ route('resident.events.index') }}" class="meta font-normal uppercase tracking-widest hover:text-[#1F2937] transition-colors">View calendar <i class="bi bi-chevron-right ml-1"></i></a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                @forelse($upcomingEvents->take(3) as $event)
                @php $eventDate = \Carbon\Carbon::parse($event['date']); @endphp
                <div class="p-4 flex gap-4 items-start first:pl-0 last:pr-0 group">
                    <div class="w-10 h-12 rounded-lg bg-slate-50 border border-slate-100 flex flex-col items-center justify-center shrink-0 group-hover:bg-slate-100 transition-colors">
                        <span class="text-[12px] font-semibold meta uppercase leading-none mb-1">{{ $eventDate->format('M') }}</span>
                        <span class="text-lg font-semibold text-[#1F2937] leading-none">{{ $eventDate->format('d') }}</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="meta font-normal uppercase tracking-widest mb-1">{{ $eventDate->diffForHumans() }}</p>
                            <h4 class="text-[15px] font-semibold text-[#1F2937] tracking-tight leading-tight">{{ $event['title'] }}</h4>
                        </div>
                        <div class="flex flex-col gap-2 text-sm text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <i class="bi bi-clock text-[13px]"></i>
                                <p class="font-medium">{{ $event['time'] }}</p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="bi bi-geo-alt text-[13px]"></i>
                                <p class="font-medium">{{ $event['location'] }}</p>
                            </div>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('resident.events.index') }}" class="inline-flex items-center gap-2 px-5 py-2 border border-slate-200 text-[#1F2937] text-[13px] font-semibold rounded-lg hover:bg-slate-50 transition-all uppercase tracking-widest">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 meta col-span-3 text-center font-normal">No upcoming events scheduled.</div>
                @endforelse
            </div>
        </section>

</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
