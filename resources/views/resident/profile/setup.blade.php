@extends('layouts.resident')

@section('title', 'Setup Profile')

@section('content')
<div class="container mt-4" style="max-width: 600px;">
    <h2 class="mb-4">Setup Your Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('resident.profile.update') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" name="name" class="form-control"
                   value="{{ old('name', $user->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input id="address" name="address" class="form-control"
                   value="{{ old('address', $user->address ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="block_lot" class="form-label">Block & Lot</label>
            <input id="block_lot" name="block_lot" class="form-control"
                   value="{{ old('block_lot', $user->block_lot ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="contact" class="form-label">Contact Number</label>
            <input id="contact" name="contact_number" class="form-control"
                   value="{{ old('contact_number', $user->resident->contact_number ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" name="email" type="email" class="form-control"
                   value="{{ old('email', $user->email ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Save Profile</button>
    </form>
</div>
@endsection
