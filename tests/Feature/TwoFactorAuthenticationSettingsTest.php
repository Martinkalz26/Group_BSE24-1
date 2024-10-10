<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_enable_two_factor_authentication()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Enable two-factor authentication
        $response = $this->postJson('/api/two-factor-authentication/enable');

        $response->assertStatus(200);
        $this->assertTrue($user->two_factor_enabled);
    }

    /** @test */
    public function user_can_disable_two_factor_authentication()
    {
        $user = User::factory()->create(['two_factor_enabled' => true]);
        $this->actingAs($user);

        // Disable two-factor authentication
        $response = $this->postJson('/api/two-factor-authentication/disable');

        $response->assertStatus(200);
        $this->assertFalse($user->fresh()->two_factor_enabled);
    }
}

