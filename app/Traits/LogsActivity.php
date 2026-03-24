<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Record an activity log entry
     *
     * @param string $action The action performed (created, updated, deleted, approved, etc.)
     * @param string $module The module name (dues, payments, residents, etc.)
     * @param string $description Human-readable description
     * @param array|null $metadata Additional data to store
     * @return void
     */
    protected function logActivity(string $action, string $module, string $description, ?array $metadata = null)
    {
        ActivityLog::create([
            'causer_id' => Auth::id(),
            'causer_type' => Auth::check() ? get_class(Auth::user()) : 'System',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }
}
