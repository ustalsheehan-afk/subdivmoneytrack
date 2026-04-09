@extends('layouts.admin')

@section('title', 'SMS Templates')
@section('page-title', 'SMS Templates')

@section('content')
<div class="space-y-6">
    <div class="glass-card p-6">
        <h2 class="text-2xl font-black text-gray-900">SMS Template Management</h2>
        <p class="mt-2 text-sm text-gray-600">
            Edit the default SMS content used for Dues reminders and Penalty notices.
        </p>
    </div>

    <div class="glass-card p-6">
        <form method="POST" action="{{ route('admin.smsTemplates.update') }}" class="space-y-6">
            @csrf

            <div>
                <label for="dues_reminder" class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Dues Reminder Template</label>
                <textarea id="dues_reminder" name="dues_reminder" rows="6" class="w-full rounded-xl border border-gray-200 p-3 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" required>{{ old('dues_reminder', $templates['dues_reminder'] ?? '') }}</textarea>
                <p class="mt-2 text-xs text-gray-500">Placeholders: {resident_name}, {due_title}, {amount}, {due_date}, {payment_link}</p>
            </div>

            <div>
                <label for="penalty_notice" class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Penalty Notice Template</label>
                <textarea id="penalty_notice" name="penalty_notice" rows="6" class="w-full rounded-xl border border-gray-200 p-3 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" required>{{ old('penalty_notice', $templates['penalty_notice'] ?? '') }}</textarea>
                <p class="mt-2 text-xs text-gray-500">Placeholders: {resident_name}, {amount}, {penalty_reason}, {payment_link}</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-premium">
                    <i class="bi bi-save2"></i>
                    Save Templates
                </button>
                <a href="{{ route('admin.dues.index') }}" class="btn-secondary">Back to Dues</a>
                <a href="{{ route('admin.penalties.index') }}" class="btn-secondary">Back to Penalties</a>
            </div>
        </form>
    </div>
</div>
@endsection
