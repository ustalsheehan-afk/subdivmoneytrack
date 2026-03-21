@extends('layouts.admin')

@section('title', isset($payment) ? 'Edit Payment' : 'Add Payment')
@section('page-title', isset($payment) ? 'Edit Payment' : 'Add Payment')

@section('content')
<div class="admin-form-card">

    <h2 class="text-xl font-semibold mb-6 text-gray-900">{{ isset($payment) ? 'Edit Payment' : 'Add New Payment' }}</h2>

    <form action="{{ isset($payment) ? route('admin.payments.update', $payment->id) : route('admin.payments.review') }}" method="{{ isset($payment) ? 'POST' : 'POST' }}" enctype="multipart/form-data">
        @csrf
        @if(isset($payment))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="admin-form-label">Resident</label>
            <select name="resident_id" id="resident_id" class="admin-form-select" required>
                <option value="">Select Resident</option>
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}" {{ (old('resident_id', $payment->resident_id ?? '') == $resident->id) ? 'selected' : '' }}>
                        {{ $resident->full_name }} - Block {{ $resident->block }}, Lot {{ $resident->lot }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Due</label>
            <select name="due_id" id="due_id" class="admin-form-select" required>
                <option value="">Select Due</option>
                @foreach($dues as $due)
                    <option value="{{ $due->id }}" data-amount="{{ $due->amount }}"
                        {{ (old('due_id', $payment->due_id ?? '') == $due->id) ? 'selected' : '' }}>
                        {{ $due->title }} - ₱{{ number_format($due->amount,2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Amount Paid</label>
            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $payment->amount ?? '') }}" class="admin-form-input" required>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Date & Time Paid</label>
            <input type="datetime-local" name="date_paid" value="{{ old('date_paid', isset($payment->date_paid) ? $payment->date_paid->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" class="admin-form-input" required>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Payment Method</label>
            <input type="text" name="payment_method" value="{{ old('payment_method', $payment->payment_method ?? '') }}" class="admin-form-input" required>
        </div>

        @if(isset($payment))
        <div class="mb-4">
            <label class="admin-form-label">Status</label>
            <select name="status" class="admin-form-select">
                <option value="pending" {{ $payment->status=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ $payment->status=='approved'?'selected':'' }}>Approved</option>
                <option value="rejected" {{ $payment->status=='rejected'?'selected':'' }}>Rejected</option>
            </select>
        </div>
        @endif

        <div class="mb-4">
            <label class="admin-form-label">Proof (Optional)</label>
            @if(isset($payment) && $payment->proof)
                <div class="mb-2">
                    <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank" class="text-blue-600 underline">View Current Proof</a>
                </div>
            @endif
            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <button type="submit" class="admin-btn-primary mt-2">
            {{ isset($payment) ? 'Update Payment' : 'Add Payment' }}
        </button>
    </form>
</div>

<script>
document.getElementById('resident_id').addEventListener('change', function() {
    const residentId = this.value;
    const dueSelect = document.getElementById('due_id');
    const amountInput = document.getElementById('amount');

    dueSelect.innerHTML = '<option value="">Select Due</option>';
    amountInput.value = '';

    if (!residentId) return;

    fetch(`/admin/residents/${residentId}/dues`)
        .then(res => res.json())
        .then(data => {
            data.forEach(due => {
                const option = document.createElement('option');
                option.value = due.id;
                option.textContent = `${due.title} - ₱${new Intl.NumberFormat().format(due.amount)}`;
                option.dataset.amount = due.amount;
                dueSelect.appendChild(option);
            });
        });
});

document.getElementById('due_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('amount').value = selected?.dataset.amount ?? '';
});
</script>
@endsection
