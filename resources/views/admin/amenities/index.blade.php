@extends('layouts.admin')

@section('title', 'Amenities')
@section('page-title', 'Amenities Management')

@section('content')
<div class="space-y-8 animate-fade-in">

    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Amenities
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Manage subdivision facilities, set capacities, and configure booking rates.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.amenities.create') }}" class="btn-premium">
                    <i class="bi bi-plus-lg"></i>
                    Add Amenity
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card border-emerald-100 bg-emerald-50/50 p-6 animate-fade-in">
            <div class="flex items-center gap-3 text-emerald-700">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                    <i class="bi bi-check-lg text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-emerald-900 uppercase tracking-widest">Operation Successful</p>
                    <p class="text-sm font-bold text-emerald-600">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================== --}}
    {{-- TABLE CONTAINER --}}
    {{-- ===================== --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-[40%]">Amenity Facility</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-[15%]">Capacity</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-[15%]">Rate/Slot</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-[15%]">Status</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-[15%]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($amenities as $amenity)
                    <tr class="hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500">
                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    @if($amenity->image)
                                        <img src="{{ Storage::url($amenity->image) }}" class="w-14 h-14 rounded-2xl object-cover shadow-sm group-hover:scale-105 transition-transform duration-500 border border-gray-100" alt="{{ $amenity->name }}">
                                    @else
                                        <div class="w-14 h-14 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center text-2xl border border-gray-100 shadow-sm group-hover:scale-105 transition-transform duration-500">
                                            <i class="bi bi-building"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-base font-black text-gray-900 group-hover:text-emerald-700 transition-colors truncate">{{ $amenity->name }}</p>
                                    <p class="text-[11px] text-gray-500 font-medium line-clamp-1 mt-0.5">{{ $amenity->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-5 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-base font-black text-gray-900 tabular-nums">{{ $amenity->max_capacity }}</span>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-0.5">PAX Max</span>
                            </div>
                        </td>
                        <td class="p-5 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-base font-black text-emerald-600 tabular-nums">₱{{ number_format($amenity->price, 2) }}</span>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Booking Fee</span>
                            </div>
                        </td>
                        <td class="p-5 text-center">
                            @php
                                $statusColors = [
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'maintenance' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'inactive' => 'bg-red-50 text-red-700 border-red-100'
                                ];
                                $statusDots = [
                                    'active' => 'bg-emerald-500',
                                    'maintenance' => 'bg-amber-500',
                                    'inactive' => 'bg-red-500'
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColors[$amenity->status] ?? 'bg-gray-50 text-gray-600 border-gray-100' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$amenity->status] ?? 'bg-gray-400' }}"></span>
                                {{ $amenity->status }}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.amenities.edit', $amenity) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-900 text-white hover:bg-emerald-600 transition-all shadow-sm" title="Edit Amenity">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.amenities.destroy', $amenity) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this amenity?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-600 transition-all bg-white" title="Delete Amenity">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center">
                            <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <i class="bi bi-building-slash text-4xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm font-medium">No amenities have been registered yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($amenities->hasPages())
    <div class="mt-8">
        {{ $amenities->links() }}
    </div>
    @endif

</div>
@endsection
