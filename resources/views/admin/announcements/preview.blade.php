{{-- Preview Modal --}}
<div id="previewModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-2xl rounded-xl shadow-xl overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">
                Preview Announcement
            </h2>

            <button onclick="closePreview()"
                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-6 space-y-4">

            {{-- TITLE --}}
            <h1 id="pTitle" class="text-xl font-semibold text-gray-900 leading-tight"></h1>

            {{-- META --}}
            <div class="flex flex-wrap items-center gap-2 text-xs">

                {{-- CATEGORY --}}
                <span id="pCategory"
                      class="px-2.5 py-1 rounded-md font-medium bg-gray-100 text-gray-700">
                </span>

                {{-- PIN --}}
                <span id="pPinStatus"
                      class="px-2.5 py-1 rounded-md font-medium bg-gray-900 text-white hidden">
                </span>

                {{-- DATE --}}
                <span id="pDatePosted"
                      class="text-gray-400 text-xs ml-auto">
                </span>

            </div>

            {{-- IMAGE --}}
            <img id="pImg"
                 class="hidden w-full rounded-lg border border-gray-100">

            {{-- CONTENT --}}
            <p id="pContent"
               class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
            </p>

        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-2">

            <button onclick="closePreview()"
                class="px-4 py-2 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                Close
            </button>

            <button type="submit"
                class="px-4 py-2 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800">
                Confirm & Save
            </button>

        </div>

    </div>
</div>

<script>
function openPreview() {
    const title = document.querySelector('[name=title]').value;
    const content = document.querySelector('[name=content]').value;
    const category = document.querySelector('[name=category]').value;
    const isPinned = document.getElementById('is_pinned').checked;
    const pinDuration = document.getElementById('pin_duration').value;
    const datePosted = document.querySelector('[name=date_posted]').value;

    // Title & Content
    document.getElementById('pTitle').innerText = title || 'Untitled Announcement';
    document.getElementById('pContent').innerText = content || 'No content provided.';

    // Category Badge (cleaner)
    const pCategory = document.getElementById('pCategory');
    pCategory.innerText = category || 'General';
    pCategory.className = `px-2.5 py-1 rounded-md font-medium text-xs ${getCategoryColor(category)}`;

    // Pin Badge
    const pPinStatus = document.getElementById('pPinStatus');
    if (isPinned) {
        pPinStatus.innerText = `Pinned • ${pinDuration} day(s)`;
        pPinStatus.classList.remove('hidden');
    } else {
        pPinStatus.classList.add('hidden');
    }

    // Date
    const pDatePosted = document.getElementById('pDatePosted');
    pDatePosted.innerText = datePosted ? formatDate(datePosted) : '';

    // Image
    const imgFile = document.querySelector('[name=image]').files[0];
    const pImg = document.getElementById('pImg');
    if (imgFile) {
        pImg.src = URL.createObjectURL(imgFile);
        pImg.classList.remove('hidden');
    } else {
        pImg.classList.add('hidden');
    }

    // Show modal
    const modal = document.getElementById('previewModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Close modal
function closePreview() {
    const modal = document.getElementById('previewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Category Colors (SOFT SaaS style)
function getCategoryColor(category) {
    switch(category) {
        case 'Emergency': return 'bg-red-50 text-red-600';
        case 'Meeting': return 'bg-blue-50 text-blue-600';
        case 'Maintenance': return 'bg-amber-50 text-amber-600';
        case 'Security': return 'bg-gray-100 text-gray-700';
        case 'Event': return 'bg-green-50 text-green-600';
        case 'Finance': return 'bg-purple-50 text-purple-600';
        default: return 'bg-gray-100 text-gray-600';
    }
}

// Date format
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// ESC close (pro UX)
document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") closePreview();
});
</script>