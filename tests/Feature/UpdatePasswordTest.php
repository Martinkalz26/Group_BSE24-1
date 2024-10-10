<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Http\Livewire\UpdatePasswordForm;
use Livewire\Livewire;

test('password can be updated', function () {
    // Authenticate a user
    $this->actingAs($user = User::factory()->create());

    // Update the user's password
    Livewire::test(UpdatePasswordForm::class)
        ->set('state', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->call('updatePassword');

    // Assert that the new password is correctly hashed and stored
    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('current password must be correct', function () {
    // Authenticate a user
    $this->actingAs($user = User::factory()->create());

    // Attempt to update the password with an incorrect current password
    Livewire::test(UpdatePasswordForm::class)
        ->set('state', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->call('updatePassword')
        ->assertHasErrors(['current_password']); // Assert that an error is triggered

    // Ensure the user's password has not changed
    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('new passwords must match', function () {
    // Authenticate a user
    $this->actingAs($user = User::factory()->create());

    // Attempt to update the password with non-matching new passwords
    Livewire::test(UpdatePasswordForm::class)
        ->set('state', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'wrong-password',
        ])
        ->call('updatePassword')
        ->assertHasErrors(['password']); // Assert that an error is triggered

    // Ensure the user's password has not changed
    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});
