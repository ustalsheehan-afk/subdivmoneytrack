@extends('resident.layouts.app')
@section('title', 'My Requests')
@section('page-title', 'Service Requests')

@section('content')
<div class="space-y-8" x-data="{ filter: 'all' }">

        <x-resident-hero-header 
            label="Resident Services" 
            icon="bi-inbox-fill"
            title="Service Requests" 
            description="Track and manage your subdivision requests, maintenance reports, and service inquiries."
            :tabs="[
                ['id' => 'all', 'label' => 'All', 'icon' => 'bi-grid-fill', 'click' => 'filter = \'all\'', 'active_condition' => 'filter === \'all\''],
                ['id' => 'pending', 'label' => 'Pending', 'icon' => 'bi-clock-history', 'click' => 'filter = \'pending\'', 'active_condition' => 'filter === \'pending\''],
                ['id' => 'in progress', 'label' => 'In Progress', 'icon' => 'bi-gear-fill', 'click' => 'filter = \'in progress\'', 'active_condition' => 'filter === \'in progress\''],
                ['id' => 'completed', 'label' => 'Completed', 'icon' => 'bi-check-circle-fill', 'click' => 'filter = \'completed\'', 'active_condition' => 'filter === \'completed\''],
            ]"
        >
            <x-slot name="actions">
                <a href="{{ route('resident.requests.create') }}" class="btn-premium">
                    <i class="bi bi-plus-lg"></i>
                    New Request
                </a>
            </x-slot>
        </x-resident-hero-header>

        {{-- ========================= --}}
        {{-- REQUESTS TABLE CARD --}}
        {{-- ========================= --}}
        <div class="glass-card overflow-hidden relative">
            
            <div class="overflow-x-auto relative z-10">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Request Details</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Timeline</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Current Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @forelse($requests as $request)
                            @php
                                $statusMap = [
                                    'pending'    => ['bg' => 'bg-amber-500/10',   'text' => 'text-amber-600',   'border' => 'border-amber-500/20', 'icon' => 'bi-clock-history'],
                                    'in progress' => ['bg' => 'bg-blue-500/10',    'text' => 'text-blue-600',    'border' => 'border-blue-500/20',  'icon' => 'bi-gear-fill'],
                                    'completed'  => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600', 'border' => 'border-emerald-500/20', 'icon' => 'bi-check-circle-fill'],
                                    'approved'   => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600', 'border' => 'border-emerald-500/20', 'icon' => 'bi-shield-check'],
                                    'rejected'   => ['bg' => 'bg-red-500/10',     'text' => 'text-red-600',     'border' => 'border-red-500/20',     'icon' => 'bi-x-circle-fill'],
                                ];
                                $style = $statusMap[strtolower($request->status)] ?? ['bg' => 'bg-gray-500/10', 'text' => 'text-gray-600', 'border' => 'border-gray-500/20', 'icon' => 'bi-info-circle'];
                            @endphp

                            <tr class="group hover:bg-gray-50/80 transition-all duration-300 cursor-pointer" 
                                onclick="window.location='{{ route('resident.requests.show', $request->id) }}'"
                                x-show="filter === 'all' || filter === '{{ strtolower($request->status) }}'">
                                
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="relative shrink-0">
                                            @if($request->photo_url)
                                                <img src="{{ $request->photo_url }}" 
                                                     class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-md group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <div class="w-14 h-14 rounded-2xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors">
                                                    <i class="bi bi-image text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-gray-900 tracking-tight capitalize mb-1">{{ $request->type }}</p>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest line-clamp-1 group-hover:text-gray-600 transition-colors">
                                                {{ $request->description ?: 'No description provided' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900 tracking-tight">{{ $request->created_at->format('M d, Y') }}</span>
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $request->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-center">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $style['bg'] }} {{ $style['text'] }} {{ $style['border'] }} shadow-sm">
                                        <i class="bi {{ $style['icon'] }} text-xs"></i>
                                        {{ $request->status }}
                                    </span>
                                </td>

                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation()">
                                        <a href="{{ route('resident.requests.show', $request->id) }}" 
                                           class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition-all duration-300 hover:border-[#B6FF5C] hover:bg-[#B6FF5C]/15 hover:text-slate-900"
                                           title="View Details">
                                            <i class="bi bi-eye-fill text-sm"></i>
                                        </a>
                                        @if($request->status == 'pending')
                                            <a href="{{ route('resident.requests.edit', $request->id) }}" 
                                               class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition-all duration-300 hover:border-[#B6FF5C] hover:bg-[#B6FF5C]/15 hover:text-slate-900"
                                               title="Edit Request">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>

                                            <form action="{{ route('resident.requests.destroy', $request->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this request? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition-all duration-300 hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"
                                                        title="Delete Request">
                                                    <i class="bi bi-trash3-fill text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-24 text-center">
                                    <div class="relative inline-block mb-6">
                                        <div class="absolute inset-0 bg-emerald-500/10 rounded-full blur-2xl animate-pulse"></div>
                                        <div class="relative w-20 h-20 bg-gray-50 rounded-full border border-dashed border-gray-200 flex items-center justify-center mx-auto">
                                            <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight mb-2">No Service Requests</h3>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest max-w-xs mx-auto">
                                        You haven't submitted any service requests yet.
                                    </p>
                                    <div class="mt-8">
                                        <a href="{{ route('resident.requests.create') }}" 
                                           class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-500 text-black text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-400 transition-all duration-300">
                                            Submit Your First Request
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</div>
@endsection
