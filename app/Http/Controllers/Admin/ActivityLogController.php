<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:logs.view')->only(['index']);
        $this->middleware('permission:logs.export')->only(['export']);
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with('causer');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_range')) {
            // Simple date range handling
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }

        $logs = $query->latest()->paginate(25);

        return view('admin.system.activity-logs', compact('logs'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('causer');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }

        $logs = $query->latest()->limit(5000)->get();

        $filename = 'activity-logs-' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['Time', 'User', 'Role', 'Action', 'Module', 'Description', 'IP Address']);

            foreach ($logs as $log) {
                $user = $log->causer;
                $userName = $user->name ?? ($user->full_name ?? 'System');
                $role = $log->metadata['role'] ?? (
                    (method_exists($user, 'rbacRole') ? ($user->rbacRole?->name ?? null) : null)
                    ?? ($user->role ?? '')
                );
                $ip = $log->metadata['ip'] ?? '';

                fputcsv($out, [
                    $log->created_at?->format('Y-m-d H:i:s'),
                    $userName,
                    $role,
                    $log->action,
                    $log->module,
                    $log->description,
                    $ip,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
