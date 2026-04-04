<?php

namespace Tests\Feature;

use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementDraftPublishTest extends TestCase
{
    use RefreshDatabase;

    public function test_publishing_a_draft_only_changes_the_status(): void
    {
        $this->withoutMiddleware();

        $announcement = Announcement::create([
            'title' => 'Board Meeting Reminder',
            'content' => 'Bring your questions for the monthly meeting.',
            'category' => 'Meeting',
            'priority' => 'high',
            'date_posted' => now()->subDay(),
            'is_pinned' => true,
            'pin_duration' => 7,
            'pin_expires_at' => now()->addDays(7),
            'status' => 'draft',
            'image' => 'announcements/existing-banner.jpg',
        ]);

        $announcement->refresh();

        $original = $announcement->only([
            'title',
            'content',
            'category',
            'priority',
            'date_posted',
            'is_pinned',
            'pin_duration',
            'pin_expires_at',
            'image',
        ]);

        $response = $this->put(route('admin.announcements.update', $announcement), [
            'status' => 'active',
            'submit_action' => 'publish_draft',
        ]);

        $response->assertRedirect(route('admin.announcements.index'));

        $announcement->refresh();

        $this->assertSame('active', $announcement->status);
        $this->assertSame($original['title'], $announcement->title);
        $this->assertSame($original['content'], $announcement->content);
        $this->assertSame($original['category'], $announcement->category);
        $this->assertSame($original['priority'], $announcement->priority);
        $this->assertSame($original['image'], $announcement->image);
        $this->assertSame($original['is_pinned'], $announcement->is_pinned);
        $this->assertSame($original['pin_duration'], $announcement->pin_duration);
        $this->assertTrue($original['date_posted']->equalTo($announcement->date_posted));
        $this->assertTrue($original['pin_expires_at']->equalTo($announcement->pin_expires_at));
    }
}
    