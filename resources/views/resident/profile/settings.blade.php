@extends('resident.layouts.app')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="min-h-[60vh] w-full bg-white flex items-start justify-center py-12 px-4 md:px-0">
    <div class="w-full max-w-2xl border border-slate-200 rounded-none shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">Account Settings</h1>
                <p class="text-xs text-slate-500 mt-1">Minimal view of your account details.</p>
            </div>
        </div>

        <div class="px-6 py-6 space-y-8">
            <section class="space-y-3">
                <h2 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Profile</h2>
                <div class="flex items-center justify-between text-sm">
                    <div class="space-y-1">
                        <p class="text-slate-500">Name</p>
                        <p class="font-medium text-slate-900">
                            {{ $resident->first_name }} {{ $resident->last_name }}
                        </p>
                    </div>
                    <a href="{{ route('resident.profile.edit') }}"
                       class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Edit
                    </a>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="space-y-1">
                        <p class="text-slate-500">Email</p>
                        <p class="font-medium text-slate-900 break-all">
                            {{ $resident->email }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Security</h2>
                <p class="text-xs text-slate-500">
                    Password changes are managed from your profile edit page.
                </p>
                <a href="{{ route('resident.profile.edit') }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold border border-slate-300 text-slate-800 hover:bg-slate-900 hover:text-white transition">
                    Manage password
                </a>
            </section>

            <section class="space-y-3">
                <h2 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Notifications</h2>
                <p class="text-xs text-slate-500">
                    Notification preferences will be available here in a future update.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection

