<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;

// ─── PUBLIC PROFILE ───────────────────────────────────────────────────────────

it('returns a public profile for an existing player', function () {
    $game = Game::create(['name' => 'Pokémon']);
    $user = User::factory()->create([
        'bio'              => 'I love TCGs',
        'country'          => 'ES',
        'favorite_game_id' => $game->id,
    ]);

    $response = $this->getJson("/api/players/{$user->id}");

    $response->assertStatus(200)
             ->assertJsonStructure(['id', 'name', 'bio', 'country', 'favorite_game', 'organized_events_count', 'joined_events_count'])
             ->assertJson([
                 'id'      => $user->id,
                 'name'    => $user->name,
                 'bio'     => 'I love TCGs',
                 'country' => 'ES',
             ]);
});

it('returns 404 for a non-existent player', function () {
    $response = $this->getJson('/api/players/999');

    $response->assertStatus(404);
});

it('public profile is accessible without authentication', function () {
    $user = User::factory()->create();

    $response = $this->getJson("/api/players/{$user->id}");

    $response->assertStatus(200);
});

it('returns correct organized and joined event counts', function () {
    $game      = Game::create(['name' => 'Pokémon']);
    $organizer = User::factory()->create();
    $player    = User::factory()->create();

    Event::create([
        'title' => 'Tournament 1', 'description' => 'desc',
        'location' => 'BCN', 'entry_fee' => 5, 'max_players' => 16,
        'date_time' => now()->addYear(), 'status' => 'upcoming',
        'creator_id' => $organizer->id, 'game_id' => $game->id,
    ])->participants()->attach($player->id);

    Event::create([
        'title' => 'Tournament 2', 'description' => 'desc',
        'location' => 'BCN', 'entry_fee' => 5, 'max_players' => 16,
        'date_time' => now()->addYear(), 'status' => 'upcoming',
        'creator_id' => $organizer->id, 'game_id' => $game->id,
    ]);

    $response = $this->getJson("/api/players/{$organizer->id}");
    $response->assertStatus(200)
             ->assertJson(['organized_events_count' => 2, 'joined_events_count' => 0]);

    $response = $this->getJson("/api/players/{$player->id}");
    $response->assertStatus(200)
             ->assertJson(['organized_events_count' => 0, 'joined_events_count' => 1]);
});

it('returns null favorite_game when user has none set', function () {
    $user = User::factory()->create(['favorite_game_id' => null]);

    $response = $this->getJson("/api/players/{$user->id}");

    $response->assertStatus(200)
             ->assertJson(['favorite_game' => null]);
});
