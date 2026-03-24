@extends('resident.layouts.app')
@section('title', 'Edit Request')
@section('page-title', 'Edit Request')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-4xl mx-auto px-6 py-8 flex flex-col gap-8 pb-24 animate-fade-in">

        {{-- ========================= --}}
        {{-- HEADER SECTION --}}
        {{-- ========================= --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 mb-3">
                    <i class="bi bi-pencil-square text-emerald-500 text-xs"></i>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Update Ticket #{{ str_pad($requestItem->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none">Edit Request</h1>
                <p class="text-sm font-black text-gray-400 mt-3 uppercase tracking-widest">Modify your existing service request details</p>
            </div>
            
            <a href="{{ route('resident.requests.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-black text-gray-400 hover:text-gray-900 hover:border-gray-900 transition-all duration-300 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>BACK TO LIST</span>
            </a>
        </div>

        {{-- ========================= --}}
        {{-- FORM SECTION --}}
        {{-- ========================= --}}
        <form action="{{ route('resident.requests.update', $requestItem->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PATCH')

            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-1000"></div>
                
                <div class="relative z-10 grid grid-cols-1 gap-10">
                    
                    {{-- Request Type --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Type of Request</label>
                        <div class="relative">
                            <i class="bi bi-tag-fill absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="type" id="type" value="{{ old('type', $requestItem->type) }}" required
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Detailed Description</label>
                        <div class="relative">
                            <i class="bi bi-chat-left-text-fill absolute left-6 top-6 text-gray-400"></i>
                            <textarea name="description" rows="5" required placeholder="Please describe your request in detail..."
                                      class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-6 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none resize-none">{{ old('description', $requestItem->description) }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Priority --}}
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Priority Level</label>
                            <div class="relative">
                                <i class="bi bi-flag-fill absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <select name="priority"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none appearance-none cursor-pointer">
                                    <option value="Low" {{ old('priority', $requestItem->priority) == 'Low' ? 'selected' : '' }}>LOW - NOT URGENT</option>
                                    <option value="Medium" {{ old('priority', $requestItem->priority) == 'Medium' ? 'selected' : '' }}>MEDIUM - NORMAL</option>
                                    <option value="High" {{ old('priority', $requestItem->priority) == 'High' ? 'selected' : '' }}>HIGH - URGENT</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>
                        </div>

                        {{-- Photo Upload --}}
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Update Photo (Optional)</label>
                            <input type="file" name="photo" id="photo" accept="image/*" onchange="previewImage(event)" class="hidden">
                            <label for="photo"
                                   class="flex items-center justify-center gap-3 w-full px-6 py-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-emerald-500 hover:bg-emerald-500/5 hover:text-emerald-600 transition-all cursor-pointer">
                                <i class="bi bi-camera-fill text-lg"></i>
                                <span id="photoLabel">{{ $requestItem->photo ? 'Change Photo' : 'Upload Photo' }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Image Preview --}}
                    <div id="imagePreviewContainer" class="{{ $requestItem->photo ? '' : 'hidden' }} pt-8 border-t border-gray-50 text-center animate-fade-in">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">PHOTO PREVIEW</p>
                        <div class="relative inline-block group/preview">
                            <div class="absolute inset-0 bg-emerald-500/20 rounded-[32px] blur-xl"></div>
                            <img id="imagePreview" src="{{ $requestItem->photo ? asset('storage/' . $requestItem->photo) : '#' }}" 
                                 class="max-h-64 rounded-[32px] border-4 border-white shadow-2xl relative z-10">
                            <button type="button" onclick="clearImage()"
                                    class="absolute -top-3 -right-3 w-10 h-10 bg-red-500 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:bg-red-600 hover:scale-110 transition-all z-20 border-4 border-white">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT ACTIONS --}}
            <div class="bg-[#081412] rounded-[40px] p-10 shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-left">
                        <h4 class="text-white font-black text-lg tracking-tight mb-1">Save your changes?</h4>
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-widest">Update the details of your service ticket.</p>
                    </div>
                    
                    <button type="submit"
                            class="group relative px-12 py-5 bg-emerald-500 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-emerald-400 hover:shadow-[0_20px_40px_rgba(16,185,129,0.3)] transition-all duration-300 transform active:scale-95 flex items-center gap-3 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <i class="bi bi-save-fill relative z-10"></i>
                        <span class="relative z-10">Update Request</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>

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

function clearImage(){
    const input = document.getElementById('photo');
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const label = document.getElementById('photoLabel');

    input.value = '';
    preview.src = '{{ $requestItem->photo ? asset("storage/" . $requestItem->photo) : "#" }}';
    if (preview.src.endsWith('#')) {
        container.classList.add('hidden');
        label.textContent = 'Upload Photo';
    } else {
        label.textContent = 'Change Photo';
    }
}
</script>
@endsection
