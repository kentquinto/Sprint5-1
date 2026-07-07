<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

// ─── HELPER ──────────────────────────────────────────────────────────────────

function makeStatEvent(User $creator, Game $game): Event
{
    return Event::create([
        'title'       => 'Tournament',
        'description' => 'desc',
        'location'    => 'BCN',
        'entry_fee'   => 5,
        'max_players' => 16,
        'date_time'   => now()->addYear(),
        'status'      => 'upcoming',
        'creator_id'  => $creator->id,
        'game_id'     => $game->id,
    ]);
}

// ─── PLAYER STATS ────────────────────────────────────────────────────────────

it('returns player statistics ranked by events joined', function () {
    $game    = Game::create(['name' => 'Pokémon']);
    $player1 = User::factory()->create();
    $player2 = User::factory()->create();
    $organizer = User::factory()->organizer()->create();

    $event1 = makeStatEvent($organizer, $game);
    $event2 = makeStatEvent($organizer, $game);

    $event1->participants()->attach($player1->id);
    $event2->participants()->attach($player1->id);
    $event1->participants()->attach($player2->id);

    $response = $this->getJson('/api/stats/players');

    $response->assertStatus(200)
             ->assertJsonStructure([['id', 'name', 'joined_events_count']])
             ->assertJsonPath('0.id', $player1->id)
             ->assertJsonPath('0.joined_events_count', 2);
});

it('player stats are publicly accessible', function () {
    $response = $this->getJson('/api/stats/players');

    $response->assertStatus(200);
});

// ─── GAME STATS ──────────────────────────────────────────────────────────────

it('returns game statistics ranked by number of events', function () {
    $pokemon = Game::create(['name' => 'Pokémon']);
    $yugioh  = Game::create(['name' => 'Yu-Gi-Oh!']);
    $organizer = User::factory()->organizer()->create();

    makeStatEvent($organizer, $pokemon);
    makeStatEvent($organizer, $pokemon);
    makeStatEvent($organizer, $yugioh);

    $response = $this->getJson('/api/stats/games');

    $response->assertStatus(200)
             ->assertJsonStructure([['id', 'name', 'events_count']])
             ->assertJsonPath('0.name', 'Pokémon')
             ->assertJsonPath('0.events_count', 2);
});

it('game stats are publicly accessible', function () {
    $response = $this->getJson('/api/stats/games');

    $response->assertStatus(200);
});

// ─── ORGANIZER STATS ─────────────────────────────────────────────────────────

it('returns organizer statistics ranked by events created', function () {
    $game = Game::create(['name' => 'Pokémon']);
    $org1 = User::factory()->organizer()->create();
    $org2 = User::factory()->organizer()->create();

    makeStatEvent($org1, $game);
    makeStatEvent($org1, $game);
    makeStatEvent($org2, $game);

    $response = $this->getJson('/api/stats/organizers');

    $response->assertStatus(200)
             ->assertJsonStructure([['id', 'name', 'organized_events_count']])
             ->assertJsonPath('0.id', $org1->id)
             ->assertJsonPath('0.organized_events_count', 2);
});

it('organizer stats are publicly accessible', function () {
    $response = $this->getJson('/api/stats/organizers');

    $response->assertStatus(200);
});
