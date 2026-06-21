<?php

use App\Models\Game;

it('returns all games', function () {
    Game::create(['name' => 'Pokémon']);
    Game::create(['name' => 'Yu-Gi-Oh!']);

    $response = $this->getJson('/api/games');

    $response->assertStatus(200)
             ->assertJsonCount(2)
             ->assertJsonStructure([['id', 'name']]);
});

it('returns empty array when no games exist', function () {
    $response = $this->getJson('/api/games');

    $response->assertStatus(200)
             ->assertExactJson([]);
});

it('games list is publicly accessible without authentication', function () {
    $response = $this->getJson('/api/games');

    $response->assertStatus(200);
});
