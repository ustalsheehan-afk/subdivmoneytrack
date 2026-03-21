<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $resident = Auth::user()->resident;
        $messages = SupportMessage::where('resident_id', $resident->id)
            ->latest()
            ->paginate(10);

        return view('resident.support.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $resident = Auth::user()->resident;
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support_attachments', 'public');
        }

        $message = SupportMessage::create([
            'resident_id' => $resident->id,
            'category' => $request->category,
            'message' => $request->message,
            'resident_attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'admin_id' => $admin->id,
                'title' => '💬 New Support Message',
                'message' => "{$resident->full_name} has sent a new support message regarding '{$request->category}'.",
                'type' => 'system',
                'link' => route('admin.support.index'),
                'is_read' => false,
            ]);
        }

        return redirect()->route('resident.support.index')->with('success', 'Message sent. We’ll get back to you shortly.');
    }

    public function show($id)
    {
        $resident = Auth::user()->resident;
        $message = SupportMessage::where('resident_id', $resident->id)->findOrFail($id);

        return response()->json($message);
    }
}
