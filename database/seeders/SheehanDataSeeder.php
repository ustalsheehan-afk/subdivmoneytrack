<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resident;
use App\Models\Due;
use App\Models\Payment;
use Carbon\Carbon;

class SheehanDataSeeder extends Seeder
{
    public function run(): void
    {
        // 🧍‍♂️ Find existing user
        $user = User::where('email', 'sheehan@example.com')->first();

        if (!$user) {
            $this->command->warn("⚠️ sheehan@example.com not found. Please register that account first.");
            return;
        }

        // 🏡 Link or create resident profile
        $resident = Resident::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => 'Sheehan',
                'last_name' => 'Ustal',
                'contact_number' => '09123456789',
                'email' => 'sheehan@example.com',
                'block' => '12',
                'lot' => '8',
                'status' => 'active',
            ]
        );

       // 💰 Sample dues data
$dues = [
    [
        'title' => 'January 2025 Monthly Due',
        'description' => 'Regular monthly maintenance fee.',
        'amount' => 500,
        'due_date' => Carbon::create(2025, 1, 31),
        'status' => 'unpaid',
    ],
    [
        'title' => 'February 2025 Monthly Due',
        'description' => 'Regular monthly maintenance fee.',
        'amount' => 500,
        'due_date' => Carbon::create(2025, 2, 28),
        'status' => 'unpaid',
    ],
];

        foreach ($dues as $dueData) {
            $due = Due::updateOrCreate(
                ['title' => $dueData['title']],
                [
                    'description' => $dueData['description'],
                    'amount' => $dueData['amount'],
                    'due_date' => $dueData['due_date'],
                    'status' => $dueData['status'],
                ]
            );

            // Add one approved payment for January due
            if ($due->title === 'January 2025 Monthly Due') {
                Payment::updateOrCreate(
                    [
                        'resident_id' => $resident->id,
                        'due_id' => $due->id,
                    ],
                    [
                        'amount' => 500,
                        'date_paid' => Carbon::now()->subDays(5),
                        'payment_method' => 'GCash',
                        'proof' => 'sample_receipt.jpg',
                        'status' => 'approved',
                    ]
                );
            }
        }

        $this->command->info('✅ Sample dues and payment seeded successfully for sheehan@example.com!');
    }
}
