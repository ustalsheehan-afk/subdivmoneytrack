@extends('layouts.admin')

@section('title', 'Edit Billing Statement')
@section('page-title', 'Edit Billing Statement')

@section('content')
<div class="admin-form-card">

    <div class="flex justify-between items-center mb-6 border-b pb-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Edit Billing Statement</h2>
            <p class="text-sm text-gray-500 mt-1">Update the details of this billing batch below.</p>
        </div>
        <a href="{{ route('admin.dues.index') }}" 
           class="admin-btn-secondary text-sm font-medium px-4 py-2">
            ← Back to List
        </a>
    </div>

    <form action="{{ route('admin.dues.update', $batch->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="admin-form-label">Billing Title</label>
            <input type="text" name="title" 
                   value="{{ old('title', $batch->title) }}"
                   class="admin-form-input"
                   placeholder="e.g. Monthly HOA Fee" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="admin-form-label">Type (Read Only)</label>
                <input type="text" class="admin-form-input bg-gray-50" value="{{ str_replace('_', ' ', $batch->type) }}" disabled>
            </div>

            <div>
                <label class="admin-form-label">Due Date</label>
                <input type="date" name="due_date"
                       value="{{ old('due_date', $batch->due_date ? $batch->due_date->format('Y-m-d') : '') }}"
                       class="admin-form-input" required>
                @error('due_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mt-6">
            <div class="flex gap-3">
                <i class="bi bi-info-circle text-blue-600"></i>
                <div class="text-xs text-blue-700 leading-relaxed">
                    <strong>Note:</strong> Editing the title and due date here will update the main batch record. Individual resident dues within this batch will remain linked to this statement.
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t mt-8">
            <a href="{{ route('admin.dues.index') }}"
               class="admin-btn-secondary">
                Cancel
            </a>
            <button type="submit"
                    class="admin-btn-primary">
                Update Statement
            </button>
        </div>
    </form>

</div>
@endsection
