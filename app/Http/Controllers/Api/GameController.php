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
     *     { "id": 3,  "name": "Digimon" },
     *     { "id": 4,  "name": "Dragon Ball Super" },
     *     { "id": 5,  "name": "Flesh and Blood" },
     *     { "id": 6,  "name": "KeyForge" },
     *     { "id": 7,  "name": "Lorcana" },
     *     { "id": 8,  "name": "Magic: The Gathering" },
     *     { "id": 9,  "name": "MetaZoo" },
     *     { "id": 10, "name": "One Piece" },
     *     { "id": 1,  "name": "Pokémon" },
     *     { "id": 11, "name": "Star Wars: Unlimited" },
     *     { "id": 12, "name": "Union Arena" },
     *     { "id": 13, "name": "Vanguard" },
     *     { "id": 2,  "name": "Yu-Gi-Oh!" }
     *   ]
     * }
     */
    public function index(): AnonymousResourceCollection
    {
        return GameResource::collection(Game::orderBy('name')->get());
    }
}
