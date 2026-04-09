<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SmsTemplateService;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function __construct(private SmsTemplateService $templates)
    {
    }

    public function index()
    {
        return view('admin.system.sms-templates', [
            'templates' => $this->templates->all(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'dues_reminder' => 'required|string|max:1000',
            'penalty_notice' => 'required|string|max:1000',
        ]);

        $this->templates->save($validated);

        return back()->with('success', 'SMS templates updated successfully.');
    }
}
