@extends('resident.layouts.app')
@section('title', 'Request Details')
@section('page-title', 'Request Details')

@php
    $statusValue = strtolower(trim((string) $requestItem->status));
    $priorityValue = strtolower(trim((string) $requestItem->priority));

    $statusStyles = [
        'pending' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200', 'icon' => 'bi-hourglass-split'],
        'in progress' => ['class' => 'bg-blue-50 text-blue-700 border-blue-200', 'icon' => 'bi-gear-wide-connected'],
        'completed' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'icon' => 'bi-check-circle'],
        'approved' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'icon' => 'bi-check-circle'],
        'rejected' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200', 'icon' => 'bi-x-circle'],
    ];

    $priorityStyles = [
        'high' => ['class' => 'bg-rose-50 text-rose-700 border-rose-200'],
        'medium' => ['class' => 'bg-amber-50 text-amber-700 border-amber-200'],
        'low' => ['class' => 'bg-slate-50 text-slate-600 border-slate-200'],
    ];

    $statusStyle = $statusStyles[$statusValue] ?? ['class' => 'bg-slate-50 text-slate-600 border-slate-200', 'icon' => 'bi-info-circle'];
    $priorityStyle = $priorityStyles[$priorityValue] ?? ['class' => 'bg-slate-50 text-slate-600 border-slate-200'];
    $isInProgress = in_array($statusValue, ['in progress', 'completed', 'approved'], true);
    $progressDate = $requestItem->processed_at ?? ($isInProgress ? $requestItem->updated_at : null);
    $isCompleted = in_array($statusValue, ['completed', 'approved'], true);
    $completedDate = $requestItem->completed_at ?? ($isCompleted ? $requestItem->updated_at : null);
@endphp

@section('content')
<div class="min-h-full bg-slate-50">
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <div class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div class="space-y-3">
                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">
                    Ticket #{{ str_pad($requestItem->id, 4, '0', STR_PAD_LEFT) }}
                </span>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl capitalize">{{ $requestItem->type }}</h1>
                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                        Submitted {{ $requestItem->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            <a href="{{ route('resident.requests.index') }}" class="inline-flex items-center gap-2 self-start rounded-2xl border border-slate-200 bg-white px-5 py-3 text-xs font-black uppercase tracking-[0.16em] text-slate-600 shadow-sm transition-colors hover:border-slate-300 hover:text-slate-900">
                <i class="bi bi-arrow-left"></i>
                Back to list
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-8">
                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                            <i class="bi bi-text-left text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black tracking-tight text-slate-900">Request Description</h2>
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Problem details and context</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm leading-relaxed text-slate-700">
                        {{ $requestItem->description }}
                    </div>

                    @if($requestItem->photo)
                        <div class="mt-8">
                            <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Attached evidence</p>
                            <button type="button" class="group block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" onclick="window.open('{{ asset('storage/' . $requestItem->photo) }}', '_blank')">
                                <img src="{{ asset('storage/' . $requestItem->photo) }}" alt="Request attachment" class="max-h-96 w-full object-cover transition-transform duration-300 group-hover:scale-[1.01]">
                            </button>
                        </div>
                    @endif
                </section>

                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                            <i class="bi bi-clock-history text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black tracking-tight text-slate-900">Timeline</h2>
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Request journey</p>
                        </div>
                    </div>

                    <div class="relative space-y-8 pl-6">
                        <div class="absolute left-[11px] top-2 bottom-2 w-px bg-slate-200"></div>

                        <div class="relative">
                            <div class="absolute -left-[22px] top-1 h-4 w-4 rounded-full border-4 border-white bg-slate-900 shadow-sm"></div>
                            <p class="text-sm font-black tracking-tight text-slate-900">Submitted</p>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">
                                {{ $requestItem->created_at->format('M d, Y • g:i A') }}
                            </p>
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">Request received and added to the queue.</p>
                        </div>

                        <div class="relative {{ $isInProgress ? '' : 'opacity-40' }}">
                            <div class="absolute -left-[22px] top-1 h-4 w-4 rounded-full border-4 border-white {{ $isInProgress ? 'bg-blue-500' : 'bg-slate-200' }} shadow-sm"></div>
                            <p class="text-sm font-black tracking-tight text-slate-900">Processing</p>
                            @if($progressDate)
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.16em] text-blue-600">
                                    {{ $progressDate->format('M d, Y • g:i A') }}
                                </p>
                            @endif
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">Maintenance team is working on this.</p>
                        </div>

                        <div class="relative {{ $isCompleted ? '' : 'opacity-40' }}">
                            <div class="absolute -left-[22px] top-1 h-4 w-4 rounded-full border-4 border-white {{ $isCompleted ? 'bg-emerald-500' : 'bg-slate-200' }} shadow-sm"></div>
                            <p class="text-sm font-black tracking-tight text-slate-900">Resolution</p>
                            @if($completedDate)
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.16em] text-emerald-600">
                                    {{ $completedDate->format('M d, Y • g:i A') }}
                                </p>
                            @endif
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">The request has been resolved.</p>
                        </div>
                    </div>
                </section>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-lg font-black tracking-tight text-slate-900">Quick Overview</h2>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Request snapshot</p>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Current status</span>
                            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-[0.16em] {{ $statusStyle['class'] }}">
                                <i class="bi {{ $statusStyle['icon'] }}"></i>
                                {{ $requestItem->status }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Priority</span>
                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-[0.16em] {{ $priorityStyle['class'] }}">
                                {{ $requestItem->priority }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Category</span>
                            <span class="text-sm font-bold capitalize text-slate-800">{{ $requestItem->type }}</span>
                        </div>
                    </div>
                </section>

                @if($requestItem->status == 'pending')
                    <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                                <i class="bi bi-pencil-square text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-black tracking-tight text-slate-900">Need to update?</h2>
                                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Allowed while pending</p>
                            </div>
                        </div>

                        <p class="mt-4 text-sm leading-relaxed text-slate-600">
                            You can still edit this request while it is pending.
                        </p>

                        <a href="{{ route('resident.requests.edit', $requestItem->id) }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-[0.16em] text-white transition-colors hover:bg-slate-800">
                            Edit request details
                        </a>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
