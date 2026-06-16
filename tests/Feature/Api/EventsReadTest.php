<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

beforeEach(function () {
    $this->game = Game::create(['name' => 'Pokémon']);
    $this->user = User::factory()->create();
});

function makeEvent(array $overrides = []): array
{
    return array_merge([
        'title'       => 'Test Event',
        'description' => 'Test description',
        'location'    => 'Barcelona',
        'entry_fee'   => 10.00,
        'max_players' => 16,
        'date_time'   => '2026-12-01 14:00:00',
        'status'      => 'upcoming',
    ], $overrides);
}

it('returns all events', function () {
    Event::create(makeEvent(['title' => 'Event 1', 'creator_id' => $this->user->id, 'game_id' => $this->game->id]));
    Event::create(makeEvent(['title' => 'Event 2', 'creator_id' => $this->user->id, 'game_id' => $this->game->id]));

    $response = $this->getJson('/api/events');

    $response->assertStatus(200)
             ->assertJsonCount(2, 'data')
             ->assertJsonStructure(['data' => [['id', 'title', 'description', 'location', 'entry_fee', 'max_players', 'date_time', 'status', 'game', 'creator']]]);
});

it('returns event details with GET /api/events/{id}', function () {
    $event = Event::create(makeEvent(['creator_id' => $this->user->id, 'game_id' => $this->game->id]));

    $response = $this->getJson("/api/events/{$event->id}");

    $response->assertStatus(200)
             ->assertJsonStructure(['id', 'title', 'description', 'location', 'entry_fee', 'max_players', 'date_time', 'status', 'game', 'creator'])
             ->assertJson(['id' => $event->id, 'title' => $event->title]);
});

it('returns 404 for a non-existent event', function () {
    $response = $this->getJson('/api/events/999');

    $response->assertStatus(404);
});

it('filters events by game', function () {
    $other = Game::create(['name' => 'Yu-Gi-Oh!']);

    Event::create(makeEvent(['creator_id' => $this->user->id, 'game_id' => $this->game->id]));
    Event::create(makeEvent(['creator_id' => $this->user->id, 'game_id' => $other->id]));

    $response = $this->getJson("/api/events?game={$this->game->id}");

    $response->assertStatus(200)->assertJsonCount(1, 'data');
});

it('filters events by status', function () {
    Event::create(makeEvent(['status' => 'upcoming', 'creator_id' => $this->user->id, 'game_id' => $this->game->id]));
    Event::create(makeEvent(['status' => 'finished', 'creator_id' => $this->user->id, 'game_id' => $this->game->id]));

    $response = $this->getJson('/api/events?status=upcoming');

    $response->assertStatus(200)->assertJsonCount(1, 'data');
});

it('filters events by price free', function () {
    Event::create(makeEvent(['entry_fee' => 0,     'creator_id' => $this->user->id, 'game_id' => $this->game->id]));
    Event::create(makeEvent(['entry_fee' => 10.00, 'creator_id' => $this->user->id, 'game_id' => $this->game->id]));

    $response = $this->getJson('/api/events?price=free');

    $response->assertStatus(200)->assertJsonCount(1, 'data');
});

it('filters events by search term in title', function () {
    Event::create(makeEvent(['title' => 'Pokemon Championship', 'creator_id' => $this->user->id, 'game_id' => $this->game->id]));
    Event::create(makeEvent(['title' => 'Friday Night Magic',   'creator_id' => $this->user->id, 'game_id' => $this->game->id]));

    $response = $this->getJson('/api/events?search=Pokemon');

    $response->assertStatus(200)->assertJsonCount(1, 'data');
});

it('filters events by location', function () {
    Event::create(makeEvent(['location' => 'Barcelona', 'creator_id' => $this->user->id, 'game_id' => $this->game->id]));
    Event::create(makeEvent(['location' => 'Madrid',    'creator_id' => $this->user->id, 'game_id' => $this->game->id]));

    $response = $this->getJson('/api/events?location=Barcelona');

    $response->assertStatus(200)->assertJsonCount(1, 'data');
});

it('events list is publicly accessible without authentication', function () {
    $response = $this->getJson('/api/events');

    $response->assertStatus(200);
});
