@extends('layouts.admin')

@section('title', 'Manage Board Members')
@section('page-title', 'Board Members')

@section('content')

<div class="p-6 lg:p-10 space-y-10 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

{{-- Header --}}
<div class="flex items-center justify-between">

<div>
<h1 class="text-3xl font-extrabold text-gray-900">Board Members</h1>
<p class="text-sm text-gray-500 mt-1">
Manage subdivision leadership and board member profiles
</p>
</div>

<a href="{{ route('admin.board.create') }}"
class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold text-sm shadow hover:bg-blue-700 transition flex items-center gap-2">

<i class="bi bi-plus-lg"></i>
Add Member

</a>

</div>



{{-- Members Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

@forelse($members as $member)

<div class="relative bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl shadow-lg hover:shadow-xl transition duration-300 p-8 group">

{{-- Kebab Menu --}}
<div x-data="{open:false}" class="absolute top-5 right-5">

<button @click="open=!open"
class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition">

<i class="bi bi-three-dots-vertical"></i>

</button>

<div x-show="open"
@click.outside="open=false"
x-transition
class="absolute right-0 mt-2 w-36 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden z-50">

<a href="{{ route('admin.board.edit',$member->id) }}"
class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">

<i class="bi bi-pencil"></i>
Edit

</a>

<form action="{{ route('admin.board.destroy',$member->id) }}"
method="POST"
onsubmit="return confirm('Are you sure you want to remove this board member?')">

@csrf
@method('DELETE')

<button
class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">

<i class="bi bi-trash"></i>
Delete

</button>

</form>

</div>

</div>



{{-- Status Badge --}}
<div class="absolute top-6 left-6">

<span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
{{ $member->is_active ? 'bg-emerald-500 text-white' : 'bg-gray-400 text-white' }}">

{{ $member->is_active ? 'Active' : 'Inactive' }}

</span>

</div>



{{-- Profile --}}
<div class="flex flex-col items-center text-center mb-6">

@if($member->photo)

<img src="{{ asset('storage/'.$member->photo) }}"
class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-md mb-4 group-hover:scale-105 transition">

@else

<div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 mb-4">
<i class="bi bi-person text-3xl"></i>
</div>

@endif


<h3 class="text-lg font-bold text-gray-900">
{{ $member->name }}
</h3>

<p class="text-xs uppercase tracking-widest text-blue-600 font-semibold mt-1">
{{ $member->position }}
</p>

</div>



{{-- Bio --}}
@if($member->bio)

<p class="text-sm text-gray-500 italic text-center leading-relaxed mb-6">
"{{ $member->bio }}"
</p>

@endif



{{-- Contact --}}
<div class="space-y-2 text-sm">

@if($member->email)

<div class="flex items-center gap-2 text-gray-600">
<i class="bi bi-envelope text-blue-500"></i>
<span class="truncate">{{ $member->email }}</span>
</div>

@endif


@if($member->phone)

<div class="flex items-center gap-2 text-gray-600">
<i class="bi bi-telephone text-blue-500"></i>
<span>{{ $member->phone }}</span>
</div>

@endif


@if($member->facebook)

<div class="flex items-center gap-2 text-gray-600">
<i class="bi bi-facebook text-blue-500"></i>

<a href="{{ $member->facebook }}"
target="_blank"
class="hover:text-blue-600 transition">

View Profile

</a>

</div>

@endif

</div>

</div>

@empty

{{-- Empty State --}}
<div class="col-span-full text-center py-24">

<div class="w-20 h-20 bg-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">

<i class="bi bi-people text-4xl text-gray-400"></i>

</div>

<h3 class="text-xl font-bold text-gray-900 mb-2">
No Board Members Yet
</h3>

<p class="text-gray-500 text-sm">
Click "Add Member" to start building your subdivision leadership.
</p>

</div>

@endforelse

</div>

</div>

@endsection