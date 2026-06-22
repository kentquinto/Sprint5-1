<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GameController extends Controller
{
    /**
     * List all games.
     *
     * Returns all 13 supported Trading Card Games, sorted alphabetically. Use the `id` when creating or filtering events.
     *
     * @group Games
     * @unauthenticated
     *
     * @response 200 {
     *   "data": [
     *     { "id": 1,  "name": "Yu-Gi-Oh!" },
     *     { "id": 2,  "name": "Pokémon" },
     *     { "id": 3,  "name": "Magic: The Gathering" },
     *     { "id": 4,  "name": "One Piece" },
     *     { "id": 5,  "name": "League of Legends Riftbound" },
     *     { "id": 6,  "name": "Disney Lorcana" },
     *     { "id": 7,  "name": "Dragon Ball Super Card Game" },
     *     { "id": 8,  "name": "Star Wars: Unlimited" },
     *     { "id": 9,  "name": "Final Fantasy TCG" },
     *     { "id": 10, "name": "Flesh and Blood" },
     *     { "id": 11, "name": "Digimon Card Game" },
     *     { "id": 12, "name": "Gundam Card Game" },
     *     { "id": 13, "name": "Altered" }
     *   ]
     * }
     */
    public function index(): AnonymousResourceCollection
    {
        return GameResource::collection(Game::orderBy('name')->get());
    }
}
