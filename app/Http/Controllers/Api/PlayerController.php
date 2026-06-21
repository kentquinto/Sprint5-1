<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicProfileResource;
use App\Models\User;

class PlayerController extends Controller
{
    /**
     * Get a player's public profile.
     *
     * Returns a player's public profile including their bio, country, favourite game and event statistics.
     *
     * @group Players
     * @unauthenticated
     *
     * @urlParam user_id integer required The ID of the player. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Player One",
     *   "bio": "Pokémon TCG player since 2010.",
     *   "country": "ES",
     *   "favorite_game": { "id": 1, "name": "Pokémon" },
     *   "organized_events_count": 5,
     *   "joined_events_count": 12
     * }
     * @response 404 scenario="User not found" { "message": "No query results for model [App\\Models\\User] 99" }
     */
    public function show(User $user): PublicProfileResource
    {
        $user->load('favoriteGame')
             ->loadCount(['createdEvents', 'participatingEvents']);

        return new PublicProfileResource($user);
    }
}
