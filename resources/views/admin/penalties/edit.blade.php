@extends('layouts.admin')

@section('title', 'Edit Penalty')
@section('page-title', 'Edit Penalty')

@section('content')
<div class="admin-form-card">

<div class="mb-6 border-b pb-4 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-semibold text-gray-900">Edit Penalty</h2>
        <p class="text-gray-500 text-sm">Update penalty details and status.</p>
    </div>
    <a href="{{ route('admin.penalties.index') }}"
       class="admin-btn-secondary text-sm font-semibold px-4 py-2">
       ← Back to List
    </a>
</div>

<form action="{{ route('admin.penalties.update', $penalty->id) }}" method="POST" class="space-y-5">
    @csrf
    @method('PUT')

    <div>
        <label class="admin-form-label">Resident</label>
        <select name="resident_id"
            class="admin-form-select"
            required>
            @foreach($residents as $resident)
                <option value="{{ $resident->id }}" {{ $penalty->resident_id == $resident->id ? 'selected' : '' }}>
                    {{ $resident->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="admin-form-label">Type</label>
        <select name="type"
            class="admin-form-select">
            <option value="general" {{ $penalty->type == 'general' ? 'selected' : '' }}>General</option>
            <option value="late_payment" {{ $penalty->type == 'late_payment' ? 'selected' : '' }}>Late Payment</option>
            <option value="overdue" {{ $penalty->type == 'overdue' ? 'selected' : '' }}>Overdue</option>
            <option value="violation" {{ $penalty->type == 'violation' ? 'selected' : '' }}>Violation</option>
            <option value="damage" {{ $penalty->type == 'damage' ? 'selected' : '' }}>Damage</option>
        </select>
    </div>

    <div>
        <label class="admin-form-label">Reason</label>
        <input type="text" name="reason" value="{{ $penalty->reason }}"
            class="admin-form-input"
            placeholder="e.g. Late payment for monthly dues" required>
    </div>

    <div>
        <label class="admin-form-label">Amount (₱)</label>
        <input type="number" step="0.01" name="amount" value="{{ $penalty->amount }}"
            class="admin-form-input" required>
    </div>

    <div>
        <label class="admin-form-label">Date Issued</label>
        <input type="date" name="date_issued" value="{{ $penalty->date_issued }}"
            class="admin-form-input">
    </div>

    <div>
        <label class="admin-form-label">Status</label>
        <select name="status"
            class="admin-form-select" required>
            <option value="unpaid" {{ $penalty->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="paid" {{ $penalty->status == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t">
        <a href="{{ route('admin.penalties.index') }}"
           class="admin-btn-secondary">
            Cancel
        </a>
        <button type="submit"
                class="admin-btn-primary">
            Update Penalty
        </button>
    </div>
</form>
</div>
@endsection
