<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'System initialized and seeded with sample data.',
                'timestamp' => now(),
            ]);
        }
    }
}
