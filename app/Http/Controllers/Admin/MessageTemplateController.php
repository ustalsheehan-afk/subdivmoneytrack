<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::query()
            ->orderBy('category')
            ->orderByDesc('use_count')
            ->orderBy('title')
            ->get()
            ->groupBy('category');

        return view('admin.system.message-templates', [
            'templates' => $templates,
            'categories' => MessageTemplate::CATEGORY_LABELS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(array_keys(MessageTemplate::CATEGORY_LABELS))],
            'title' => 'required|string|max:150',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:3000',
            'is_active' => 'nullable|boolean',
        ]);

        $categoryCount = MessageTemplate::query()->where('category', $validated['category'])->count();
        if ($categoryCount >= 10) {
            return back()->with('error', 'Maximum of 10 templates per category is allowed.')->withInput();
        }

        MessageTemplate::create([
            ...$validated,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return back()->with('success', 'Message template created successfully.');
    }

    public function update(Request $request, MessageTemplate $template)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:3000',
            'is_active' => 'nullable|boolean',
        ]);

        $newIsActive = (bool) ($validated['is_active'] ?? false);

        if (! $newIsActive && $template->is_active) {
            $activeCount = MessageTemplate::query()
                ->where('category', $template->category)
                ->where('is_active', true)
                ->count();

            if ($activeCount <= 5) {
                return back()->with('error', 'Each category must keep at least 5 active templates.');
            }
        }

        $template->update([
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'is_active' => $newIsActive,
        ]);

        return back()->with('success', 'Message template updated successfully.');
    }

    public function destroy(MessageTemplate $template)
    {
        $activeCount = MessageTemplate::query()
            ->where('category', $template->category)
            ->where('is_active', true)
            ->count();

        if ($template->is_active && $activeCount <= 5) {
            return back()->with('error', 'Cannot delete. Each category must keep at least 5 active templates.');
        }

        $template->delete();

        return back()->with('success', 'Message template deleted successfully.');
    }
}
