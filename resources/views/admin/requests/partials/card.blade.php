@php
    $statusClasses = match($req->status) {
        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
        'in progress' => 'bg-blue-50 text-blue-600 border-blue-100',
        'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'rejected' => 'bg-red-50 text-red-600 border-red-100',
        default => 'bg-gray-50 text-gray-600 border-gray-100',
    };
    $priorityClasses = match($req->priority) {
        'high' => 'text-red-600 bg-red-50 border-red-100',
        'medium' => 'text-amber-600 bg-amber-50 border-amber-100',
        'low' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
        default => 'text-gray-600 bg-gray-50 border-gray-100',
    };
    $requestData = [
        'id' => $req->id,
        'resident_name' => $req->resident->full_name ?? 'Unknown',
        'type' => $req->type,
        'description' => $req->description,
        'status' => ucfirst($req->status),
        'priority' => ucfirst($req->priority),
        'date' => $req->created_at->format('M d, Y h:i A'),
        'photo_url' => $req->resident->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($req->resident->photo) 
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($req->resident->photo) 
            : null
    ];
@endphp
<div onclick="loadRequestDetails({{ json_encode($requestData) }})"
    class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md transition-all duration-200 cursor-pointer relative group">
    
    {{-- Status Pill --}}
    <span class="absolute top-4 right-4 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusClasses }}">
        {{ $req->status }}
    </span>

    <div class="flex flex-col items-center text-center mt-2">
        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xl font-bold shadow-sm overflow-hidden mb-3 ring-2 ring-gray-100 group-hover:ring-blue-50 transition-all">
            @if($req->resident->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($req->resident->photo))
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($req->resident->photo) }}" class="w-full h-full object-cover" alt="Resident Photo">
            @else
                {{ substr($req->resident->first_name ?? '?', 0, 1) }}{{ substr($req->resident->last_name ?? '?', 0, 1) }}
            @endif
        </div>
        
        <h3 class="text-base font-bold text-gray-900 group-hover:text-blue-700 transition leading-tight mb-1">
            {{ $req->resident->full_name ?? 'Unknown Resident' }}
        </h3>
        <p class="text-xs text-gray-500 mb-3">B{{ $req->resident->block ?? '-' }} L{{ $req->resident->lot ?? '-' }}</p>
        
        <p class="text-sm font-bold text-gray-800 mb-4 capitalize">{{ $req->type }}</p>

        {{-- Footer: Details --}}
        <div class="w-full border-t border-gray-50 pt-3 flex flex-col gap-1 text-xs text-gray-600">
            <div class="flex justify-between w-full">
                <span class="text-gray-400">Priority</span>
                <span class="font-medium capitalize {{ $priorityClasses }} px-1.5 rounded-md">{{ $req->priority }}</span>
            </div>
            <div class="flex justify-between w-full">
                <span class="text-gray-400">Date</span>
                <span class="font-medium">{{ $req->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>
</div>
