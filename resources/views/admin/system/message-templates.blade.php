@extends('layouts.admin')

@section('title', 'Message Templates')
@section('page-title', 'Message Templates')

@section('content')
<div class="space-y-8 pb-10">
    <div class="glass-card p-8">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Resident Message Templates</h2>
                <p class="text-sm text-gray-500 mt-1">Manage dynamic templates used in Resident Messages Center. Each category supports 5 to 10 templates.</p>
            </div>
            <span class="px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-widest">Admin Managed</span>
        </div>

        <form method="POST" action="{{ route('admin.messages.templates.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
            @csrf
            <div class="lg:col-span-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Category</label>
                <select name="category" class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500/20" required>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Template Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500/20" required>
            </div>
            <div class="lg:col-span-3">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Default Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500/20" required>
            </div>
            <div class="lg:col-span-3">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Template Body</label>
                <textarea name="body" rows="2" class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500/20" required>{{ old('body') }}</textarea>
            </div>
            <div class="lg:col-span-1">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Active</label>
                <div class="mt-2 flex items-center gap-2 rounded-xl border border-gray-200 px-3 py-2.5">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-xs font-semibold text-gray-600">Yes</span>
                </div>
            </div>
            <div class="lg:col-span-1">
                <button type="submit" class="btn-premium w-full justify-center">Add</button>
            </div>
        </form>
    </div>

    @foreach($categories as $categoryKey => $categoryLabel)
        @php $rows = $templates->get($categoryKey, collect()); @endphp
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">{{ $categoryLabel }}</h3>
                <span class="text-[10px] font-black uppercase tracking-widest {{ $rows->count() < 5 ? 'text-red-500' : 'text-gray-500' }}">{{ $rows->count() }} templates</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Subject</th>
                            <th class="px-6 py-3">Body</th>
                            <th class="px-6 py-3 text-center">Used</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rows as $template)
                            <tr>
                                <td class="px-6 py-4">
                                    <input form="template-update-{{ $template->id }}" type="text" name="title" value="{{ $template->title }}" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm">
                                </td>
                                <td class="px-6 py-4">
                                    <input form="template-update-{{ $template->id }}" type="text" name="subject" value="{{ $template->subject }}" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm">
                                </td>
                                <td class="px-6 py-4">
                                    <textarea form="template-update-{{ $template->id }}" name="body" rows="2" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm">{{ $template->body }}</textarea>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-gray-500">{{ $template->use_count }}</td>
                                <td class="px-6 py-4 text-center">
                                    <input form="template-update-{{ $template->id }}" type="hidden" name="is_active" value="0">
                                    <input form="template-update-{{ $template->id }}" type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <form id="template-update-{{ $template->id }}" method="POST" action="{{ route('admin.messages.templates.update', $template) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn-secondary px-3 py-2">Save</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.messages.templates.destroy', $template) }}" onsubmit="return confirm('Delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger px-3 py-2">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No templates yet in this category.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
