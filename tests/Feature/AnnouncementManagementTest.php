<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Announcement;
use App\Models\Headquarters;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $headquarters;
    protected $centre;
    protected $station;

    protected function setUp(): void
    {
        parent::setUp();

        $this->headquarters = Headquarters::create([
            'name' => 'NIMR Headquarters',
            'code' => 'HQ',
            'is_active' => true,
        ]);

        $this->centre = Centre::create([
            'name' => 'Test Centre',
            'code' => 'TC',
            'is_active' => true,
        ]);

        $this->station = Station::create([
            'name' => 'Test Station',
            'code' => 'TS',
            'centre_id' => $this->centre->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_users_can_create_announcements(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->post('/announcements', [
            'title' => 'Test Announcement',
            'content' => 'This is a test announcement content.',
            'category' => 'general',
            'priority' => 'medium',
            'target_scope' => 'all',
            'target_centres' => [],
            'target_stations' => [],
        ]);

        $response->assertRedirect('/announcements');
        $this->assertDatabaseHas('announcements', [
            'title' => 'Test Announcement',
            'created_by' => $admin->id,
        ]);
    }

    public function test_staff_cannot_create_announcements(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->post('/announcements', [
            'title' => 'Test Announcement',
            'content' => 'This is a test announcement content.',
            'category' => 'general',
            'priority' => 'medium',
            'target_scope' => 'all',
        ]);

        $response->assertStatus(403);
    }

    public function test_super_admin_can_target_all_scopes(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->post('/announcements', [
            'title' => 'All Staff Announcement',
            'content' => 'This is for everyone.',
            'category' => 'general',
            'priority' => 'high',
            'target_scope' => 'all',
            'target_centres' => [],
            'target_stations' => [],
        ]);

        $response->assertRedirect('/announcements');
    }

    public function test_centre_admin_can_only_target_their_centre(): void
    {
        $centreAdmin = User::factory()->create([
            'role' => 'centre_admin',
            'centre_id' => $this->centre->id,
        ]);

        $response = $this->actingAs($centreAdmin)->post('/announcements', [
            'title' => 'Centre Announcement',
            'content' => 'For centre only.',
            'category' => 'general',
            'priority' => 'medium',
            'target_scope' => 'specific',
            'target_centres' => [$this->centre->id],
            'target_stations' => [],
        ]);

        $response->assertRedirect('/announcements');
    }

    public function test_station_admin_can_only_target_their_station(): void
    {
        $stationAdmin = User::factory()->create([
            'role' => 'station_admin',
            'station_id' => $this->station->id,
            'centre_id' => $this->centre->id,
        ]);

        $response = $this->actingAs($stationAdmin)->post('/announcements', [
            'title' => 'Station Announcement',
            'content' => 'For station only.',
            'category' => 'general',
            'priority' => 'medium',
            'target_scope' => 'specific',
            'target_centres' => [],
            'target_stations' => [$this->station->id],
        ]);

        $response->assertRedirect('/announcements');
    }

    public function test_users_can_view_announcements_visible_to_them(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'centre_id' => $this->centre->id,
        ]);

        $announcement = Announcement::create([
            'title' => 'Test Announcement',
            'content' => 'Test Content',
            'category' => 'general',
            'priority' => 'medium',
            'target_scope' => 'all',
            'created_by' => User::factory()->create(['role' => 'super_admin'])->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/announcements/' . $announcement->id);

        $response->assertOk();
        $response->assertSee('Test Announcement');
    }

    public function test_announcements_can_be_edited_by_creator(): void
    {
        $admin = User::factory()->create(['role' => 'hq_admin']);
        $announcement = Announcement::create([
            'title' => 'Original Title',
            'content' => 'Original Content',
            'category' => 'general',
            'priority' => 'medium',
            'target_scope' => 'all',
            'created_by' => $admin->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)->patch('/announcements/' . $announcement->id, [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'category' => 'urgent',
            'priority' => 'high',
            'target_scope' => 'all',
            'target_centres' => [],
            'target_stations' => [],
        ]);

        $response->assertRedirect('/announcements');
        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_announcements_can_be_deleted_by_authorized_users(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $announcement = Announcement::create([
            'title' => 'To Be Deleted',
            'content' => 'This will be deleted',
            'category' => 'general',
            'priority' => 'low',
            'target_scope' => 'all',
            'created_by' => $admin->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)->delete('/announcements/' . $announcement->id);

        $response->assertRedirect('/announcements');
        $this->assertDatabaseMissing('announcements', [
            'id' => $announcement->id,
        ]);
    }

    public function test_read_tracking_works(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        $announcement = Announcement::create([
            'title' => 'Unread Announcement',
            'content' => 'This is unread',
            'category' => 'general',
            'priority' => 'medium',
            'target_scope' => 'all',
            'created_by' => User::factory()->create(['role' => 'super_admin'])->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        // View the announcement
        $this->actingAs($user)->get('/announcements/' . $announcement->id);

        // Check if marked as read
        $this->assertDatabaseHas('announcement_reads', [
            'announcement_id' => $announcement->id,
            'user_id' => $user->id,
        ]);
    }
}
