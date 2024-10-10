<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteApiTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_tokens_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an API token
        $token = $user->createToken('Test Token')->plainTextToken;

        // Delete the API token
        $response = $this->deleteJson('/api/tokens/'.$token);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->id,
        ]);
    }
}
