@extends('resident.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 mt-6">

    {{-- ========================= --}}
    {{-- PROFILE SECTION --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden">
        
        {{-- Background Decoration --}}
        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-blue-600 to-blue-800 opacity-90"></div>

        <div class="relative pt-12 flex flex-col md:flex-row gap-8 items-start">
            
            {{-- PHOTO & STATUS --}}
            <div class="flex flex-col items-center">
                <div class="relative">
                    <img
                        src="{{ ($resident && $resident->photo) ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}"
                        onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                        alt="Profile Photo"
                        class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md bg-white"
                    >
                    <a href="{{ route('resident.profile.edit') }}" class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center shadow hover:bg-blue-700 transition" title="Edit Profile">
                        <i class="bi bi-pencil-fill text-xs"></i>
                    </a>
                </div>
                
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $resident->first_name ?? 'Resident' }} {{ $resident->last_name ?? '' }}</h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide mt-2
                    {{ ($resident && $resident->status === 'active') ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                    {{ ucfirst($resident->status ?? 'unknown') }}
                </span>
            </div>

            {{-- INFO GRID --}}
            <div class="flex-1 w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $infoCards = [
                            ['icon'=>'bi-telephone-fill', 'label'=>'Contact Number', 'value'=>$resident->contact, 'color'=>'blue'],
                            ['icon'=>'bi-envelope-fill', 'label'=>'Email Address', 'value'=>$resident->email, 'color'=>'purple'],
                            ['icon'=>'bi-geo-alt-fill', 'label'=>'Block / Lot', 'value'=>'Block ' . ($resident->block ?? '-') . ' / Lot ' . ($resident->lot ?? '-'), 'color'=>'orange'],
                            ['icon'=>'bi-calendar-check-fill', 'label'=>'Move-in Date', 'value'=>$resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-', 'color'=>'emerald'],
                            ['icon'=>'bi-house-fill', 'label'=>'Member Type', 'value'=>$resident->membership_type ?? 'Homeowner', 'color'=>'cyan'],
                        ];
                    @endphp

                    @foreach($infoCards as $card)
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-gray-200 transition-colors">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-{{ $card['color'] }}-600 shadow-sm border border-gray-100 shrink-0">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">{{ $card['label'] }}</p>
                            <p class="text-sm font-semibold text-gray-900 break-all">{{ $card['value'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- PROPERTY & ACCOUNT --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ACCOUNT STATUS --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2 mb-6">
                <i class="bi bi-shield-check text-emerald-600"></i> Account Status
            </h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Payment Status</span>
                    @php
                        $isGoodStanding = $resident->payment_status === 'Good Standing';
                    @endphp
                    <span class="text-sm font-bold {{ $isGoodStanding ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-amber-600 bg-amber-50 border-amber-100' }} px-3 py-1 rounded-full border">
                        {{ $resident->payment_status }}
                    </span>
                </div>

                <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Membership Type</span>
                    <span class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                        {{ $resident->membership_type ?? 'Regular Member' }}
                    </span>
                </div>

                <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Member Since</span>
                    <span class="text-sm font-bold text-gray-900">{{ $resident->move_in_date ? $resident->move_in_date->year : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- PROPERTY DETAILS --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2 mb-6">
                <i class="bi bi-houses-fill text-orange-500"></i> Property Details
            </h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Property Type</span>
                    <span class="text-sm font-bold text-gray-900">{{ $resident->property_type ?? 'Residential House & Lot' }}</span>
                </div>

                <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Lot Area</span>
                    <span class="text-sm font-bold text-gray-900">{{ $resident->lot_area ? number_format($resident->lot_area, 0) . ' sq.m' : 'Not Available' }}</span>
                </div>

                <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Floor Area</span>
                    <span class="text-sm font-bold text-gray-900">{{ $resident->floor_area ? number_format($resident->floor_area, 0) . ' sq.m' : 'Not Available' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
