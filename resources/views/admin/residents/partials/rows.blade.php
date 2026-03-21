@foreach($residents as $resident)
<tr onclick="openResidentDrawer({{ $resident->id }})"
    id="resident-row-{{ $resident->id }}"
    data-id="{{ $resident->id }}"
    class="hover:bg-gray-50 cursor-pointer transition-all group border-b border-gray-100 last:border-0 shadow-sm hover:shadow-md">

    {{-- CHECKBOX --}}
    <td class="p-4 text-center w-12 resident-checkbox-col hidden" onclick="event.stopPropagation()">
        <input type="checkbox" value="{{ $resident->id }}" class="resident-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors w-4 h-4" onchange="updateBulkAction()">
    </td>

    {{-- PHOTO --}}
    <td class="p-4 text-center w-20">
        <img 
            src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}"
            onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
            class="w-10 h-10 rounded-full object-cover mx-auto ring-2 ring-gray-100 group-hover:ring-blue-200 transition-all duration-300 transform group-hover:scale-105"
            alt="{{ $resident->first_name }} {{ $resident->last_name }}"
            title="Click to view full image"
            onclick="event.stopPropagation(); window.open(this.src,'_blank')">
    </td>

    {{-- NAME --}}
    <td class="p-4 font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 whitespace-nowrap">
        {{ $resident->first_name }} {{ $resident->last_name }}
    </td>

    {{-- EMAIL --}}
    <td class="p-4 text-gray-600 text-sm whitespace-nowrap">{{ $resident->email }}</td>

    {{-- CONTACT --}}
    <td class="p-4 text-gray-600 text-sm font-mono whitespace-nowrap">{{ $resident->contact_number }}</td>

    {{-- BLOCK --}}
    <td class="p-4 text-gray-600 text-sm text-center font-mono whitespace-nowrap">
        <span class="bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 font-bold text-gray-700 block mx-auto w-12">{{ $resident->block ?? '-' }}</span>
    </td>

    {{-- MOVE-IN DATE --}}
    <td class="p-4 text-center text-gray-600 text-sm whitespace-nowrap">
        {{ $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-' }}
    </td>

    {{-- STATUS --}}
    <td class="p-4 text-center whitespace-nowrap">
        @php
            $statusClasses = $resident->status === 'active' 
                ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border-green-300 shadow-sm'
                : 'bg-gray-100 text-gray-600 border-gray-200';
        @endphp
        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClasses }} transition-all duration-300">
            {{ ucfirst($resident->status) }}
        </span>
    </td>
</tr>
@endforeach
