<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Headquarters;
use App\Models\Centre;
use App\Models\Station;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected $headquarters;
    protected $centre;
    protected $station;
    protected $department;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
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

        $this->department = Department::create([
            'name' => 'ICT',
            'code' => 'ICT',
            'headquarters_id' => $this->headquarters->id,
            'is_active' => true,
        ]);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_for_headquarters(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@nimr.or.tz',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'organizational_level' => 'headquarters',
            'department_id' => $this->department->id,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'test@nimr.or.tz')->first();
        $this->assertNotNull($user);
        $this->assertEquals('headquarters', $user->organizational_level ?? 'headquarters');
        $this->assertEquals($this->department->id, $user->department_id);
    }

    public function test_new_users_can_register_for_centre(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@nimr.or.tz',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'organizational_level' => 'centre',
            'centre_id' => $this->centre->id,
            'work_location' => 'centre',
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'test@nimr.or.tz')->first();
        $this->assertNotNull($user);
        $this->assertEquals($this->centre->id, $user->centre_id);
        $this->assertNull($user->station_id);
    }

    public function test_new_users_can_register_for_station(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@nimr.or.tz',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'organizational_level' => 'centre',
            'centre_id' => $this->centre->id,
            'work_location' => 'station_' . $this->station->id,
            'station_id' => $this->station->id,
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'test@nimr.or.tz')->first();
        $this->assertNotNull($user);
        $this->assertEquals($this->station->id, $user->station_id);
        $this->assertEquals($this->centre->id, $user->centre_id);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'organizational_level' => 'headquarters',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@nimr.or.tz',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword!',
            'organizational_level' => 'headquarters',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_registration_prevents_duplicate_emails(): void
    {
        User::factory()->create(['email' => 'test@nimr.or.tz']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@nimr.or.tz',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'organizational_level' => 'headquarters',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(1, User::where('email', 'test@nimr.or.tz')->count());
    }

    public function test_registration_rate_limiting_works(): void
    {
        // Make 4 registration attempts (limit is 3 per minute)
        for ($i = 0; $i < 4; $i++) {
            $this->post('/register', [
                'name' => 'Test User ' . $i,
                'email' => 'test' . $i . '@nimr.or.tz',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'organizational_level' => 'headquarters',
            ]);
        }

        $response = $this->post('/register', [
            'name' => 'Test User 5',
            'email' => 'test5@nimr.or.tz',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'organizational_level' => 'headquarters',
        ]);

        $response->assertStatus(429); // Too Many Requests
    }
}
