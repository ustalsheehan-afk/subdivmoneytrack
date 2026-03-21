@extends('resident.layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit Profile</h2>
                <p class="text-sm text-gray-500 mt-1">Update your personal information</p>
            </div>
            <a href="{{ route('resident.profile.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                Cancel
            </a>
        </div>

        {{-- Form --}}
        <form action="{{ route('resident.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Profile Photo --}}
            <div class="flex items-center gap-6 mb-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
                <div class="relative shrink-0">
                    <img id="photoPreview"
                         src="{{ ($resident && $resident->photo) ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}"
                         onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                         alt="Profile Photo"
                         class="w-24 h-24 rounded-full object-cover shadow-sm ring-4 ring-white">
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">Profile Photo</h3>
                    <p class="text-xs text-gray-500 mb-3">JPG, GIF or PNG. Max size of 2MB</p>
                    <div class="flex gap-3">
                        <button type="button"
                                onclick="document.getElementById('photoInput').click()"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                            Change Photo
                        </button>
                        <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                    </div>
                    @error('photo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- First Name --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $resident->first_name ?? '') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                           required>
                    @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Last Name --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $resident->last_name ?? '') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                           required>
                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Contact Number --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $resident->contact_number ?? '') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                           required>
                    @error('contact_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $resident->email ?? '') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                           required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Block --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Block</label>
                    <input type="number" name="block" value="{{ old('block', $resident->block ?? '') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                           min="1" required>
                    @error('block') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Lot --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lot</label>
                    <input type="number" name="lot" value="{{ old('lot', $resident->lot ?? '') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                           min="1" required>
                    @error('lot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Move-in Date --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Move-in Date</label>
                    <input type="date" name="move_in_date" value="{{ old('move_in_date', $resident->move_in_date) }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                           required>
                    @error('move_in_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <div class="relative">
                        <select name="status"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none appearance-none cursor-pointer"
                                required>
                            <option value="active" {{ old('status', $resident->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $resident->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Membership Type --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Membership Type</label>
                    <input type="text" name="membership_type" value="{{ old('membership_type', $resident->membership_type) }}"
                           placeholder="e.g., Regular Member"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    @error('membership_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Property Type --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Property Type</label>
                    <input type="text" name="property_type" value="{{ old('property_type', $resident->property_type) }}"
                           placeholder="e.g., Residential House & Lot"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    @error('property_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Lot Area --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lot Area (sq.m)</label>
                    <input type="number" name="lot_area" value="{{ old('lot_area', $resident->lot_area) }}"
                           placeholder="150" step="0.01" min="0"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    @error('lot_area') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Floor Area --}}
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Floor Area (sq.m)</label>
                    <input type="number" name="floor_area" value="{{ old('floor_area', $resident->floor_area) }}"
                           placeholder="120" step="0.01" min="0"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    @error('floor_area') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Password 
                        <span class="font-normal text-gray-400 ml-1">(Leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                <a href="{{ route('resident.profile.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-2.5 rounded-xl transition shadow-md hover:shadow-lg">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
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
