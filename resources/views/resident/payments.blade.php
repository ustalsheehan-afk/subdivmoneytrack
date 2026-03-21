@extends('layouts.resident')

@section('title', 'My Payments')
@section('page-title', 'My Payment Records')

@section('content')
<div class="bg-white shadow-sm rounded-3 p-4 border">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold text-dark mb-0">
            <i class="bi bi-wallet2 me-2"></i> My Payment Records
        </h4>
        <a href="{{ route('resident.payments.create') }}" class="btn btn-dark px-4 py-2 shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Payment
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 border">
            <thead class="bg-dark text-white text-uppercase small">
                <tr>
                    <th scope="col" class="ps-3">#</th>
                    <th scope="col">Due Description</th>
                    <th scope="col" class="text-end">Amount (₱)</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-end pe-3">Date Paid</th>
                </tr>
            </thead>
            <tbody>
                @if($payments->count() > 0)
                    @foreach($payments as $payment)
                    <tr>
                        <td class="ps-3 text-dark">{{ $loop->iteration }}</td>
                        <td class="text-dark fw-medium">
                            {{ $payment->dues->description ?? 'N/A' }}
                        </td>
                        <td class="text-end text-dark fw-semibold">
                            ₱{{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2 
                                @if($payment->status === 'approved' || $payment->status === 'Paid') bg-success-subtle text-success
                                @elseif($payment->status === 'pending') bg-warning-subtle text-warning
                                @else bg-danger-subtle text-danger
                                @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-3 text-secondary">
                            {{ $payment->date_paid 
                                ? \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') 
                                : ($payment->created_at ? $payment->created_at->format('M d, Y') : '—') }}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-receipt text-muted fs-4 d-block mb-2"></i>
                            No payment records found.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $payments->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
