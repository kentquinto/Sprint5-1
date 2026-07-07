<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

beforeEach(function () {
    $this->game = Game::create(['name' => 'Pokémon']);

    $this->validEventData = [
        'title'       => 'Test Tournament',
        'description' => 'A test TCG tournament open to all players.',
        'location'    => 'Barcelona',
        'entry_fee'   => 10.00,
        'max_players' => 16,
        'date_time'   => now()->addYear()->toDateTimeString(),
        'game_id'     => $this->game->id,
    ];
});

// ─── REGISTRATION ────────────────────────────────────────────────────────────

it('registers as player by default when no role is provided', function () {
    $response = $this->postJson('/api/register', [
        'name'                  => 'Kent',
        'email'                 => 'kent@test.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('users', ['email' => 'kent@test.com', 'role' => 'player']);
});

it('can register as an organizer', function () {
    $response = $this->postJson('/api/register', [
        'name'                  => 'Kent',
        'email'                 => 'kent@test.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
        'role'                  => 'organizer',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('users', ['email' => 'kent@test.com', 'role' => 'organizer']);
});

it('rejects invalid role on registration', function () {
    $response = $this->postJson('/api/register', [
        'name'                  => 'Kent',
        'email'                 => 'kent@test.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
        'role'                  => 'admin',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['role']);
});

// ─── ROLE IN PROFILE ─────────────────────────────────────────────────────────

it('role is returned in GET /api/me', function () {
    $user  = User::factory()->create(['role' => 'player']);
    $token = $user->createToken('api')->accessToken;

    $response = $this->getJson('/api/me', [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['id', 'name', 'email', 'role'])
             ->assertJson(['role' => 'player']);
});

// ─── PLAYER RESTRICTIONS ─────────────────────────────────────────────────────

it('player cannot create an event', function () {
    $player = User::factory()->create(['role' => 'player']);
    $token  = $player->createToken('api')->accessToken;

    $response = $this->postJson('/api/events', $this->validEventData, [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(403)
             ->assertJson(['message' => 'Only organizers can create events.']);
});

it('player cannot update an event', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $player    = User::factory()->create(['role' => 'player']);
    $token     = $player->createToken('api')->accessToken;

    $event = Event::create([
        ...$this->validEventData,
        'creator_id' => $organizer->id,
        'status'     => 'upcoming',
    ]);

    $response = $this->putJson("/api/events/{$event->id}", $this->validEventData, [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(403);
});

it('player cannot delete an event', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $player    = User::factory()->create(['role' => 'player']);
    $token     = $player->createToken('api')->accessToken;

    $event = Event::create([
        ...$this->validEventData,
        'creator_id' => $organizer->id,
        'status'     => 'upcoming',
    ]);

    $response = $this->deleteJson("/api/events/{$event->id}", [], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(403);
});

// ─── ORGANIZER PERMISSIONS ───────────────────────────────────────────────────

it('organizer can create an event', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $token     = $organizer->createToken('api')->accessToken;

    $response = $this->postJson('/api/events', $this->validEventData, [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['id', 'title', 'status', 'game', 'creator']);
});

it('organizer can update their own event', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $token     = $organizer->createToken('api')->accessToken;

    $event = Event::create([
        ...$this->validEventData,
        'creator_id' => $organizer->id,
        'status'     => 'upcoming',
    ]);

    $response = $this->putJson("/api/events/{$event->id}", [
        ...$this->validEventData,
        'title' => 'Updated Tournament',
    ], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)
             ->assertJson(['title' => 'Updated Tournament']);
});

it('organizer can delete their own event', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $token     = $organizer->createToken('api')->accessToken;

    $event = Event::create([
        ...$this->validEventData,
        'creator_id' => $organizer->id,
        'status'     => 'upcoming',
    ]);

    $response = $this->deleteJson("/api/events/{$event->id}", [], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

it('organizer cannot update another organizers event', function () {
    $organizer1 = User::factory()->create(['role' => 'organizer']);
    $organizer2 = User::factory()->create(['role' => 'organizer']);
    $token      = $organizer2->createToken('api')->accessToken;

    $event = Event::create([
        ...$this->validEventData,
        'creator_id' => $organizer1->id,
        'status'     => 'upcoming',
    ]);

    $response = $this->putJson("/api/events/{$event->id}", $this->validEventData, [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(403);
});
