<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Headquarters;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
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

    public function test_super_admin_can_access_all_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);

        $routes = [
            '/admin/users',
            '/admin/centres',
            '/admin/stations',
            '/admin/settings',
            '/admin/reports',
            '/admin/backup',
            '/admin/logs',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            $this->assertNotEquals(403, $response->status(), "Super admin should access: {$route}");
        }
    }

    public function test_hq_admin_can_access_hq_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'hq_admin',
            'headquarters_id' => $this->headquarters->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/hq/users');
        $this->assertNotEquals(403, $response->status());

        $response = $this->actingAs($user)->get('/admin/centres');
        $this->assertNotEquals(403, $response->status());
    }

    public function test_hq_admin_cannot_access_super_admin_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'hq_admin',
            'headquarters_id' => $this->headquarters->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/settings');
        $this->assertEquals(403, $response->status());

        $response = $this->actingAs($user)->get('/admin/backup');
        $this->assertEquals(403, $response->status());
    }

    public function test_centre_admin_can_access_centre_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'centre_admin',
            'centre_id' => $this->centre->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/centre/users');
        $this->assertNotEquals(403, $response->status());

        $response = $this->actingAs($user)->get('/admin/centre/staff');
        $this->assertNotEquals(403, $response->status());
    }

    public function test_centre_admin_cannot_access_hq_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'centre_admin',
            'centre_id' => $this->centre->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/hq/users');
        $this->assertEquals(403, $response->status());

        $response = $this->actingAs($user)->get('/admin/settings');
        $this->assertEquals(403, $response->status());
    }

    public function test_station_admin_can_access_station_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'station_admin',
            'station_id' => $this->station->id,
            'centre_id' => $this->centre->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/station/users');
        $this->assertNotEquals(403, $response->status());

        $response = $this->actingAs($user)->get('/admin/station/staff');
        $this->assertNotEquals(403, $response->status());
    }

    public function test_station_admin_cannot_access_centre_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'station_admin',
            'station_id' => $this->station->id,
            'centre_id' => $this->centre->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/centre/users');
        $this->assertEquals(403, $response->status());

        $response = $this->actingAs($user)->get('/admin/settings');
        $this->assertEquals(403, $response->status());
    }

    public function test_staff_cannot_access_any_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $routes = [
            '/admin/users',
            '/admin/centres',
            '/admin/settings',
            '/admin/hq/users',
            '/admin/centre/users',
            '/admin/station/users',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            $this->assertEquals(403, $response->status(), "Staff should not access: {$route}");
        }
    }

    public function test_role_helper_methods_work_correctly(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->assertTrue($superAdmin->isSuperAdmin());
        $this->assertTrue($superAdmin->isAdmin());
        $this->assertFalse($superAdmin->isHqAdmin());

        $hqAdmin = User::factory()->create(['role' => 'hq_admin']);
        $this->assertTrue($hqAdmin->isHqAdmin());
        $this->assertTrue($hqAdmin->isAdmin());
        $this->assertFalse($hqAdmin->isSuperAdmin());

        $centreAdmin = User::factory()->create(['role' => 'centre_admin']);
        $this->assertTrue($centreAdmin->isCentreAdmin());
        $this->assertTrue($centreAdmin->isAdmin());
        $this->assertFalse($centreAdmin->isHqAdmin());

        $stationAdmin = User::factory()->create(['role' => 'station_admin']);
        $this->assertTrue($stationAdmin->isStationAdmin());
        $this->assertTrue($stationAdmin->isAdmin());
        $this->assertFalse($stationAdmin->isCentreAdmin());

        $staff = User::factory()->create(['role' => 'staff']);
        $this->assertFalse($staff->isAdmin());
        $this->assertFalse($staff->isSuperAdmin());
        $this->assertFalse($staff->isHqAdmin());
    }

    public function test_users_can_only_access_content_in_their_scope(): void
    {
        $centreAdmin = User::factory()->create([
            'role' => 'centre_admin',
            'centre_id' => $this->centre->id,
        ]);

        $stationAdmin = User::factory()->create([
            'role' => 'station_admin',
            'station_id' => $this->station->id,
            'centre_id' => $this->centre->id,
        ]);

        // Centre admin should see station (in their centre)
        $this->assertTrue($centreAdmin->canAccessStation($this->station));

        // Station admin should only see their own station
        $this->assertTrue($stationAdmin->canAccessStation($this->station));

        $otherStation = Station::create([
            'name' => 'Other Station',
            'code' => 'OS',
            'centre_id' => $this->centre->id,
            'is_active' => true,
        ]);

        $this->assertFalse($stationAdmin->canAccessStation($otherStation));
    }
}
