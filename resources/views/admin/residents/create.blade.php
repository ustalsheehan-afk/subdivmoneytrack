@extends('layouts.admin')

@section('title', 'Create Resident')
@section('page-title', 'Add New Resident')

@section('content')
<div class="admin-form-card">

    {{-- Success message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-green-600"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Error message --}}
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Create Resident</h2>
        <a href="{{ route('admin.residents.index') }}"
           class="admin-btn-secondary">
           ← Back
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.residents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="flex flex-col items-center mb-5">
            <label class="admin-form-label">Profile Photo</label>
            <div class="relative">
                <img id="photoPreview"
                     src="{{ asset('CDlogo.jpg') }}"
                     alt="Profile Photo"
                     class="w-32 h-32 rounded-full object-cover shadow mb-2">
                <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                <button type="button"
                        onclick="document.getElementById('photoInput').click()"
                        class="admin-btn-secondary">
                    Upload Photo
                </button>
            </div>
            @error('photo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">First Name *</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}"
                   class="admin-form-input"
                   required>
            @error('first_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">Last Name *</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}"
                   class="admin-form-input"
                   required>
            @error('last_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">Contact Number *</label>
            <input type="text" name="contact" value="{{ old('contact') }}"
                   class="admin-form-input"
                   required>
            @error('contact') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">Block *</label>
            <input type="number" name="block" value="{{ old('block') }}"
                   class="admin-form-input"
                   min="1" required>
            @error('block') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">Lot *</label>
            <input type="number" name="lot" value="{{ old('lot') }}"
                   class="admin-form-input"
                   min="1" required>
            @error('lot') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">Email Address *</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="admin-form-input"
                   required>
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">Move-in Date *</label>
            <input type="date" name="move_in_date"
                   value="{{ old('move_in_date') }}"
                   class="admin-form-input"
                   required>
            @error('move_in_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-form-label">Status *</label>
            <select name="status"
                    class="admin-form-select"
                    required>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.residents.index') }}"
               class="admin-btn-secondary">
               Cancel
            </a>
            <button type="submit"
                    class="admin-btn-primary">
                Create Resident
            </button>
        </div>
    </form>
</div>

{{-- Live Photo Preview --}}
<script>
document.getElementById('photoInput').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('photoPreview').src = URL.createObjectURL(file);
    }
});
</script>
@endsection
