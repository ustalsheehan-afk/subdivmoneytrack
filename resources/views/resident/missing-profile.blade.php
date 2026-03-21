@extends('layouts.app')

@section('title', 'Profile Not Found')

@section('content')
<div class="max-w-xl mx-auto mt-20 p-6 bg-white shadow rounded-lg text-center">
    <h1 class="text-2xl font-bold mb-4">Resident Profile Not Found</h1>
    <p class="mb-6">We couldn't find your resident profile. Please contact the admin for assistance.</p>
    <a href="{{ route('login') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
        Go to Login
    </a>
</div>
@endsection
