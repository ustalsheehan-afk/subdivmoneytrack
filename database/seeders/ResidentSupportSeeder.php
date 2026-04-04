<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\User;
use App\Models\MessageThread;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResidentSupportSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Message::truncate();
        MessageThread::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create/Find Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'active' => true,
            ]
        );

        // 2. Create 5 Residents with realistic details
        $residentData = [
            [
                'first_name' => 'John',
                'last_name' => 'Troy Patalinghug',
                'email' => 'john@example.com',
                'block' => '12',
                'lot' => '45',
            ],
            [
                'first_name' => 'Jio',
                'last_name' => 'Licarte',
                'email' => 'jio@example.com',
                'block' => '05',
                'lot' => '22',
            ],
            [
                'first_name' => 'Jia',
                'last_name' => 'Licarte',
                'email' => 'jia@example.com',
                'block' => '08',
                'lot' => '11',
            ],
            [
                'first_name' => 'Jian',
                'last_name' => 'Licarte',
                'email' => 'jian@example.com',
                'block' => '02',
                'lot' => '15',
            ],
            [
                'first_name' => 'Chan',
                'last_name' => 'Licarte',
                'email' => 'chan@example.com',
                'block' => '10',
                'lot' => '03',
            ],
        ];

        $residents = [];
        foreach ($residentData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'password' => Hash::make('password'),
                    'role' => 'resident',
                    'active' => true,
                    'lot_unit' => "Blk {$data['block']} Lot {$data['lot']}",
                ]
            );

            $residents[] = Resident::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($data, [
                    'user_id' => $user->id,
                    'status' => 'active',
                    'membership_type' => 'Homeowner',
                    'property_type' => 'House & Lot'
                ])
            );
        }

        // 3. Thread Scenarios
        $scenarios = [
            [
                'subject' => 'Kitchen Sink Leak',
                'category' => 'Maintenance',
                'status' => 'replied',
                'messages' => [
                    ['sender_is_admin' => false, 'body' => 'Good morning. My kitchen sink has a major leak since last night. Can we have someone check it today?'],
                    ['sender_is_admin' => true, 'body' => 'Hello! We have noted your request. Our maintenance team will visit your unit at 2:00 PM today.'],
                    ['sender_is_admin' => false, 'body' => 'Thank you, I will be home by then.'],
                ],
                'days_ago' => 3,
            ],
            [
                'subject' => 'HOA Dues Double Charge',
                'category' => 'Billing',
                'status' => 'replied',
                'messages' => [
                    ['sender_is_admin' => false, 'body' => 'I noticed a double charge on my HOA dues for March. Can you please check my account?'],
                    ['sender_is_admin' => true, 'body' => 'We apologize for the inconvenience. We are reviewing our transaction logs.'],
                    ['sender_is_admin' => true, 'body' => 'We found the error and have applied a credit to your next bill.'],
                    ['sender_is_admin' => false, 'body' => 'Great, thank you for the quick fix!'],
                ],
                'days_ago' => 5,
            ],
            [
                'subject' => 'Clubhouse Booking Inquiry',
                'category' => 'Amenity',
                'status' => 'pending',
                'messages' => [
                    ['sender_is_admin' => false, 'body' => 'Is the clubhouse available for a private event on April 15th?'],
                ],
                'days_ago' => 1,
            ],
            [
                'subject' => 'Pet Policy Clarification',
                'category' => 'General',
                'status' => 'closed',
                'messages' => [
                    ['sender_is_admin' => false, 'body' => 'Are we allowed to have small pets in the subdivision?'],
                    ['sender_is_admin' => true, 'body' => 'Yes, pets are allowed as long as they are registered and kept on a leash.'],
                    ['sender_is_admin' => false, 'body' => 'Understood, thanks!'],
                ],
                'days_ago' => 7,
            ],
            [
                'subject' => 'Broken Street Lamp',
                'category' => 'Maintenance',
                'status' => 'replied',
                'messages' => [
                    ['sender_is_admin' => false, 'body' => 'There is a street lamp out near Blk 10 Lot 3.'],
                    ['sender_is_admin' => true, 'body' => 'Thank you for reporting. Our utility team will replace it tonight.'],
                ],
                'days_ago' => 2,
            ],
            [
                'subject' => 'Penalty Payment Options',
                'category' => 'Billing',
                'status' => 'pending',
                'messages' => [
                    ['sender_is_admin' => false, 'body' => 'Where can I pay my overdue penalties? Can I do it through GCash?'],
                ],
                'days_ago' => 0,
            ],
        ];

        // 4. Create Threads and Messages
        foreach ($residents as $index => $resident) {
            // Give each resident 1-2 threads from the scenarios
            $numThreads = rand(1, 2);
            $residentScenarios = array_slice($scenarios, ($index * 2) % count($scenarios), $numThreads);

            foreach ($residentScenarios as $scenario) {
                $lastMsgAt = Carbon::now()->subDays($scenario['days_ago'])->subMinutes(rand(1, 1440));
                
                $thread = MessageThread::create([
                    'resident_id' => $resident->id,
                    'subject' => $scenario['subject'],
                    'category' => $scenario['category'],
                    'status' => $scenario['status'],
                    'last_message_at' => $lastMsgAt,
                ]);

                foreach ($scenario['messages'] as $mIndex => $msgData) {
                    $msgTime = (clone $lastMsgAt)->subMinutes((count($scenario['messages']) - $mIndex) * 30);
                    
                    Message::create([
                        'message_thread_id' => $thread->id,
                        'sender_type' => $msgData['sender_is_admin'] ? User::class : Resident::class,
                        'sender_id' => $msgData['sender_is_admin'] ? $admin->id : $resident->id,
                        'body' => $msgData['body'],
                        'is_read' => ($scenario['status'] !== 'pending' || $msgData['sender_is_admin']),
                        'created_at' => $msgTime,
                        'updated_at' => $msgTime,
                    ]);
                }
            }
        }
    }
}
