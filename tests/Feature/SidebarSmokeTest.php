<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sidebar_renders_expected_links(): void
    {
        $user = User::factory()->create([
            'role' => 'hq_admin',
            'email_verified_at' => now(),
        ]);

        $adminResponse = $this->actingAs($user)->get('/dashboard?view=admin');

        $adminResponse->assertOk()
            ->assertSee('View Mode')
            ->assertSee('Manage Policies');

        $staffResponse = $this->actingAs($user)->get('/dashboard?view=staff');

        $staffResponse->assertOk()
            ->assertSee('Communication')
            ->assertSee('Document Library');
    }
}




