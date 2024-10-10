<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_accounts_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Delete the user account
        $response = $this->deleteJson('/api/delete-account');

        $response->assertStatus(204);
        $this->assertDeleted($user); // Check that the user is deleted
    }
}
