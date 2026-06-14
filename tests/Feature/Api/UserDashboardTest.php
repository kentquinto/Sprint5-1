<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

beforeEach(function () {
    $this->user  = User::factory()->create();
    $this->other = User::factory()->create();
    $this->token = $this->user->createToken('api')->accessToken;
    $this->game  = Game::create(['name' => 'Pokémon']);
});

// ─── HELPER ──────────────────────────────────────────────────────────────────

function makeDashboardEvent(User $creator, Game $game, array $overrides = []): Event
{
    return Event::create(array_merge([
        'title'       => 'Test Tournament',
        'description' => 'Test description',
        'location'    => 'Barcelona',
        'entry_fee'   => 10.00,
        'max_players' => 16,
        'date_time'   => now()->addYear()->toDateTimeString(),
        'status'      => 'upcoming',
        'creator_id'  => $creator->id,
        'game_id'     => $game->id,
    ], $overrides));
}

// ─── ORGANIZED EVENTS ────────────────────────────────────────────────────────

it('returns events organized by the authenticated user', function () {
    makeDashboardEvent($this->user, $this->game);
    makeDashboardEvent($this->user, $this->game, ['title' => 'Second Tournament']);

    $response = $this->getJson('/api/me/organized-events', [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJsonCount(2)
             ->assertJsonStructure([['id', 'title', 'status', 'date_time']]);
});

it('requires authentication to view organized events', function () {
    $response = $this->getJson('/api/me/organized-events');

    $response->assertStatus(401);
});

it('organized events only shows the authenticated user\'s own events', function () {
    makeDashboardEvent($this->other, $this->game);

    $response = $this->getJson('/api/me/organized-events', [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJsonCount(0);
});

// ─── JOINED EVENTS ───────────────────────────────────────────────────────────

it('returns events the authenticated user has joined', function () {
    $event1 = makeDashboardEvent($this->other, $this->game);
    $event2 = makeDashboardEvent($this->other, $this->game, ['title' => 'Second Tournament']);
    $event1->participants()->attach($this->user->id);
    $event2->participants()->attach($this->user->id);

    $response = $this->getJson('/api/me/joined-events', [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJsonCount(2)
             ->assertJsonStructure([['id', 'title', 'status', 'date_time']]);
});

it('requires authentication to view joined events', function () {
    $response = $this->getJson('/api/me/joined-events');

    $response->assertStatus(401);
});

it('joined events only shows events the authenticated user joined', function () {
    $event = makeDashboardEvent($this->other, $this->game);
    $event->participants()->attach($this->other->id);

    $response = $this->getJson('/api/me/joined-events', [
        'Authorization' => "Bearer {$this->token}",
    ]);

    $response->assertStatus(200)
             ->assertJsonCount(0);
});
