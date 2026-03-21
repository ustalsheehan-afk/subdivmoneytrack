@extends('layouts.admin')

@section('title', 'Review Payment')
@section('page-title', 'Review Payment Details')

@section('content')
<div class="max-w-3xl mx-auto px-4">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h2 class="text-lg font-semibold text-slate-900">
                Review Payment Details
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Please confirm the payment information before processing.
            </p>
        </div>

        <div class="p-6 space-y-6">

            {{-- Top Section --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Resident Info --}}
                <div class="md:col-span-2 border border-slate-200 rounded-xl p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-3">
                        Resident Information
                    </p>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr($resident->first_name, 0, 1) . substr($resident->last_name, 0, 1)) }}
                        </div>

                        <div>
                            <p class="font-semibold text-slate-900 text-sm">
                                {{ $resident->full_name }}
                            </p>
                            <p class="text-xs text-slate-500">
                                Block {{ $resident->block }}, Lot {{ $resident->lot }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Payment Summary --}}
                <div class="border border-emerald-200 rounded-xl p-4 bg-emerald-50">
                    <p class="text-[10px] uppercase tracking-wider text-emerald-700 mb-1">
                        Payment Summary
                    </p>

                    <p class="text-xs text-emerald-700">Total Amount</p>

                    <p class="text-xl font-bold text-emerald-800">
                        ₱{{ number_format($data['amount'], 2) }}
                    </p>
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <p class="text-xs font-semibold text-slate-700">
                        Payment Information
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-5 py-4 text-sm">
                    <div>
                        <p class="text-[10px] uppercase text-slate-400 mb-1">Due Type</p>
                        <p class="font-semibold text-slate-900">{{ $due->title }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase text-slate-400 mb-1">Date & Time Paid</p>
                        <p class="font-semibold text-slate-900">
                            {{ \Carbon\Carbon::parse($data['date_paid'])->format('M d, Y • g:i A') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase text-slate-400 mb-1">Payment Method</p>
                        <span class="inline-flex bg-slate-100 px-2 py-1 rounded-md text-xs font-medium text-slate-700 uppercase">
                            {{ $data['payment_method'] }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase text-slate-400 mb-1">Status</p>
                        <span class="inline-flex bg-amber-100 text-amber-700 px-2 py-1 rounded-md text-xs font-medium">
                            Pending Confirmation
                        </span>
                    </div>
                </div>
            </div>

            {{-- Proof Preview --}}
            @if($proofPath)
                <div class="border border-slate-200 rounded-xl p-4 bg-white">
                    <div class="mb-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Proof of Payment
                        </p>
                        <p class="mt-1 text-xs text-slate-500">
                            Uploaded proof for verification.
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 inline-block">
                        <img
                            src="{{ asset('storage/' . $proofPath) }}"
                            alt="Proof of Payment"
                            class="max-h-64 rounded-lg object-contain"
                        >
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                <button
                    type="button"
                    onclick="window.history.back()"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition"
                >
                    <i class="bi bi-arrow-left"></i>
                    Go Back & Edit
                </button>

                <form action="{{ route('admin.payments.confirm') }}" method="POST">
                    @csrf
                    <input type="hidden" name="resident_id" value="{{ $data['resident_id'] }}">
                    <input type="hidden" name="due_id" value="{{ $data['due_id'] }}">
                    <input type="hidden" name="amount" value="{{ $data['amount'] }}">
                    <input type="hidden" name="date_paid" value="{{ $data['date_paid'] }}">
                    <input type="hidden" name="payment_method" value="{{ $data['payment_method'] }}">
                    @if($proofPath)
                        <input type="hidden" name="temp_proof" value="{{ $proofPath }}">
                    @endif

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2 text-xs font-semibold bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition"
                    >
                        Confirm & Process Payment
                        <i class="bi bi-check2-circle text-sm"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection