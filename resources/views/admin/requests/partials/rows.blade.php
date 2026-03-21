@if($requests->count() > 0)
    @foreach($requests as $req)
    <tr onclick="viewRequest({{ $req->id }})" 
        class="hover:bg-gray-50 cursor-pointer transition group border-b border-gray-100 last:border-0">
        {{-- Resident --}}
        <td class="px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">
                    {{ substr($req->resident->first_name ?? '?', 0, 1) }}{{ substr($req->resident->last_name ?? '?', 0, 1) }}
                </div>
                <div>
                    <p class="font-medium text-gray-900">{{ $req->resident->full_name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">B{{ $req->resident->block ?? '-' }} L{{ $req->resident->lot ?? '-' }}</p>
                </div>
            </div>
        </td>

        {{-- Type --}}
        <td class="px-4 py-3 text-gray-700 font-medium">
            {{ $req->type }}
        </td>

        {{-- Description --}}
        <td class="px-4 py-3 text-gray-500 text-sm max-w-xs truncate" title="{{ $req->description }}">
            {{ $req->description }}
        </td>

        {{-- Priority --}}
        <td class="px-4 py-3 text-center">
            @php
                $priorityClass = match(strtolower($req->priority)) {
                    'high' => 'bg-red-100 text-red-700',
                    'medium' => 'bg-yellow-100 text-yellow-700',
                    default => 'bg-green-100 text-green-700',
                };
            @endphp
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $priorityClass }}">
                {{ ucfirst($req->priority) }}
            </span>
        </td>

        {{-- Status --}}
        <td class="px-4 py-3 text-center">
            @php
                $statusClass = match($req->status) {
                    'pending' => 'bg-gray-100 text-gray-700',
                    'in progress' => 'bg-blue-100 text-blue-700',
                    'completed' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                {{ ucfirst($req->status) }}
            </span>
        </td>

        {{-- Date --}}
        <td class="px-4 py-3 text-center text-gray-500 whitespace-nowrap">
            {{ $req->created_at->format('M d, Y') }}
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="px-4 py-8 text-center text-gray-500 bg-gray-50">
            <div class="flex flex-col items-center justify-center">
                <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                <p>No service requests found matching your filters.</p>
            </div>
        </td>
    </tr>
@endif
