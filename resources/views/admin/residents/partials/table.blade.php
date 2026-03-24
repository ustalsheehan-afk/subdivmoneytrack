{{-- SCROLL CONTAINER (REQUIRED FOR INFINITE SCROLL) --}}
<div id="scrollContainer" class="h-[70vh] overflow-y-auto">

    {{-- LIST VIEW --}}
    <div id="listView">
        <div class="overflow-x-auto bg-white rounded-2xl shadow border border-gray-100">
            <table class="min-w-full text-sm text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 font-semibold rounded-t-2xl">
                    <tr>
                        {{-- SELECTION COLUMN --}}
                        <th class="resident-checkbox-col hidden px-4 py-3 border-b rounded-tl-2xl">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>

                        <th class="px-6 py-3 border-b">#</th>
                        <th class="px-6 py-3 border-b">Name</th>
                        <th class="px-6 py-3 border-b">Email</th>
                        <th class="px-6 py-3 border-b">Contact</th>
                        <th class="px-6 py-3 border-b">Block & Lot</th>
                        <th class="px-6 py-3 border-b">Move-in Date</th>
                        <th class="px-6 py-3 border-b text-center rounded-tr-2xl">Actions</th>
                    </tr>
                </thead>

                {{-- IMPORTANT ID --}}
                <tbody id="residentsBody" class="text-gray-700 divide-y">
                    @forelse($residents as $index => $user)
                        <tr onclick="openResidentDrawer({{ $user->id }})"
                            class="hover:bg-gray-50 transition duration-150 cursor-pointer">

                            {{-- CHECKBOX --}}
                            <td class="resident-checkbox-col hidden px-4 py-3" onclick="event.stopPropagation()">
                                <input type="checkbox"
                                       class="resident-checkbox"
                                       value="{{ $user->id }}"
                                       onchange="updateBulkAction()">
                            </td>

                            <td class="px-6 py-3">
                                {{ $loop->iteration + ($residents->currentPage() - 1) * $residents->perPage() }}
                            </td>

                            <td class="px-6 py-3 font-medium text-gray-900">{{ $user->name }}</td>

                            <td class="px-6 py-3 text-gray-600 truncate">{{ $user->email }}</td>

                            <td class="px-6 py-3 text-gray-600">
                                @if($user->resident?->contact_number)
                                    {{ $user->resident->contact_number }}
                                @else
                                    <span class="text-gray-400 italic">—</span>
                                @endif
                            </td>

                            <td class="px-6 py-3">
                                @if($user->resident?->block_lot)
                                    <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">
                                        {{ $user->resident->block_lot }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">—</span>
                                @endif
                            </td>

                            <td class="px-6 py-3 text-gray-600">
                                @if($user->resident?->move_in_date)
                                    {{ \Carbon\Carbon::parse($user->resident->move_in_date)->format('M d, Y') }}
                                @else
                                    <span class="text-gray-400 italic">Not set</span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-6 py-3 text-center" onclick="event.stopPropagation()">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.residents.edit', $user->id) }}"
                                       class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 hover:shadow transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.residents.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this resident?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 hover:shadow transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-6 text-center text-gray-500 italic">
                                No residents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRID VIEW (placeholder – you can design later) --}}
    <div id="gridView" class="hidden p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($residents as $user)
            <div onclick="openResidentDrawer({{ $user->id }})"
                 class="bg-white p-4 rounded-xl shadow border border-gray-100 hover:shadow-lg transition cursor-pointer">
                <h3 class="font-bold text-gray-900">{{ $user->name }}</h3>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $user->resident?->block_lot ?? 'No block/lot' }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- LOAD MORE --}}
    <div id="loadMoreContainer" class="text-center py-4">
        @if($residents->hasMorePages())
            <button id="loadMoreBtn"
                    onclick="loadMore()"
                    class="px-4 py-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                Load More
            </button>
        @else
            <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-2">End of List</p>
        @endif

        <div id="loadingSpinner" class="hidden mt-2">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900 mx-auto"></div>
        </div>
    </div>

</div>
