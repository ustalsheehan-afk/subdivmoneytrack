@extends('layouts.resident')

@section('title', 'Submit Request')
@section('page-title', 'Service Requests')

@section('content')
<div class="bg-white shadow-lg rounded-2xl p-6">
    <form action="{{ route('resident.requests.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Request Type</label>
            <input type="text" name="type" required class="w-full border p-3 rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Description</label>
            <textarea name="description" rows="4" required class="w-full border p-3 rounded-lg focus:ring focus:ring-blue-300"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Submit Request</button>
        </div>
    </form>

    <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-800">My Requests</h3>

    <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="py-3 px-4 text-left">#</th>
                <th class="py-3 px-4 text-left">Type</th>
                <th class="py-3 px-4 text-left">Description</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Submitted</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @if($requests->count() > 0)
                @foreach($requests as $req)
                <tr>
                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4">{{ $req->type }}</td>
                    <td class="py-3 px-4">{{ $req->description }}</td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-sm {{ $req->status == 'Approved' ? 'bg-green-100 text-green-800' : ($req->status == 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $req->status }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $req->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No requests submitted yet.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
