<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate table to avoid duplicates if re-seeded
        Schema::disableForeignKeyConstraints();
        Announcement::truncate();
        Schema::enableForeignKeyConstraints();

        $announcements = [
            // EVENT Announcements
            [
                'title' => 'Community Clean-Up Drive',
                'content' => 'Join us this Saturday for our monthly clean-up drive. Let’s keep our subdivision clean and green!',
                'category' => 'Event',
                'date_posted' => Carbon::now()->subDays(2),
                'is_pinned' => true,
                'pin_expires_at' => Carbon::now()->addDays(5),
                'status' => 'active',
            ],
            [
                'title' => 'Christmas Party 2026',
                'content' => 'All residents are invited to our annual Christmas Party on December 15 at the clubhouse.',
                'category' => 'Event',
                'date_posted' => Carbon::parse('2026-12-01'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Family Sports Day',
                'content' => 'Bring your family and join our Sports Day on March 20. Fun games and prizes await!',
                'category' => 'Event',
                'date_posted' => Carbon::parse('2026-03-01'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Children’s Summer Workshop',
                'content' => 'Free art and storytelling workshop for kids ages 5–12. Register at the admin office.',
                'category' => 'Event',
                'date_posted' => Carbon::parse('2026-04-15'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Foundation Day Celebration',
                'content' => 'Celebrate our subdivision’s foundation day with food, music, and games on July 10.',
                'category' => 'Event',
                'date_posted' => Carbon::parse('2026-07-01'),
                'is_pinned' => false,
                'status' => 'active',
            ],

            // MAINTENANCE Announcements
            [
                'title' => 'Water Interruption Notice',
                'content' => 'Water supply will be temporarily interrupted on Feb 5 (8AM–12PM) due to pipeline maintenance.',
                'category' => 'Maintenance',
                'date_posted' => Carbon::parse('2026-02-01'),
                'is_pinned' => true,
                'pin_expires_at' => Carbon::parse('2026-02-06'),
                'status' => 'active',
            ],
            [
                'title' => 'Road Repair Schedule',
                'content' => 'Road repairs will start on Block 3 from March 1–5. Please avoid parking in the area.',
                'category' => 'Maintenance',
                'date_posted' => Carbon::parse('2026-02-25'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Street Light Maintenance',
                'content' => 'Street lights will be checked and repaired on April 10. Expect minor power interruptions.',
                'category' => 'Maintenance',
                'date_posted' => Carbon::parse('2026-04-05'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Drainage Cleaning',
                'content' => 'Drainage cleaning will be conducted on May 3. Please clear your frontage.',
                'category' => 'Maintenance',
                'date_posted' => Carbon::parse('2026-04-28'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Gate System Upgrade',
                'content' => 'Our gate barrier system will be upgraded on June 18 to improve security and access control.',
                'category' => 'Maintenance',
                'date_posted' => Carbon::parse('2026-06-10'),
                'is_pinned' => false,
                'status' => 'active',
            ],

            // MEETING Announcements
            [
                'title' => 'General Assembly Meeting',
                'content' => 'The annual General Assembly will be held on January 25 at 3PM in the clubhouse.',
                'category' => 'Meeting',
                'date_posted' => Carbon::parse('2026-01-10'),
                'is_pinned' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Homeowners Board Meeting',
                'content' => 'Board members are requested to attend the meeting on Feb 10 at 6PM.',
                'category' => 'Meeting',
                'date_posted' => Carbon::parse('2026-02-01'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Special Meeting on Security Concerns',
                'content' => 'A special meeting will be conducted on March 8 to discuss recent security issues.',
                'category' => 'Meeting',
                'date_posted' => Carbon::parse('2026-03-01'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Budget Planning Meeting',
                'content' => 'Join us on April 12 to discuss the proposed budget for 2026.',
                'category' => 'Meeting',
                'date_posted' => Carbon::parse('2026-04-01'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Community Feedback Forum',
                'content' => 'Residents are invited to share concerns and suggestions on May 20 at the function hall.',
                'category' => 'Meeting',
                'date_posted' => Carbon::parse('2026-05-10'),
                'is_pinned' => false,
                'status' => 'active',
            ],

            // SECURITY Announcements
            [
                'title' => 'ID Requirement at Gate',
                'content' => 'All visitors must present a valid ID at the gate starting February 1.',
                'category' => 'Security',
                'date_posted' => Carbon::parse('2026-01-20'),
                'is_pinned' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Curfew Reminder',
                'content' => 'Please observe the community curfew from 11PM to 4AM.',
                'category' => 'Security',
                'date_posted' => Carbon::now()->subMonths(1),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Suspicious Activity Alert',
                'content' => 'Report any suspicious persons to the security office immediately.',
                'category' => 'Security',
                'date_posted' => Carbon::now()->subDays(5),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'CCTV Installation',
                'content' => 'New CCTV cameras will be installed in Block 2 and Block 4 for added security.',
                'category' => 'Security',
                'date_posted' => Carbon::now()->subDays(10),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Vehicle Sticker Implementation',
                'content' => 'All vehicles must display subdivision stickers starting March 1.',
                'category' => 'Security',
                'date_posted' => Carbon::parse('2026-02-15'),
                'is_pinned' => false,
                'status' => 'active',
            ],

            // FINANCE Announcements
            [
                'title' => 'HOA Dues Collection Reminder',
                'content' => 'Please settle your HOA dues on or before the 10th of each month to avoid penalties.',
                'category' => 'Finance',
                'date_posted' => Carbon::now()->startOfMonth(),
                'is_pinned' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Special Assessment Notice – Road Repair Fund',
                'content' => 'A one-time road repair fund of ₱2,000 will be collected starting March 15.',
                'category' => 'Finance',
                'date_posted' => Carbon::parse('2026-03-01'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Financial Report Release',
                'content' => 'The 1st Quarter financial report is now available at the admin office.',
                'category' => 'Finance',
                'date_posted' => Carbon::parse('2026-04-05'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Penalty Charges Implementation',
                'content' => 'Late payments will incur a ₱50 penalty starting February.',
                'category' => 'Finance',
                'date_posted' => Carbon::parse('2026-01-15'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Community Event Fund Collection',
                'content' => 'Collection for the Community Event Fund will begin on April 1.',
                'category' => 'Finance',
                'date_posted' => Carbon::parse('2026-03-20'),
                'is_pinned' => false,
                'status' => 'active',
            ],

            // EMERGENCY Announcements
            [
                'title' => 'Power Outage Alert',
                'content' => 'There will be a scheduled power interruption on Feb 7 from 9AM–3PM.',
                'category' => 'Emergency',
                'date_posted' => Carbon::parse('2026-02-05'),
                'is_pinned' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Fire Drill Notice',
                'content' => 'A fire drill will be conducted on March 18 at 10AM. Please cooperate.',
                'category' => 'Emergency',
                'date_posted' => Carbon::parse('2026-03-10'),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Flood Warning',
                'content' => 'Due to heavy rain, please be alert for possible flooding in low-lying areas.',
                'category' => 'Emergency',
                'date_posted' => Carbon::now()->subHours(12),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Earthquake Safety Reminder',
                'content' => 'In case of an earthquake, remember to Drop, Cover, and Hold.',
                'category' => 'Emergency',
                'date_posted' => Carbon::now()->subMonths(2),
                'is_pinned' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Emergency Hotline Update',
                'content' => 'Please save the new emergency hotline numbers posted on the bulletin board.',
                'category' => 'Emergency',
                'date_posted' => Carbon::now()->subWeeks(1),
                'is_pinned' => false,
                'status' => 'active',
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
