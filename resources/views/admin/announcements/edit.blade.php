@extends('layouts.admin')

@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-32">
    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" id="announcementForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="form-status" value="{{ $announcement->status }}">
        <input type="hidden" name="submit_action" id="submit-action" value="update">
        
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            {{-- LEFT PANEL (FORM) --}}
            <div class="lg:w-[60%] w-full space-y-6">
                <div class="glass-card p-8 space-y-8 bg-white border border-gray-100 rounded-[12px] shadow-sm">
                    
                    {{-- 1. BASIC INFO --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">1. Basic Info</span>
                            <div class="h-[1px] flex-1 bg-gray-100"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Announcement Title</label>
                                <input type="text" name="title" id="input-title"
                                       value="{{ old('title', $announcement->title) }}"
                                       placeholder="Enter a descriptive title..."
                                       class="w-full px-4 py-3 rounded-[10px] border border-gray-200 bg-gray-50/50 text-sm focus:ring-2 focus:ring-[#B6FF5C] focus:ring-offset-0 focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all duration-300"
                                       required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Category</label>
                                <select name="category" id="input-category" 
                                        class="w-full px-4 py-3 rounded-[10px] border border-gray-200 bg-gray-50/50 text-sm focus:ring-2 focus:ring-[#B6FF5C] focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all duration-300 cursor-pointer" 
                                        required>
                                    @foreach(['Event','Maintenance','Meeting','Security','Finance','Emergency'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $announcement->category) == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Priority Level</label>
                                <select name="priority" id="input-priority" 
                                        class="w-full px-4 py-3 rounded-[10px] border border-gray-200 bg-gray-50/50 text-sm focus:ring-2 focus:ring-[#B6FF5C] focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all duration-300 cursor-pointer">
                                    <option value="normal" {{ in_array(old('priority', $announcement->priority), ['normal', 'fyi']) ? 'selected' : '' }}>FYI (Normal)</option>
                                    <option value="high" {{ in_array(old('priority', $announcement->priority), ['high', 'important']) ? 'selected' : '' }}>Important</option>
                                    <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- 2. CONTENT --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">2. Content</span>
                            <div class="h-[1px] flex-1 bg-gray-100"></div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Announcement Message</label>
                            <textarea name="content" id="input-content"
                                      maxlength="1000"
                                      placeholder="Write your announcement details here..."
                                      class="w-full px-4 py-3 rounded-[10px] border border-gray-200 bg-gray-50/50 text-sm focus:ring-2 focus:ring-[#B6FF5C] focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all duration-300 resize-none min-h-[200px]"
                                      required>{{ old('content', $announcement->content) }}</textarea>
                            <div class="flex justify-end mt-2">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" id="char-count">0 / 1000</span>
                            </div>
                        </div>
                    </section>

                    {{-- 3. SCHEDULING --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">3. Scheduling</span>
                            <div class="h-[1px] flex-1 bg-gray-100"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Publish Date & Time</label>
                                <input type="datetime-local"
                                       name="date_posted"
                                       id="input-date"
                                       class="w-full px-4 py-3 rounded-[10px] border border-gray-200 bg-gray-50/50 text-sm focus:ring-2 focus:ring-[#B6FF5C] focus:border-[#B6FF5C] focus:bg-white focus:outline-none transition-all duration-300"
                                       value="{{ old('date_posted', $announcement->date_posted->format('Y-m-d\TH:i')) }}"
                                       required>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-[12px] border border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B6FF5C]"></div>
                                    </div>
                                    <div>
                                        <label for="is_pinned" class="text-xs font-bold text-gray-700 uppercase tracking-wide cursor-pointer">Pin to Top</label>
                                        <p class="text-[10px] text-gray-500 font-medium">Keep this at the top of the feed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 4. MEDIA --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">4. Media</span>
                            <div class="h-[1px] flex-1 bg-gray-100"></div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Attachment Image (Optional)</label>
                            <label class="group flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-[12px] bg-gray-50/50 hover:bg-white hover:border-[#B6FF5C] hover:border-solid cursor-pointer transition-all duration-300">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 group-hover:text-[#B6FF5C] mb-2 transition-colors"></i>
                                    <p id="file-name" class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors font-medium">
                                        {{ $announcement->image ? 'Change current image...' : 'Click to upload or drag and drop' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">PNG, JPG or JPEG (Max. 5MB)</p>
                                </div>
                                <input type="file" name="image" id="input-image" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </section>
                </div>
            </div>

            {{-- RIGHT PANEL (LIVE PREVIEW) --}}
            <div class="lg:w-[40%] w-full lg:sticky lg:top-32 space-y-6">
                <div class="glass-card bg-white border border-gray-100 rounded-[12px] shadow-lg overflow-hidden flex flex-col min-h-[500px]">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Live Preview</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="p-1.5 rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-[#B6FF5C] transition-colors shadow-sm">
                                <i class="bi bi-display text-sm"></i>
                            </button>
                            <button type="button" class="p-1.5 rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-[#B6FF5C] transition-colors shadow-sm">
                                <i class="bi bi-phone text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- PREVIEW CARD SIMULATION --}}
                    <div class="p-8 flex-1 flex flex-col">
                        {{-- Accent Line Simulator --}}
                        <div class="relative pl-8">
                            <div id="preview-accent-line" class="absolute left-0 top-0 bottom-0 w-[4px] bg-gray-200 rounded-full transition-all duration-300"></div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <div id="preview-icon-bg" class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gray-50 text-gray-400 border border-gray-100 transition-all duration-300">
                                    <i id="preview-icon" class="bi bi-megaphone-fill text-2xl"></i>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span id="preview-category-badge" class="text-[10px] font-black uppercase tracking-[0.1em] text-gray-400 transition-colors duration-300">
                                            General
                                        </span>
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span id="preview-date-badge" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                            {{ $announcement->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span id="preview-pin-badge" class="hidden px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                                            <i class="bi bi-pin-angle-fill mr-1"></i> Pinned
                                        </span>
                                        <span id="preview-priority-badge" class="hidden px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border"></span>
                                    </div>
                                </div>
                            </div>

                            <h4 id="preview-title" class="text-xl font-extrabold text-gray-900 leading-tight mb-4 break-words">
                                {{ $announcement->title }}
                            </h4>
                            
                            <div id="preview-content" class="text-sm text-gray-600 leading-relaxed font-medium whitespace-pre-line mb-6 min-h-[100px] break-words">
                                {{ $announcement->content }}
                            </div>

                            <div id="preview-image-container" class="{{ $announcement->image ? '' : 'hidden' }} rounded-2xl overflow-hidden border border-gray-100 shadow-sm mb-6">
                                <img id="preview-image" src="{{ $announcement->image ? asset('storage/'.$announcement->image) : '#' }}" alt="Preview" class="w-full h-auto object-cover max-h-[300px]">
                            </div>

                            <div class="flex items-center gap-6 mt-auto pt-6 border-t border-gray-50">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    <i class="bi bi-eye-fill"></i>
                                    <span>{{ $announcement->readers()->count() }} / {{ \App\Models\Resident::where('status', 'active')->count() }} Seen</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    <i class="bi bi-clock-history"></i>
                                    <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50/50 border-t border-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#B6FF5C] to-[#8AC941] flex items-center justify-center text-[10px] font-bold text-[#081412]">
                                AD
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-900 uppercase tracking-wide">Administrator</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">System Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STICKY ACTION BAR --}}
        <div class="fixed bottom-0 right-0 left-0 lg:left-72 bg-white/80 backdrop-blur-md border-t border-gray-100 p-6 z-40 transition-all duration-300">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="hidden md:flex flex-col">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</span>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                        <span class="text-xs font-bold text-gray-700">Editing Announcement</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <a href="{{ route('admin.announcements.index') }}" 
                       class="flex-1 md:flex-none px-6 py-3 bg-white border border-gray-200 text-gray-600 rounded-[12px] text-sm font-bold hover:bg-gray-50 transition-all duration-300 text-center">
                        Cancel
                    </a>
                    @if($announcement->status === 'draft')
                        <button type="button" onclick="submitAsDraft()"
                                class="hidden md:block px-6 py-3 bg-gray-100 text-gray-600 rounded-[12px] text-sm font-bold hover:bg-gray-200 transition-all duration-300">
                            Save as Draft
                        </button>
                    @endif
                    <button type="submit" @if($announcement->status === 'draft') onclick="prepareDraftPublish()" @endif
                            class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-8 py-3 bg-[#081412] text-[#B6FF5C] rounded-[12px] text-sm font-bold hover:shadow-[0_0_20px_rgba(182,255,92,0.3)] hover:-translate-y-0.5 transition-all duration-300 border border-[#B6FF5C]/20">
                        <i class="bi bi-check2-circle"></i>
                        {{ $announcement->status === 'draft' ? 'Publish Announcement' : 'Update Announcement' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const inputs = {
        title: document.getElementById('input-title'),
        content: document.getElementById('input-content'),
        category: document.getElementById('input-category'),
        priority: document.getElementById('input-priority'),
        date: document.getElementById('input-date'),
        image: document.getElementById('input-image'),
        isPinned: document.getElementById('is_pinned')
    };

    const previews = {
        title: document.getElementById('preview-title'),
        content: document.getElementById('preview-content'),
        category: document.getElementById('preview-category-badge'),
        date: document.getElementById('preview-date-badge'),
        icon: document.getElementById('preview-icon'),
        iconBg: document.getElementById('preview-icon-bg'),
        accentLine: document.getElementById('preview-accent-line'),
        image: document.getElementById('preview-image'),
        imageContainer: document.getElementById('preview-image-container'),
        pinBadge: document.getElementById('preview-pin-badge'),
        priorityBadge: document.getElementById('preview-priority-badge'),
        charCount: document.getElementById('char-count')
    };

    const config = {
        categories: {
            'Maintenance': { color: '#E6B566', icon: 'bi-tools' },
            'Meeting': { color: '#7DA2D6', icon: 'bi-people-fill' },
            'Event': { color: '#7FB69A', icon: 'bi-calendar-event-fill' },
            'Security': { color: '#8B8F9C', icon: 'bi-shield-lock-fill' },
            'Finance': { color: '#8FAE9E', icon: 'bi-cash-stack' },
            'Emergency': { color: '#C97A7A', icon: 'bi-exclamation-octagon-fill' },
            'default': { color: '#94a3b8', icon: 'bi-megaphone-fill' }
        },
        priorities: {
            'urgent': { label: 'Urgent', class: 'bg-red-50 text-red-600 border-red-100' },
            'high': { label: 'Important', class: 'bg-amber-50 text-amber-600 border-amber-100' },
            'normal': { label: 'FYI', class: 'bg-blue-50 text-blue-600 border-blue-100' }
        }
    };

    function updatePreview() {
        // Title & Content
        previews.title.textContent = inputs.title.value || 'Announcement Title';
        previews.content.textContent = inputs.content.value || 'Start typing to see your content here...';
        previews.charCount.textContent = `${inputs.content.value.length} / 1000`;

        // Category & Styling
        const cat = config.categories[inputs.category.value] || config.categories.default;
        const isPinned = inputs.isPinned.checked;
        
        previews.category.textContent = inputs.category.value || 'General';
        previews.category.style.color = cat.color;
        previews.icon.className = `bi ${cat.icon} text-2xl`;
        previews.iconBg.style.backgroundColor = `${cat.color}10`;
        previews.iconBg.style.color = cat.color;
        previews.accentLine.style.backgroundColor = isPinned ? '#10B981' : cat.color;

        // Pin Badge
        previews.pinBadge.classList.toggle('hidden', !isPinned);

        // Priority Badge
        const priority = inputs.priority.value;
        if (priority && priority !== 'normal') {
            previews.priorityBadge.textContent = config.priorities[priority].label;
            previews.priorityBadge.className = `px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border ${config.priorities[priority].class}`;
            previews.priorityBadge.classList.remove('hidden');
        } else {
            previews.priorityBadge.classList.add('hidden');
        }

        // Date
        if (inputs.date.value) {
            const date = new Date(inputs.date.value);
            previews.date.textContent = date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric'
            });
        }
    }

    // Event Listeners
    Object.values(inputs).forEach(input => {
        if (input && input.type !== 'file') {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        }
    });

    // Image Handling
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
            fileNameDisplay.textContent = '{{ $announcement->image ? 'Change current image...' : 'Click to upload or drag and drop' }}';
            @if(!$announcement->image)
                previews.imageContainer.classList.add('hidden');
            @endif
        }
    });

    // Initial Update
    updatePreview();
});

function submitAsDraft() {
    document.getElementById('submit-action').value = 'save_draft';
    document.getElementById('form-status').value = 'draft';
    document.getElementById('announcementForm').submit();
}

function prepareDraftPublish() {
    document.getElementById('submit-action').value = 'publish_draft';
    document.getElementById('form-status').value = 'active';
}
</script>
@endpush
@endsection
