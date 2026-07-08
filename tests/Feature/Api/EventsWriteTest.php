<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

beforeEach(function () {
    $this->game  = Game::create(['name' => 'Pokémon']);
    $this->user  = User::factory()->organizer()->create();
    $this->token = $this->user->createToken('api')->accessToken;

    $this->validData = [
        'title'       => 'Test Tournament',
        'description' => 'A test TCG tournament open to all players.',
        'location'    => 'Barcelona',
        'entry_fee'   => 10.00,
        'max_players' => 16,
        'date_time'   => now()->addYear()->toDateTimeString(),
        'game_id'     => $this->game->id,
    ];
});

// ─── CREATE ──────────────────────────────────────────────────────────────────

it('creates an event successfully', function () {
    $response = $this->postJson('/api/events', $this->validData, [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['id', 'title', 'description', 'location', 'entry_fee', 'max_players', 'date_time', 'status', 'game', 'creator']);

    $this->assertDatabaseHas('events', ['title' => 'Test Tournament', 'creator_id' => $this->user->id]);
});

it('requires authentication to create an event', function () {
    $response = $this->postJson('/api/events', $this->validData);

    $response->assertStatus(401);
});

it('validates required fields on create', function () {
    $response = $this->postJson('/api/events', [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['title', 'description', 'date_time', 'max_players', 'entry_fee', 'game_id']);
});

// ─── UPDATE ──────────────────────────────────────────────────────────────────

it('updates an event successfully', function () {
    $event = Event::create([...$this->validData, 'creator_id' => $this->user->id, 'status' => 'upcoming']);

    $response = $this->putJson("/api/events/{$event->id}", [
        ...$this->validData,
        'title' => 'Updated Tournament',
    ], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJson(['title' => 'Updated Tournament']);

    $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Tournament']);
});

it('requires authentication to update an event', function () {
    $event = Event::create([...$this->validData, 'creator_id' => $this->user->id, 'status' => 'upcoming']);

    $response = $this->putJson("/api/events/{$event->id}", $this->validData);

    $response->assertStatus(401);
});

it('returns 403 when non-creator tries to update', function () {
    $event = Event::create([...$this->validData, 'creator_id' => $this->user->id, 'status' => 'upcoming']);

    $other      = User::factory()->create();
    $otherToken = $other->createToken('api')->accessToken;

    $response = $this->putJson("/api/events/{$event->id}", $this->validData, [
        'Authorization' => "Bearer {$otherToken}",
    ]);

    $response->assertStatus(403);
});

// ─── DELETE ──────────────────────────────────────────────────────────────────

it('deletes an event successfully', function () {
    $event = Event::create([...$this->validData, 'creator_id' => $this->user->id, 'status' => 'upcoming']);

    $response = $this->deleteJson("/api/events/{$event->id}", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

it('requires authentication to delete an event', function () {
    $event = Event::create([...$this->validData, 'creator_id' => $this->user->id, 'status' => 'upcoming']);

    $response = $this->deleteJson("/api/events/{$event->id}");

    $response->assertStatus(401);
});

it('returns 403 when non-creator tries to delete', function () {
    $event = Event::create([...$this->validData, 'creator_id' => $this->user->id, 'status' => 'upcoming']);

    $other      = User::factory()->create();
    $otherToken = $other->createToken('api')->accessToken;

    $response = $this->deleteJson("/api/events/{$event->id}", [], [
        'Authorization' => "Bearer {$otherToken}",
    ]);

    $response->assertStatus(403);
});
