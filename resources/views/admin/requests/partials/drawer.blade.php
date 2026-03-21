<div class="h-full flex flex-col bg-white shadow-xl">
    {{-- HEADER --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-800">Request Details</h2>
        <button onclick="closeRequestDrawer()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    {{-- CONTENT --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-6">
        
        {{-- RESIDENT INFO --}}
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
             <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-lg font-bold">
                {{ substr($request->resident->first_name ?? '?', 0, 1) }}{{ substr($request->resident->last_name ?? '?', 0, 1) }}
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-lg">{{ $request->resident->full_name ?? 'Unknown' }}</h3>
                <p class="text-sm text-gray-500">Block {{ $request->resident->block ?? '-' }} Lot {{ $request->resident->lot ?? '-' }}</p>
                <p class="text-sm text-gray-500">{{ $request->resident->contact ?? '' }}</p>
            </div>
        </div>

        {{-- STATUS & PRIORITY --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Status</p>
                @php
                    $statusClass = match($request->status) {
                        'pending' => 'bg-gray-50 text-gray-700 border-gray-200',
                        'in progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                    };
                @endphp
                <span class="px-2.5 py-1 rounded-full text-sm font-semibold border {{ $statusClass }}">
                    {{ ucfirst($request->status) }}
                </span>
            </div>
            <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Priority</p>
                 @php
                    $priorityClass = match($request->priority) {
                        'High' => 'text-red-700',
                        'Medium' => 'text-yellow-700',
                        default => 'text-green-700',
                    };
                @endphp
                <p class="text-lg font-bold {{ $priorityClass }}">{{ $request->priority }}</p>
            </div>
        </div>

        {{-- DETAILS --}}
        <div class="space-y-4">
            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide border-b border-gray-100 pb-2">Request Information</h4>
            
            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">Type</p>
                    <p class="font-medium text-gray-900 text-lg">{{ $request->type }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Date Requested</p>
                    <p class="font-medium text-gray-900">{{ $request->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Description</p>
                    <p class="font-medium text-gray-900 bg-gray-50 p-4 rounded-lg border border-gray-100 whitespace-pre-wrap">{{ $request->description }}</p>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100">
             <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Update Status</h4>
             <form action="{{ route('admin.requests.updateStatus', $request->id) }}" method="POST" class="space-y-3">
                @csrf
                <select name="status" class="admin-form-select">
                    <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in progress" {{ $request->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $request->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="admin-btn-primary w-full">
                    Update Status
                </button>
            </form>
        </div>

    </div>
</div>
