@extends('resident.layouts.app')
@section('title', 'Request Details')

@section('content')

<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">

<div class="max-w-6xl mx-auto space-y-6">

{{-- Header --}}
<div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">

<div class="text-center md:text-left">

<div class="flex items-center gap-3 mb-1">

<span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-md border border-blue-100">
Service Ticket
</span>

<span class="text-sm text-gray-400">
#{{ str_pad($requestItem->id, 4, '0', STR_PAD_LEFT) }}
</span>

</div>

<h1 class="text-2xl font-bold text-gray-900 capitalize">
{{ $requestItem->type }}
</h1>

<p class="text-sm text-gray-500">
Submitted {{ $requestItem->created_at->diffForHumans() }}
</p>

</div>

<a href="{{ route('resident.requests.index') }}"
class="px-4 py-2 bg-gray-100 border border-gray-200 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-200 transition flex items-center gap-2">

<i class="bi bi-arrow-left"></i>
Back

</a>

</div>



<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

{{-- LEFT SIDE --}}
<div class="lg:col-span-2 space-y-6">

{{-- Description --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
<i class="bi bi-text-left text-blue-600"></i>
Request Description
</h3>

<div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-gray-700 leading-relaxed">
{{ $requestItem->description }}
</div>

@if($requestItem->photo)

<div class="mt-6 space-y-2">

<p class="text-xs font-semibold text-gray-500">
Attached Photo
</p>

<img src="{{ asset('storage/' . $requestItem->photo) }}"
class="max-h-80 rounded-lg border border-gray-200 shadow cursor-zoom-in"
onclick="window.open(this.src,'_blank')">

</div>

@endif

</div>

</div>



{{-- RIGHT SIDE --}}
<div class="space-y-6">

{{-- Request Details --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-900 mb-4">
Request Details
</h3>

<div class="space-y-3 text-sm">

<div class="flex justify-between">

<span class="text-gray-500">Status</span>

<span class="px-2 py-0.5 rounded-full text-xs font-semibold
@if($requestItem->status == 'pending') bg-gray-100 text-gray-700
@elseif($requestItem->status == 'in progress') bg-blue-100 text-blue-700
@elseif($requestItem->status == 'completed') bg-green-100 text-green-700
@endif">

{{ ucfirst($requestItem->status) }}

</span>

</div>

<div class="flex justify-between">

<span class="text-gray-500">Priority</span>

<span class="px-2 py-0.5 rounded-full text-xs font-semibold
@if(strtolower($requestItem->priority) == 'high') bg-red-100 text-red-700
@elseif(strtolower($requestItem->priority) == 'medium') bg-yellow-100 text-yellow-700
@else bg-green-100 text-green-700
@endif">

{{ $requestItem->priority }}

</span>

</div>

<div class="flex justify-between">

<span class="text-gray-500">Type</span>

<span class="font-medium text-gray-800">
{{ $requestItem->type }}
</span>

</div>

</div>

</div>



{{-- Request Progress --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
<i class="bi bi-clock-history text-blue-600"></i>
Request Progress
</h3>

<div class="relative pl-6 space-y-6">

<div class="absolute left-[6px] top-2 bottom-2 w-[2px] bg-gray-200"></div>


{{-- Submitted --}}
<div class="relative">

<div class="absolute -left-[10px] top-1 w-3 h-3 rounded-full bg-blue-500 border-2 border-white"></div>

<p class="text-sm font-semibold text-gray-900">
Submitted
</p>

<p class="text-xs text-blue-600">
{{ $requestItem->created_at->format('M d, Y • g:i A') }}
</p>

<p class="text-xs text-gray-500">
Your request has been received and added to the maintenance queue.
</p>

</div>



{{-- Processing --}}
@php
$isInProgress = in_array($requestItem->status,['in progress','completed']);
$progressDate = $requestItem->processed_at ?? ($isInProgress ? $requestItem->updated_at : null);
@endphp

<div class="relative {{ !$isInProgress ? 'opacity-50' : '' }}">

<div class="absolute -left-[10px] top-1 w-3 h-3 rounded-full {{ $isInProgress ? 'bg-amber-500' : 'bg-gray-300' }} border-2 border-white"></div>

<p class="text-sm font-semibold text-gray-900">
Processing
</p>

@if($progressDate)

<p class="text-xs text-amber-600">
{{ $progressDate->format('M d, Y • g:i A') }}
</p>

@endif

<p class="text-xs text-gray-500">
Our maintenance team is currently working on the request.
</p>

</div>



{{-- Completed --}}
@php
$isCompleted = $requestItem->status == 'completed';
$completedDate = $requestItem->completed_at ?? ($isCompleted ? $requestItem->updated_at : null);
@endphp

<div class="relative {{ !$isCompleted ? 'opacity-50' : '' }}">

<div class="absolute -left-[10px] top-1 w-3 h-3 rounded-full {{ $isCompleted ? 'bg-emerald-500' : 'bg-gray-300' }} border-2 border-white"></div>

<p class="text-sm font-semibold text-gray-900">
Completed
</p>

@if($completedDate)

<p class="text-xs text-emerald-600">
{{ $completedDate->format('M d, Y • g:i A') }}
</p>

@endif

<p class="text-xs text-gray-500">
The request has been successfully resolved.
</p>

</div>

</div>

</div>



{{-- Support --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 text-center">

<div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
<i class="bi bi-info-circle"></i>
</div>

<p class="text-sm font-semibold text-gray-900 mb-1">
Need to update?
</p>

<p class="text-xs text-gray-500 mb-4">
If you need to add more information, you can edit the request while it is still pending.
</p>

@if($requestItem->status == 'pending')

<a href="{{ route('resident.requests.edit', $requestItem->id) }}"
class="text-blue-600 text-sm font-medium hover:underline">

Edit Request

</a>

@endif

</div>

</div>

</div>

</div>

@endsection