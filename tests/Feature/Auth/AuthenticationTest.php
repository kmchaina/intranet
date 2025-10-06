<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Headquarters;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create headquarters for user creation
        Headquarters::create([
            'name' => 'NIMR Headquarters',
            'code' => 'HQ',
            'is_active' => true,
        ]);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'test@nimr.or.tz',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@nimr.or.tz',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@nimr.or.tz',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => 'test@nimr.or.tz',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_login_rate_limiting_works(): void
    {
        $user = User::factory()->create([
            'email' => 'test@nimr.or.tz',
            'password' => bcrypt('password'),
        ]);

        // Make 6 failed login attempts (limit is 5 per minute)
        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', [
                'email' => 'test@nimr.or.tz',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@nimr.or.tz',
            'password' => 'password',
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    public function test_authenticated_users_cannot_access_login_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/dashboard');
    }

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
