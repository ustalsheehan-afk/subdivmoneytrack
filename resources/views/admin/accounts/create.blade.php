@extends('layouts.admin')

@section('title', 'Create Account')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Create Account</h1>

    <a href="{{ route('admin.accounts.index') }}"
        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg transition">
        ← Back to Accounts
    </a>
</div>

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 max-w-xl mx-auto">
    <form action="{{ route('admin.accounts.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Homeowner Dropdown --}}
        <div>
            <label for="homeowner_id" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">
                Select Homeowner
            </label>
            <select name="homeowner_id" id="homeowner_id" required
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                <option value="">-- Choose Homeowner --</option>
                @foreach($homeowners as $homeowner)
                    <option value="{{ $homeowner->id }}" {{ old('homeowner_id') == $homeowner->id ? 'selected' : '' }}>
                        {{ $homeowner->name }} ({{ $homeowner->email ?? 'No Email' }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Password</label>
            <input type="password" name="password" id="password" required
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Role Selector --}}
        <div>
            <label for="role" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Role</label>
            <select name="role" id="role" required
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                <option value="resident" {{ old('role') == 'resident' ? 'selected' : '' }}>Resident</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        {{-- Status (Active Toggle) --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="active" id="active" value="1" checked
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
            <label for="active" class="text-gray-700 dark:text-gray-300">Active Account</label>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.accounts.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                Cancel
            </a>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                💾 Create Account
            </button>
        </div>
    </form>
</div>
@endsection
