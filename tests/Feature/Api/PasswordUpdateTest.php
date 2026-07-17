<?php

use App\Models\User;

beforeEach(function () {
    $this->user  = User::factory()->create(['email' => 'user1@example.com']);
    $this->token = $this->user->createToken('api')->accessToken;
});

// ─── UPDATE PASSWORD ──────────────────────────────────────────────────────────

it('updates the password successfully', function () {
    $response = $this->putJson('/api/me/password', [
        'current_password'      => 'password',
        'password'              => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJson(['message' => 'Password updated successfully']);

    // The new password works for logging in
    $this->postJson('/api/login', [
        'email'    => 'user1@example.com',
        'password' => 'new-password-123',
    ])->assertStatus(200);
});

it('old password no longer works after the update', function () {
    $this->putJson('/api/me/password', [
        'current_password'      => 'password',
        'password'              => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ])->assertStatus(200);

    $this->postJson('/api/login', [
        'email'    => 'user1@example.com',
        'password' => 'password',
    ])->assertStatus(401);
});

it('requires authentication to update the password', function () {
    $response = $this->putJson('/api/me/password', [
        'current_password'      => 'password',
        'password'              => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ]);

    $response->assertStatus(401);
});

it('rejects a wrong current password', function () {
    $response = $this->putJson('/api/me/password', [
        'current_password'      => 'wrong-password',
        'password'              => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['current_password']);
});

it('requires the new password to be confirmed', function () {
    $response = $this->putJson('/api/me/password', [
        'current_password' => 'password',
        'password'         => 'new-password-123',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('rejects a new password shorter than 8 characters', function () {
    $response = $this->putJson('/api/me/password', [
        'current_password'      => 'password',
        'password'              => 'short',
        'password_confirmation' => 'short',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('rejects a new password identical to the current one', function () {
    $response = $this->putJson('/api/me/password', [
        'current_password'      => 'password',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});
