<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

beforeEach(function () {
    $this->user  = User::factory()->create(['email' => 'user1@example.com']);
    $this->token = $this->user->createToken('api')->accessToken;
});

// ─── DELETE ACCOUNT ───────────────────────────────────────────────────────────

it('deletes the account successfully', function () {
    $response = $this->deleteJson('/api/me', [
        'password' => 'password',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJson(['message' => 'Account deleted successfully']);

    $this->assertDatabaseMissing('users', ['email' => 'user1@example.com']);
});

it('requires authentication to delete the account', function () {
    $response = $this->deleteJson('/api/me', [
        'password' => 'password',
    ]);

    $response->assertStatus(401);
});

it('rejects a wrong password', function () {
    $response = $this->deleteJson('/api/me', [
        'password' => 'wrong-password',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);

    $this->assertDatabaseHas('users', ['email' => 'user1@example.com']);
});

it('requires the password field', function () {
    $response = $this->deleteJson('/api/me', [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('deletes the user\'s organized events with the account', function () {
    $game  = Game::create(['name' => 'Pokémon']);
    $event = Event::create([
        'title'       => 'My Tournament',
        'description' => 'desc',
        'location'    => 'BCN',
        'entry_fee'   => 5,
        'max_players' => 16,
        'date_time'   => now()->addYear(),
        'status'      => 'upcoming',
        'creator_id'  => $this->user->id,
        'game_id'     => $game->id,
    ]);

    $this->deleteJson('/api/me', [
        'password' => 'password',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ])->assertStatus(200);

    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

it('removes the user\'s participations but keeps the joined events', function () {
    $game      = Game::create(['name' => 'Pokémon']);
    $organizer = User::factory()->organizer()->create();
    $event     = Event::create([
        'title'       => 'Someone Else\'s Tournament',
        'description' => 'desc',
        'location'    => 'BCN',
        'entry_fee'   => 5,
        'max_players' => 16,
        'date_time'   => now()->addYear(),
        'status'      => 'upcoming',
        'creator_id'  => $organizer->id,
        'game_id'     => $game->id,
    ]);
    $event->participants()->attach($this->user->id);

    $this->deleteJson('/api/me', [
        'password' => 'password',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ])->assertStatus(200);

    // The participation row is gone, but the other organizer's event survives
    $this->assertDatabaseMissing('participants', ['user_id' => $this->user->id]);
    $this->assertDatabaseHas('events', ['id' => $event->id]);
});

it('token no longer works after the account is deleted', function () {
    $this->deleteJson('/api/me', [
        'password' => 'password',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ])->assertStatus(200);

    $this->getJson('/api/me', [
        'Authorization' => "Bearer {$this->token}",
    ])->assertStatus(401);
});
