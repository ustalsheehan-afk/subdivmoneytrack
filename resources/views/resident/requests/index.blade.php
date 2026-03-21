@extends('resident.layouts.app')
@section('title','My Requests')

@section('content')
<div class="max-w-5xl mx-auto mt-8 sm:mt-10 lg:mt-12 px-4 sm:px-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4
                bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600
                        flex items-center justify-center shadow-inner">
                <i class="bi bi-inbox-fill text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-gray-900">Service Requests</h1>
                <p class="text-xs text-gray-500">Track and manage your submitted requests</p>
            </div>
        </div>

        <a href="{{ route('resident.requests.create') }}"
           class="inline-flex items-center justify-center px-5 py-2.5
                  bg-blue-600 text-white rounded-xl
                  hover:bg-blue-700 font-semibold text-sm
                  shadow-sm hover:shadow-md transition">
            <i class="bi bi-plus-lg mr-1"></i> New Request
        </a>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3 text-xs font-bold text-gray-500 uppercase">Type</th>
                        <th class="px-4 sm:px-5 py-3 text-xs font-bold text-gray-500 uppercase">Submitted</th>
                        <th class="px-4 sm:px-5 py-3 text-xs font-bold text-gray-500 uppercase text-center">Status</th>
                        <th class="px-4 sm:px-5 py-3 text-xs font-bold text-gray-500 uppercase text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $request)
                        @php
                            $statusColors = [
                                'pending' => 'bg-orange-50 text-orange-700 border-orange-100',
                                'in progress' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'rejected' => 'bg-red-50 text-red-700 border-red-100',
                            ];
                            $statusClass = $statusColors[strtolower($request->status)] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                        @endphp

                        <tr class="hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('resident.requests.show', $request->id) }}'">
                            <td class="px-4 sm:px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($request->photo)
                                        <img src="{{ asset('storage/' . $request->photo) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400">
                                            <i class="bi bi-image text-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm capitalize">
                                            {{ $request->type }}
                                        </p>
                                        @if($request->description)
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                                {{ Str::limit($request->description, 40) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 sm:px-5 py-4 text-sm text-gray-500">
                                {{ $request->created_at->format('M d, Y') }}
                                <span class="block text-[11px] text-gray-400">
                                    {{ $request->created_at->format('h:i A') }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-5 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full
                                             text-[10px] font-black border uppercase tracking-widest {{ $statusClass }}">
                                    {{ $request->status }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('resident.requests.show', $request->id) }}"
                                       class="inline-flex items-center justify-center
                                              w-9 h-9 rounded-lg
                                              text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                       onclick="event.stopPropagation()">
                                        <i class="bi bi-eye text-lg"></i>
                                    </a>
                                    @if($request->status == 'pending')
                                    <a href="{{ route('resident.requests.edit', $request->id) }}"
                                       class="inline-flex items-center justify-center
                                              w-9 h-9 rounded-lg
                                              text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                                       onclick="event.stopPropagation()">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-14">
                                <div class="w-16 h-16 bg-gray-100 rounded-full
                                            flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-inbox text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-gray-900 font-semibold">No requests found</h3>
                                <p class="text-gray-500 text-sm mt-1">
                                    Submit a new request to get started.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
