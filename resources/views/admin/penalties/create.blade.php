@extends('layouts.admin')

@section('title', 'Add Penalty')
@section('page-title', 'Add New Penalty')

@section('content')
<div class="admin-form-card">

    <a href="{{ route('admin.penalties.index') }}"
       class="text-sm text-gray-500 hover:underline mb-4 inline-block">
        &larr; Back to Penalties
    </a>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.penalties.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="resident_id" class="admin-form-label">Resident</label>
            <select name="resident_id" id="resident_id" class="admin-form-select text-sm">
                <option value="">Select resident</option>
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}" {{ old('resident_id') == $resident->id ? 'selected' : '' }}>
                        {{ $resident->first_name }} {{ $resident->last_name }} (B{{ $resident->block }} L{{ $resident->lot }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="type" class="admin-form-label">Type</label>
            <select name="type" id="type" class="admin-form-select text-sm">
                <option value="general" {{ old('type')=='general'?'selected':'' }}>General</option>
                <option value="late_payment" {{ old('type')=='late_payment'?'selected':'' }}>Late Payment</option>
                <option value="overdue" {{ old('type')=='overdue'?'selected':'' }}>Overdue</option>
                <option value="violation" {{ old('type')=='violation'?'selected':'' }}>Violation</option>
                <option value="damage" {{ old('type')=='damage'?'selected':'' }}>Damage</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="reason" class="admin-form-label">Reason</label>
            <input type="text" name="reason" id="reason" value="{{ old('reason') }}"
                   class="admin-form-input text-sm" placeholder="Enter reason for penalty">
        </div>

        <div class="mb-4">
            <label for="amount" class="admin-form-label">Amount (₱)</label>
            <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                   class="admin-form-input text-sm" step="0.01" placeholder="0.00">
        </div>

        <div class="mb-4">
            <label for="date_issued" class="admin-form-label">Date Issued</label>
            <input type="date" name="date_issued" id="date_issued" value="{{ old('date_issued') }}"
                   class="admin-form-input text-sm">
        </div>

        <div class="mb-4">
            <label for="status" class="admin-form-label">Status</label>
            <select name="status" id="status" class="admin-form-select text-sm">
                <option value="unpaid" {{ old('status')=='unpaid'?'selected':'' }}>Unpaid</option>
                <option value="paid" {{ old('status')=='paid'?'selected':'' }}>Paid</option>
            </select>
        </div>

        <button type="submit" class="admin-btn-primary mt-2">
            Add Penalty
        </button>

    </form>
</div>
@endsection
