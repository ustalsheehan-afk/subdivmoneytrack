@extends('resident.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-white pb-8 text-[#1A202C]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 space-y-6">
        
        {{-- HERO SECTION --}}
        <div class="relative h-48 sm:h-56 rounded-[20px] overflow-hidden shadow-sm bg-slate-900">
            {{-- Background Video --}}
            <video class="absolute inset-0 w-full h-full object-cover opacity-50" autoplay muted loop playsinline> 
                <source src="{{ asset('videos/subdivision-hero.mp4') }}" type="video/mp4"> 
            </video>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>

            {{-- Hero Content --}}
            <div class="relative z-10 h-full flex flex-col justify-end p-6 sm:p-10">
                <p class="meta text-white/60 uppercase tracking-[0.2em] mb-2 font-medium">
                    {{ now()->format('l, F j, Y') }}
                </p>
                <h1 class="text-2xl sm:text-4xl font-semibold text-white tracking-tight">
                    Welcome back, {{ $resident->first_name }}.
                </h1>
                <p class="text-[15px] text-white/70 font-medium mt-1 leading-relaxed">
                    Your community portal for payments and updates.
                </p>
            </div>
        </div>

        {{-- 1. UPCOMING PAYMENT (Priority Action) --}}
        @if($summary['next_due_date'])
        <section class="bg-[#FFFBEB] border border-[#FEF3C7] rounded-[20px] p-6 flex flex-col sm:flex-row items-center justify-between shadow-sm gap-6">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-[#FEF3C7] flex items-center justify-center text-[#D97706] shrink-0">
                    <i class="bi bi-clock-fill text-2xl"></i>
                </div>
                <div>
                    <p class="meta text-[#B45309] uppercase tracking-widest mb-1 font-medium">Upcoming Payment</p>
                    <h2 class="title text-[#92400E] leading-none mb-1 tracking-[0.2px] font-semibold">
                        ₱{{ number_format($summary['next_due_amount'], 2) }}
                    </h2>
                    <p class="text-[15px] font-medium text-[#B45309] leading-relaxed">
                        {{ $summary['next_due_title'] }} · <span class="meta opacity-70 font-medium">Due {{ $summary['next_due_date']->format('F j, Y') }}</span>
                    </p>
                    <p class="meta text-[#B45309]/60 mt-2 leading-relaxed font-medium">
                        Avoid penalties by paying on time. <span class="ml-2 title text-[#92400E] tracking-[0.2px] font-semibold">₱{{ number_format($summary['outstanding_dues'], 2) }} total balance</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('resident.payments.pay', $summary['next_due_id']) }}" class="w-full sm:w-auto px-10 py-3.5 bg-[#385780] text-white meta font-semibold rounded-xl hover:bg-[#2B3A4F] transition-all shadow-lg shadow-blue-900/10 text-center tracking-widest uppercase">
                Pay Now
            </a>
        </section>
        @endif

        {{-- 2. QUICK ACTIONS (Uniform Cinematic Design) --}}
        <section class="space-y-4">
            <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                {{-- Pay Dues --}}
                <a href="{{ route('resident.payments.index') }}"
                   class="bg-white rounded-[32px] border border-gray-100 shadow-sm p-8 flex flex-col items-center justify-center text-center gap-4 group hover:border-emerald-500/30 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-16 h-16 rounded-[24px] bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-500 transition-all duration-500 shadow-sm relative z-10">
                        <i class="bi bi-credit-card text-2xl"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Pay Dues</p>
                    </div>
                </a>

                {{-- Submit Request --}}
                <a href="{{ route('resident.requests.create') }}"
                   class="bg-white rounded-[32px] border border-gray-100 shadow-sm p-8 flex flex-col items-center justify-center text-center gap-4 group hover:border-emerald-500/30 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-16 h-16 rounded-[24px] bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-500 transition-all duration-500 shadow-sm relative z-10">
                        <i class="bi bi-file-earmark-text text-2xl"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Submit Request</p>
                    </div>
                </a>

                {{-- Reservations --}}
                <a href="{{ route('resident.amenities.index') }}"
                   class="bg-white rounded-[32px] border border-gray-100 shadow-sm p-8 flex flex-col items-center justify-center text-center gap-4 group hover:border-emerald-500/30 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-16 h-16 rounded-[24px] bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-500 transition-all duration-500 shadow-sm relative z-10">
                        <i class="bi bi-calendar-event text-2xl"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Reservations</p>
                    </div>
                </a>

            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- LEFT COLUMN: Recent Activity --}}
            <div class="lg:col-span-7 bg-white rounded-[20px] p-6 border border-slate-100 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="section-title uppercase tracking-[0.1em] flex items-center gap-2">
                        <i class="bi bi-clock-history text-[#385780]"></i> RECENT ACTIVITY
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
                <div class="bg-white rounded-[20px] p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="section-title uppercase tracking-[0.1em] flex items-center gap-2">
                            <i class="bi bi-file-earmark-text text-[#385780]"></i> MY REQUESTS
                        </h3>
                        <a href="{{ route('resident.requests.index') }}" class="meta font-normal uppercase tracking-widest hover:text-[#1F2937] transition-colors">All <i class="bi bi-chevron-right ml-1"></i></a>
                    </div>
                    <div class="space-y-5">
                        @forelse($recentRequests->take(3) as $request)
                        <div class="flex items-center justify-between group">
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
                        </div>
                        @empty
                        <p class="meta font-normal">No requests found.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Announcements --}}
                <div class="bg-white rounded-[20px] p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="section-title uppercase tracking-[0.1em] flex items-center gap-2">
                            <i class="bi bi-megaphone text-[#385780]"></i> ANNOUNCEMENTS
                        </h3>
                        <a href="{{ route('resident.announcements.index') }}" class="meta font-normal uppercase tracking-widest hover:text-[#1F2937] transition-colors">All <i class="bi bi-chevron-right ml-1"></i></a>
                    </div>
                    <div class="space-y-5">
                        @forelse($recentAnnouncements->take(2) as $announcement)
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-600 text-[12px] font-semibold uppercase tracking-widest border border-rose-100">URGENT</span>
                                <span class="meta font-normal">Today</span>
                            </div>
                            <p class="text-[15px] font-semibold text-[#1F2937] leading-relaxed">{{ $announcement->title }}</p>
                            <p class="text-[14px] text-[#4B5563] line-clamp-2 leading-relaxed font-normal">{{ strip_tags($announcement->content) }}</p>
                        </div>
                        @empty
                        <p class="meta font-normal">No announcements found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. UPCOMING EVENTS --}}
        <section class="bg-white rounded-[20px] p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="section-title uppercase tracking-[0.1em] flex items-center gap-2">
                    <i class="bi bi-calendar-event text-[#385780]"></i> UPCOMING EVENTS
                </h3>
                <a href="#" class="meta font-normal uppercase tracking-widest hover:text-[#1F2937] transition-colors">View calendar <i class="bi bi-chevron-right ml-1"></i></a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                @forelse($upcomingEvents->take(3) as $event)
                <div class="p-4 flex gap-4 items-start first:pl-0 last:pr-0 group">
                    <div class="w-10 h-12 rounded-lg bg-slate-50 border border-slate-100 flex flex-col items-center justify-center shrink-0 group-hover:bg-slate-100 transition-colors">
                        <span class="text-[12px] font-semibold meta uppercase leading-none mb-1">{{ $event->date_posted->format('M') }}</span>
                        <span class="text-lg font-semibold text-[#1F2937] leading-none">{{ $event->date_posted->format('d') }}</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="meta font-normal uppercase tracking-widest mb-1">TOMORROW</p>
                            <h4 class="text-[15px] font-semibold text-[#1F2937] tracking-tight leading-tight">{{ $event->title }}</h4>
                        </div>
                        <div class="flex items-center gap-1.5 meta">
                            <i class="bi bi-geo-alt text-[13px]"></i>
                            <p class="text-[13px] font-normal">Function Hall, 2F</p>
                        </div>
                        <button class="px-5 py-1.5 border border-slate-200 text-[#1F2937] text-[13px] font-semibold rounded-lg hover:bg-slate-50 transition-all uppercase tracking-widest">RSVP</button>
                    </div>
                </div>
                @empty
                <div class="p-4 meta col-span-3 text-center font-normal">No upcoming events scheduled.</div>
                @endforelse
            </div>
        </section>

    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
