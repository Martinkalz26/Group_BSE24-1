<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowserSessionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function other_browser_sessions_can_be_logged_out()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create another session for the user
        $token = $user->createToken('Test Token')->plainTextToken;

        // Log out other sessions
        $response = $this->postJson('/api/logout-other-sessions');

        $response->assertStatus(200);
        $this->assertCount(1, $user->tokens); // Check that only the current session remains
    }
}
