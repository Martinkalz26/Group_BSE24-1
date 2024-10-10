<?php

namespace Tests\Feature;

use App\Http\Livewire\UpdateProfileInformationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function current_profile_information_is_available()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test that the current profile information is available
        $component = Livewire::test(UpdateProfileInformationForm::class);

        $component->assertSet('name', $user->name);
        $component->assertSet('email', $user->email);
    }

    /** @test */
    public function profile_information_can_be_updated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = Livewire::test(UpdateProfileInformationForm::class);

        $component->set('name', 'Updated Name')
                  ->set('email', 'updated@example.com')
                  ->call('updateProfileInformation');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }
}
