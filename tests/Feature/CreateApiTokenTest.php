<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_tokens_can_be_created()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an API token
        $response = $this->postJson('/api/tokens', [
            'name' => 'New API Token',
            'abilities' => ['read', 'write'],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'New API Token',
            'abilities' => json_encode(['read', 'write']),
        ]);
    }
}
