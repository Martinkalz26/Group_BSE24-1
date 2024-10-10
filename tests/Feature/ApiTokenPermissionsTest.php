<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenPermissionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_token_permissions_can_be_updated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an API token
        $token = $user->createToken('Test Token')->plainTextToken;

        // Update the API token permissions
        $response = $this->patchJson('/api/tokens/'.$token, [
            'abilities' => ['read', 'update'],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $token->id,
            'abilities' => json_encode(['read', 'update']),
        ]);
    }
}
