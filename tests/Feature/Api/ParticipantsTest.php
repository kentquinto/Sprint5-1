<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

beforeEach(function () {
    $this->game      = Game::create(['name' => 'Pokémon']);
    $this->organizer = User::factory()->organizer()->create();
    $this->player    = User::factory()->create();
    $this->token     = $this->player->createToken('api')->accessToken;

    $this->event = Event::create([
        'title'       => 'Test Tournament',
        'description' => 'Test description',
        'location'    => 'Barcelona',
        'entry_fee'   => 10.00,
        'max_players' => 2,
        'date_time'   => now()->addYear()->toDateTimeString(),
        'status'      => 'upcoming',
        'creator_id'  => $this->organizer->id,
        'game_id'     => $this->game->id,
    ]);
});

// ─── LIST PARTICIPANTS ────────────────────────────────────────────────────────

it('lists participants of an event', function () {
    $this->event->participants()->attach($this->player->id);

    $response = $this->getJson("/api/events/{$this->event->id}/participants", [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJsonCount(1)
             ->assertJsonStructure([['id', 'name']]);
});

it('requires authentication to list participants', function () {
    $response = $this->getJson("/api/events/{$this->event->id}/participants");

    $response->assertStatus(401);
});

// ─── JOIN EVENT ───────────────────────────────────────────────────────────────

it('joins an event successfully', function () {
    $response = $this->postJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('participants', [
        'event_id' => $this->event->id,
        'user_id'  => $this->player->id,
    ]);
});

it('requires authentication to join an event', function () {
    $response = $this->postJson("/api/events/{$this->event->id}/participants");

    $response->assertStatus(401);
});

it('cannot join own event', function () {
    $token = $this->organizer->createToken('api')->accessToken;

    $response = $this->postJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$token}",
    ]);

    $response->assertStatus(403);
});

it('cannot join if already joined', function () {
    $this->event->participants()->attach($this->player->id);

    $response = $this->postJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422);
});

it('cannot join if event is at full capacity', function () {
    // max_players is 2 — fill both spots with other users
    User::factory()->count(2)->create()->each(fn($u) => $this->event->participants()->attach($u->id));

    $response = $this->postJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422);
});

it('cannot join a finished event', function () {
    $this->event->update(['status' => 'finished']);

    $response = $this->postJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422);
});

it('cannot join a cancelled event', function () {
    $this->event->update(['status' => 'cancelled']);

    $response = $this->postJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(422);
});

// ─── LEAVE EVENT ──────────────────────────────────────────────────────────────

it('leaves an event successfully', function () {
    $this->event->participants()->attach($this->player->id);

    $response = $this->deleteJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('participants', [
        'event_id' => $this->event->id,
        'user_id'  => $this->player->id,
    ]);
});

it('requires authentication to leave an event', function () {
    $response = $this->deleteJson("/api/events/{$this->event->id}/participants");

    $response->assertStatus(401);
});

it('cannot leave an event the user never joined', function () {
    $response = $this->deleteJson("/api/events/{$this->event->id}/participants", [], [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(403);
});
