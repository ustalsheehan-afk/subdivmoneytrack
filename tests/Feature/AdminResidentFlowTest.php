<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminResidentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creates_announcement_and_resident_sees_it()
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $resident = Resident::factory()->create();

        // Admin creates announcement
        $this->actingAs($admin, 'admin')->post(route('admin.announcements.store'), [
            'title' => 'Test Announcement',
            'content' => 'Hello residents',
            'category' => 'Event',
            'date_posted' => now()->toDateString(),
        ])->assertStatus(302);

        // Resident views announcements
        $indexResponse = $this->actingAs($resident, 'resident')->get(route('resident.announcements.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Test Announcement');

        $announcementId = \App\Models\Announcement::query()->where('title', 'Test Announcement')->value('id');
        $showResponse = $this->actingAs($resident, 'resident')->get(route('resident.announcements.show', $announcementId));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Hello residents');
    }
}
