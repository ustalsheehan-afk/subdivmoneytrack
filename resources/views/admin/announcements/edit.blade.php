@extends('layouts.admin')

@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Announcement</h1>
        <p class="text-sm text-gray-500 mt-1">Update the details of your announcement.</p>
    </div>

    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" id="announcementForm">
        @csrf
        @method('PUT')
        
        <div class="flex flex-col lg:flex-row gap-8 items-stretch">
            {{-- LEFT PANEL (FORM) --}}
            <div class="lg:w-[55%] flex flex-col gap-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-6 lg:sticky lg:top-6 h-fit transition-all duration-200">
                    
                    {{-- TITLE --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Title</label>
                        <input type="text" name="title" id="input-title"
                               placeholder="Enter announcement title"
                               value="{{ old('title', $announcement->title) }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                               required>
                    </div>

                    {{-- CONTENT --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Content</label>
                        <textarea name="content" id="input-content"
                                  maxlength="1000"
                                  placeholder="Write your announcement details here..."
                                  class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all resize-none"
                                  rows="6"
                                  required>{{ old('content', $announcement->content) }}</textarea>
                    </div>

                    {{-- CATEGORY --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Category</label>
                        <select name="category" id="input-category" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all cursor-pointer" 
                                required>
                            @foreach(['Event','Maintenance','Meeting','Security','Finance','Emergency'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $announcement->category) == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DATE --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Date & Time Posted</label>
                        <input type="datetime-local"
                               name="date_posted"
                               id="input-date"
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                               value="{{ old('date_posted', $announcement->date_posted->format('Y-m-d\TH:i')) }}"
                               required>
                    </div>

                    {{-- FILE UPLOAD --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Attachment (Optional)</label>
                        <label class="group flex items-center justify-between px-4 py-3 border border-gray-200 border-dashed rounded-lg bg-gray-50 hover:bg-white hover:border-blue-500 hover:border-solid cursor-pointer transition-all duration-200">
                            <span id="file-name" class="text-sm text-gray-500 group-hover:text-blue-600 transition-colors">
                                {{ $announcement->image ? 'Change current image...' : 'Choose an image...' }}
                            </span>
                            <div class="flex items-center gap-2 text-gray-400 group-hover:text-blue-500">
                                <i class="bi bi-image text-lg"></i>
                                <i class="bi bi-plus-lg text-xs"></i>
                            </div>
                            <input type="file" name="image" id="input-image" class="hidden" accept="image/*">
                        </label>
                    </div>

                    {{-- PIN SECTION --}}
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </div>
                                <label for="is_pinned" class="text-sm font-semibold text-gray-700 cursor-pointer">Pin Announcement</label>
                            </div>
                            <i class="bi bi-pin-angle-fill text-gray-400 peer-checked:text-blue-500"></i>
                        </div>

                        <div id="pin-duration-container" class="{{ old('is_pinned', $announcement->is_pinned) ? '' : 'opacity-50 pointer-events-none' }} transition-all duration-200">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Pin Duration</label>
                            <select name="pin_duration" id="pin_duration"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="1" {{ old('pin_duration', 7) == 1 ? 'selected' : '' }}>1 Day</option>
                                <option value="3" {{ old('pin_duration', 7) == 3 ? 'selected' : '' }}>3 Days</option>
                                <option value="7" {{ old('pin_duration', 7) == 7 ? 'selected' : '' }}>7 Days</option>
                                <option value="14" {{ old('pin_duration', 7) == 14 ? 'selected' : '' }}>14 Days</option>
                                <option value="30" {{ old('pin_duration', 7) == 30 ? 'selected' : '' }}>30 Days</option>
                            </select>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 hover:shadow-lg active:scale-[0.98] transition-all duration-200 shadow-md">
                            <i class="bi bi-check2-circle text-lg"></i>
                            Update Announcement
                        </button>
                        <a href="{{ route('admin.announcements.index') }}" 
                           class="px-6 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all duration-200">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL (LIVE PREVIEW) --}}
            <div class="lg:w-[45%] flex flex-col gap-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-6 flex flex-col min-h-[500px]">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Live Preview
                        </h3>
                        <div class="flex items-center gap-1.5 px-2 py-1 bg-gray-50 rounded text-[10px] font-bold text-gray-500 uppercase">
                            <i class="bi bi-laptop"></i> Desktop View
                        </div>
                    </div>

                    {{-- PREVIEW CONTENT --}}
                    <div class="space-y-5 flex-1">
                        {{-- Title --}}
                        <h1 id="preview-title" class="text-2xl font-bold text-gray-900 leading-tight tracking-tight break-words">Untitled Announcement</h1>

                        {{-- Meta Row --}}
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span id="preview-category" class="px-3 py-1 rounded-lg font-bold text-[11px] uppercase tracking-wider bg-gray-100 text-gray-600 border border-transparent transition-all duration-300">
                                    General
                                </span>
                                <span id="preview-pin-badge" class="hidden px-2.5 py-1 rounded-lg bg-gray-900 text-white text-[10px] font-bold uppercase tracking-wider items-center gap-1.5 transition-all duration-300">
                                    <i class="bi bi-pin-fill text-[10px]"></i> Pinned
                                </span>
                            </div>
                            <div id="preview-date" class="text-xs font-bold text-gray-400 uppercase tracking-wide">
                                {{ now()->format('M d, Y • g:i A') }}
                            </div>
                        </div>

                        {{-- Body Content --}}
                        <div id="preview-content" class="text-base text-gray-700 leading-relaxed whitespace-pre-line font-medium antialiased break-words">
                            Start typing in the form to see your announcement preview...
                        </div>

                        {{-- Image Preview --}}
                        <div id="preview-image-container" class="{{ $announcement->image ? '' : 'hidden' }} relative group">
                            <img id="preview-image" src="{{ $announcement->image ? asset('storage/'.$announcement->image) : '#' }}" alt="Preview" class="w-full h-auto rounded-xl border border-gray-100 shadow-sm object-cover max-h-[300px]">
                            <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors rounded-xl"></div>
                        </div>
                    </div>

                    {{-- Preview Footer --}}
                    <div class="pt-6 border-t border-gray-50 flex items-center justify-between opacity-50">
                        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                AD
                            </div>
                            <span>Posted by Administration</span>
                        </div>
                        <div class="w-20 h-8 bg-gray-100 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('announcementForm');
    
    // Elements
    const inputs = {
        title: document.getElementById('input-title'),
        content: document.getElementById('input-content'),
        category: document.getElementById('input-category'),
        date: document.getElementById('input-date'),
        image: document.getElementById('input-image'),
        isPinned: document.getElementById('is_pinned'),
        pinDuration: document.getElementById('pin_duration')
    };

    const previews = {
        title: document.getElementById('preview-title'),
        content: document.getElementById('preview-content'),
        category: document.getElementById('preview-category'),
        date: document.getElementById('preview-date'),
        image: document.getElementById('preview-image'),
        imageContainer: document.getElementById('preview-image-container'),
        pinBadge: document.getElementById('preview-pin-badge'),
        pinDurationContainer: document.getElementById('pin-duration-container')
    };

    // Category Color Mapping
    const categoryStyles = {
        'Emergency': 'bg-red-50 text-red-600 border-red-100',
        'Meeting': 'bg-blue-50 text-blue-600 border-blue-100',
        'Maintenance': 'bg-amber-50 text-amber-600 border-amber-100',
        'Security': 'bg-slate-100 text-slate-700 border-slate-200',
        'Event': 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'Finance': 'bg-purple-50 text-purple-600 border-purple-100',
        'default': 'bg-gray-100 text-gray-600 border-gray-200'
    };

    function updatePreview() {
        // Title
        previews.title.textContent = inputs.title.value || 'Untitled Announcement';
        
        // Content
        previews.content.textContent = inputs.content.value || 'Start typing in the form to see your announcement preview...';
        
        // Category
        const catValue = inputs.category.value;
        previews.category.textContent = catValue || 'General';
        previews.category.className = `px-3 py-1 rounded-lg font-bold text-[11px] uppercase tracking-wider border transition-all duration-300 ${categoryStyles[catValue] || categoryStyles.default}`;
        
        // Date
        if (inputs.date.value) {
            const date = new Date(inputs.date.value);
            previews.date.textContent = date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        // Pin Badge
        if (inputs.isPinned.checked) {
            previews.pinBadge.classList.remove('hidden');
            previews.pinBadge.classList.add('flex');
            previews.pinBadge.innerHTML = `<i class="bi bi-pin-fill text-[10px]"></i> Pinned • ${inputs.pinDuration.value} Day${inputs.pinDuration.value > 1 ? 's' : ''}`;
            previews.pinDurationContainer.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            previews.pinBadge.classList.add('hidden');
            previews.pinBadge.classList.remove('flex');
            previews.pinDurationContainer.classList.add('opacity-50', 'pointer-events-none');
        }
    }

    // Input Listeners
    inputs.title.addEventListener('input', updatePreview);
    inputs.content.addEventListener('input', updatePreview);
    inputs.category.addEventListener('change', updatePreview);
    inputs.date.addEventListener('change', updatePreview);
    inputs.isPinned.addEventListener('change', updatePreview);
    inputs.pinDuration.addEventListener('change', updatePreview);

    // Image Preview
    inputs.image.addEventListener('change', function() {
        const file = this.files[0];
        const fileNameDisplay = document.getElementById('file-name');
        
        if (file) {
            fileNameDisplay.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                previews.image.src = e.target.result;
                previews.imageContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            fileNameDisplay.textContent = 'Choose an image...';
            @if(!$announcement->image)
                previews.imageContainer.classList.add('hidden');
            @endif
        }
    });

    // Initial call
    updatePreview();
});
</script>
@endpush
@endsection
