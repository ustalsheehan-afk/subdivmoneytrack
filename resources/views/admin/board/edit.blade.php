@extends('layouts.admin')

@section('title', 'Edit Board Member')
@section('page-title', 'Edit Board Member')

@section('content')
<div class="space-y-8 animate-fade-in">
    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Edit Member
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Update board member details for <span class="text-emerald-600 font-black">{{ $board->name }}</span>.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.board.index') }}" class="btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.board.update', $board->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="glass-card overflow-hidden">
                <div class="p-8 space-y-6">
                    {{-- Form Sections --}}
                    <div class="space-y-8">
                        {{-- Basic Info Section --}}
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">1</div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Basic Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Name --}}
                                <div class="space-y-2">
                                    <label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $board->name) }}" 
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none" 
                                           placeholder="e.g. Juan Dela Cruz" required>
                                </div>

                                {{-- Position --}}
                                <div class="space-y-2">
                                    <label for="position" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Board Position</label>
                                    <div class="relative group">
                                        <select name="position" id="position" 
                                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none appearance-none cursor-pointer" required>
                                            @foreach($positions as $position)
                                                <option value="{{ $position }}" {{ old('position', $board->position) == $position ? 'selected' : '' }}>{{ $position }}</option>
                                            @endforeach
                                        </select>
                                        <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:text-emerald-500 transition-colors"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Section --}}
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">2</div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Contact & Social</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $board->email) }}" 
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none" 
                                           placeholder="e.g. juan@example.com">
                                </div>

                                {{-- Phone --}}
                                <div class="space-y-2">
                                    <label for="phone" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $board->phone) }}" 
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none" 
                                           placeholder="e.g. 09123456789">
                                </div>

                                {{-- Facebook --}}
                                <div class="space-y-2">
                                    <label for="facebook" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Facebook Link</label>
                                    <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $board->facebook) }}" 
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none" 
                                           placeholder="https://facebook.com/profile">
                                </div>
                            </div>
                        </div>

                        {{-- Bio & Profile Section --}}
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">3</div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Bio & Profile</h3>
                            </div>
                            
                            <div class="space-y-6">
                                {{-- Bio --}}
                                <div class="space-y-2">
                                    <label for="bio" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Bio / Quote (Optional)</label>
                                    <textarea name="bio" id="bio" rows="4" 
                                              class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none resize-none" 
                                              placeholder="Brief description or quote...">{{ old('bio', $board->bio) }}</textarea>
                                </div>

                                {{-- Photo --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Profile Photo (Optional)</label>
                                        <div class="relative">
                                            <input type="file" name="photo" id="photo" accept="image/*" onchange="previewImage(event)" class="hidden">
                                            <label for="photo" class="flex flex-col items-center justify-center gap-3 w-full p-8 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[32px] text-sm font-bold text-gray-500 hover:border-emerald-400 hover:bg-emerald-50 transition-all cursor-pointer group/upload">
                                                <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center shadow-sm border border-gray-100 group-hover/upload:scale-110 transition-transform duration-500">
                                                    <i class="bi bi-camera text-2xl text-emerald-500"></i>
                                                </div>
                                                <span id="photoLabel">{{ $board->photo ? 'Change Profile Photo' : 'Click to upload photo' }}</span>
                                                <span class="text-[10px] font-medium text-gray-400">JPG, PNG or GIF (Max 2MB)</span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Image Preview --}}
                                    <div id="imagePreviewContainer" class="{{ $board->photo ? '' : 'hidden' }}">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1 mb-2 block">Photo Preview</label>
                                        <div class="relative inline-block group">
                                            <img id="imagePreview" src="{{ $board->photo ? asset('storage/' . $board->photo) . '?v=' . ($board->updated_at?->timestamp ?? time()) : '#' }}" alt="Preview" class="w-48 h-48 rounded-[32px] object-cover shadow-2xl border-4 border-white">
                                            <button type="button" onclick="clearImage()" class="absolute -top-3 -right-3 w-10 h-10 bg-red-500 text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-red-600 transition-all active:scale-95">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-4">
                    <button type="submit" class="btn-premium w-full md:w-auto px-12">
                        <i class="bi bi-check-lg"></i>
                        Update Board Member
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const label = document.getElementById('photoLabel');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
            label.textContent = input.files[0].name;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function clearImage() {
    const input = document.getElementById('photo');
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const label = document.getElementById('photoLabel');

    input.value = '';
    preview.src = '{{ $board->photo ? asset("storage/" . $board->photo) . "?v=" . ($board->updated_at?->timestamp ?? time()) : "#" }}';
    if (preview.src.endsWith('#')) {
        container.classList.add('hidden');
        label.textContent = 'Upload Photo';
    } else {
        label.textContent = 'Change Photo';
    }
}
</script>
@endsection
