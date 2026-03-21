<div class="h-full flex flex-col bg-white shadow-xl overflow-y-auto rounded-l-2xl">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-white sticky top-0 z-10">
        <div class="space-y-1">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Announcement Details
            </p>
            <h2 class="text-lg font-bold text-gray-900">
                #{{ $announcement->id }}
            </h2>
        </div>

        <div class="flex items-center gap-1">
            <a href="{{ route('admin.announcements.edit', $announcement) }}"
               class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-100 transition"
               title="Edit">
                <i class="bi bi-pencil"></i>
            </a>

            <button onclick="closeAnnouncementDrawer()"
                    class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-100 transition"
                    title="Close">
                <i class="bi bi-x-lg text-base"></i>
            </button>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="p-6 space-y-7">

        {{-- TITLE & META --}}
        <div class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 uppercase tracking-wide">
                    <i class="bi bi-tag-fill text-[0.65rem]"></i>
                    {{ $announcement->category }}
                </span>

                @if($announcement->is_pinned)
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-[#800020] text-white uppercase tracking-wide">
                        <i class="bi bi-pin-fill text-[0.65rem]"></i>
                        Pinned
                    </span>
                @endif
            </div>

            <h3 class="text-2xl font-bold text-gray-900 leading-snug">
                {{ $announcement->title }}
            </h3>

            <p class="text-sm text-gray-500">
                Posted on {{ $announcement->date_posted->format('F d, Y \a\t h:i A') }}
            </p>
        </div>

        {{-- ANNOUNCEMENT CONTENT --}}
        <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 p-6 rounded-2xl border border-gray-200">
            {!! nl2br(e($announcement->content)) !!}
        </div>

        {{-- PIN INFO --}}
        @if($announcement->is_pinned && $announcement->pin_expires_at)
            <div class="flex gap-3 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm">
                <i class="bi bi-clock-history text-lg mt-0.5"></i>
                <div class="space-y-0.5">
                    <p class="font-semibold">Pin Expiration</p>
                    <p>
                        This announcement is pinned until
                        <span class="font-medium">
                            {{ $announcement->pin_expires_at->format('M d, Y') }}
                        </span>.
                    </p>
                </div>
            </div>
        @endif

        {{-- ACTIONS --}}
        <div class="pt-5 border-t border-gray-200">
            <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this announcement?');">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-red-50 text-red-700 font-semibold hover:bg-red-100 transition">
                    <i class="bi bi-trash-fill"></i>
                    Delete Announcement
                </button>
            </form>
        </div>

    </div>
</div>
