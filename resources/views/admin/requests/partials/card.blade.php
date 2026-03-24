@php
    $statusClasses = match($req->status) {
        'pending' => 'bg-gray-50 text-gray-700 border-gray-200',
        'in progress' => 'bg-emerald-50/50 text-emerald-700 border-emerald-100',
        'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'rejected' => 'bg-red-50 text-red-600 border-red-100',
        default => 'bg-gray-50 text-gray-700 border-gray-200',
    };
    $priorityClasses = match(strtolower($req->priority)) {
        'high' => 'text-red-600 bg-red-50 border-red-100',
        'medium' => 'text-orange-600 bg-orange-50 border-orange-100',
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
    class="bg-white rounded-[24px] p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer relative group overflow-hidden">
    
    <div class="absolute -right-12 -top-12 w-24 h-24 bg-gray-50 rounded-full blur-2xl group-hover:bg-emerald-50 transition-colors duration-500"></div>

    {{-- Status Pill --}}
    <span class="absolute top-6 right-6 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClasses }}">
        {{ $req->status }}
    </span>

    <div class="flex flex-col items-center text-center mt-4 relative z-10">
        <div class="relative mb-4">
            <img 
                src="{{ $req->resident?->photo ? asset('storage/' . $req->resident->photo) : asset('CDlogo.jpg') }}"
                onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                class="w-20 h-20 rounded-[24px] object-cover ring-4 ring-white shadow-md group-hover:ring-emerald-50 transition-all duration-300"
                alt="{{ $req->resident?->full_name ?? 'Resident' }}">
            <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-md">
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            </div>
        </div>
        
        <h3 class="text-base font-black text-gray-900 group-hover:text-brand-accent transition leading-tight mb-1 truncate w-full">
            {{ $req->resident->full_name ?? 'Unknown Resident' }}
        </h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">BLK {{ $req->resident->block ?? '-' }} • LOT {{ $req->resident->lot ?? '-' }}</p>
        
        <p class="text-sm font-black text-gray-800 mb-6 uppercase tracking-widest bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">{{ $req->type }}</p>

        {{-- Footer: Details --}}
        <div class="w-full border-t border-gray-50 pt-5 flex flex-col gap-3">
            <div class="flex justify-between items-center w-full">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">PRIORITY</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $priorityClasses }}">
                    {{ $req->priority }}
                </span>
            </div>
            <div class="flex justify-between items-center w-full">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">DATE</span>
                <div class="text-right">
                    <span class="text-[10px] font-black text-gray-600 tracking-tight block">{{ $req->created_at->format('M d, Y') }}</span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tight">{{ $req->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
