@extends('resident.layouts.app')

@section('title', 'Upcoming Events')
@section('page-title', 'Upcoming Events')

@section('content')
@php use Carbon\Carbon; @endphp
<div class="space-y-8">
    <x-resident-hero-header 
        label="Community Events" 
        icon="bi-calendar-event-fill"
        title="Upcoming Events" 
        description="See the latest subdivision events and add them to your schedule."
    />

    <div class="glass-card p-6">
        <div class="grid gap-6">
            @forelse($events as $event)
                <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-black text-[#1F2937]">{{ $event['title'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ Carbon::parse($event['date'])->format('F j, Y') }} at {{ $event['time'] }}</p>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 text-emerald-700 px-4 py-2 text-xs font-black uppercase tracking-[0.25em]">
                            <i class="bi bi-geo-alt"></i> {{ $event['location'] }} 
                        </span>
                    </div>
                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-3xl bg-slate-50 px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-black">Event ID</p>
                            <p class="text-sm font-semibold text-[#1F2937] mt-1">{{ $event['id'] }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-black">Date</p>
                            <p class="text-sm font-semibold text-[#1F2937] mt-1">{{ Carbon::parse($event['date'])->format('M d') }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-black">Time</p>
                            <p class="text-sm font-semibold text-[#1F2937] mt-1">{{ $event['time'] }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 text-gray-500">
                    <p class="text-lg font-semibold">No upcoming events scheduled.</p>
                    <p class="mt-3 text-sm">Check back later for community updates and announcements.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
