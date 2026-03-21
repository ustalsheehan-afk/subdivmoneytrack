@extends('layouts.admin')

@section('title', 'Add Board Member')
@section('page-title', 'Add Board Member')

@section('content')
<div class="p-6 lg:p-8 space-y-8 bg-gray-50/50 min-h-screen">
    {{-- Header Section --}}
    <div class="bg-white border border-gray-200 rounded-3xl p-8 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full -mr-32 -mt-32 blur-3xl opacity-50"></div>
        
        <div class="relative z-10 text-center md:text-left">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Add Member</h1>
            <p class="text-sm text-gray-500 font-medium">Add a new board member to the subdivision board.</p>
        </div>

        <a href="{{ route('admin.board.index') }}" 
           class="relative z-10 px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-2xl font-bold text-sm hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm active:scale-95">
            <i class="bi bi-arrow-left"></i>
            Back to List
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.board.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div class="space-y-2">
                            <label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none" 
                                   placeholder="e.g. Juan Dela Cruz" required>
                        </div>

                        {{-- Position --}}
                        <div class="space-y-2">
                            <label for="position" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Board Position</label>
                            <select name="position" id="position" 
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none" required>
                                <option value="" disabled selected>Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position }}" {{ old('position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Email --}}
                        <div class="space-y-2">
                            <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none" 
                                   placeholder="e.g. juan@example.com">
                        </div>

                        {{-- Phone --}}
                        <div class="space-y-2">
                            <label for="phone" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none" 
                                   placeholder="e.g. 09123456789">
                        </div>

                        {{-- Facebook --}}
                        <div class="space-y-2">
                            <label for="facebook" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Facebook Link</label>
                            <input type="url" name="facebook" id="facebook" value="{{ old('facebook') }}" 
                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none" 
                                   placeholder="https://facebook.com/profile">
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div class="space-y-2">
                        <label for="bio" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Bio / Quote (Optional)</label>
                        <textarea name="bio" id="bio" rows="4" 
                                  class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none" 
                                  placeholder="Brief description or quote...">{{ old('bio') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Photo --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Profile Photo (Optional)</label>
                            <div class="relative">
                                <input type="file" name="photo" id="photo" accept="image/*" onchange="previewImage(event)" class="hidden">
                                <label for="photo" class="flex items-center justify-center gap-3 w-full px-5 py-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-sm font-bold text-gray-500 hover:border-blue-400 hover:bg-blue-50 transition-all cursor-pointer">
                                    <i class="bi bi-camera text-lg"></i>
                                    <span id="photoLabel">Upload Photo</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Image Preview --}}
                    <div id="imagePreviewContainer" class="hidden pt-4 border-t border-gray-50 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Photo Preview</p>
                        <div class="relative inline-block group">
                            <img id="imagePreview" src="#" alt="Preview" class="max-h-64 rounded-2xl shadow-xl border border-gray-100">
                            <button type="button" onclick="clearImage()" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-all opacity-0 group-hover:opacity-100">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-gray-50 border-t border-gray-100">
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="bi bi-save-fill"></i>
                        Add Member
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
    preview.src = '#';
    container.classList.add('hidden');
    label.textContent = 'Upload Photo';
}
</script>
@endsection
