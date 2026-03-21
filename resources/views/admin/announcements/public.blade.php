@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
<h1 class="text-3xl font-bold mb-6">Announcements</h1>

@foreach($announcements as $a)
<div class="border p-4 rounded mb-4 bg-white">
<h2 class="font-bold text-xl">
@if($a->is_pinned) 📌 @endif {{ $a->title }}
</h2>
<p class="text-sm text-gray-500">
{{ $a->category }} • {{ $a->date_posted->format('M d, Y') }}
</p>

@if($a->image)
<img src="{{ asset('storage/'.$a->image) }}" class="my-3 rounded">
@endif

<p>{{ $a->content }}</p>
</div>
@endforeach
</div>
@endsection
