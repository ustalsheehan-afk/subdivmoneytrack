@foreach($requests as $req)
@php
    // Status Styles (matching Payments uniform design)
    $statusStyles = match($req->status) {
        'pending' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'dot' => 'bg-gray-500'],
        'in progress' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500'],
        'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
        'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'dot' => 'bg-red-500'],
        default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'dot' => 'bg-gray-500'],
    };

    // Priority Styles
    $priorityClass = match(strtolower($req->priority)) {
        'high' => 'text-red-600 bg-red-50 border-red-100',
        'medium' => 'text-amber-600 bg-amber-50 border-amber-100',
        'low' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
        default => 'text-gray-600 bg-gray-50 border-gray-100',
    };

    // Data for Drawer
    $requestData = [
        'id' => $req->id,
        'customTitle' => $req->type,
        'type' => $req->type,
        'resident_initials' => substr($req->resident->first_name ?? '?', 0, 1) . substr($req->resident->last_name ?? '?', 0, 1),
        'resident_name' => $req->resident->full_name ?? 'Unknown Resident',
        'resident_property' => 'Block ' . ($req->resident->block ?? '-') . ' Lot ' . ($req->resident->lot ?? '-'),
        'resident_contact' => $req->resident->contact ?? 'No contact info',
        'status' => $req->status,
        'status_text' => ucfirst($req->status),
        'priority_text' => ucfirst($req->priority),
        'date' => $req->created_at->format('M d, Y h:i A'),
        'description' => $req->description,
        'photo_url' => $req->photo ? asset('storage/' . $req->photo) : null,
        'update_url' => route('admin.requests.updateStatus', $req->id),
        'view_url' => route('admin.requests.show', $req->id),
    ];
@endphp

<tr onclick="loadRequestDetails({{ json_encode($requestData) }})" 
    class="cursor-pointer hover:bg-gray-100 transition-all duration-200 group border-l-4 border-transparent">
    
    {{-- Resident --}}
    <td class="px-4 py-3 align-middle text-left">
        <div class="flex items-center gap-3">
            <img 
                src="{{ $req->resident->photo ? asset('storage/' . $req->resident->photo) : asset('CDlogo.jpg') }}"
                onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-100 group-hover:ring-blue-200 transition-all duration-300 shadow-sm"
                alt="{{ $req->resident->full_name ?? 'Resident' }}">
            <div class="min-w-0">
                <p class="font-bold text-gray-900 group-hover:text-blue-700 transition text-sm truncate">{{ $req->resident->full_name ?? 'Unknown Resident' }}</p>
                <p class="text-[10px] text-gray-500 font-medium">Blk {{ $req->resident->block ?? '-' }} • Lot {{ $req->resident->lot ?? '-' }}</p>
            </div>
        </div>
    </td>

    {{-- Type --}}
    <td class="px-4 py-3 text-center align-middle">
        <span class="text-sm text-gray-700 font-bold capitalize whitespace-nowrap">{{ $req->type }}</span>
    </td>

    {{-- Date --}}
    <td class="px-4 py-3 text-center align-middle">
        <div class="flex flex-col items-center">
            <span class="text-xs font-bold text-gray-900">{{ $req->created_at->format('M d, Y') }}</span>
            <span class="text-[10px] text-gray-400 font-medium">{{ $req->created_at->diffForHumans() }}</span>
        </div>
    </td>

    {{-- Priority --}}
    <td class="px-4 py-3 text-center align-middle">
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-black border uppercase tracking-wider {{ $priorityClass }}">
            {{ ucfirst($req->priority) }}
        </span>
    </td>

    {{-- Status --}}
    <td class="px-4 py-3 text-center align-middle">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest {{ $statusStyles['bg'] }} {{ $statusStyles['text'] }} {{ $statusStyles['border'] }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
            {{ $req->status }}
        </span>
    </td>

</tr>
@endforeach
