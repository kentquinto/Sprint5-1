<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    /**
     * Top players leaderboard.
     *
     * Returns the top 10 players ranked by number of events joined. Only players who have joined at least one event are included.
     *
     * @group Statistics
     * @unauthenticated
     *
     * @response 200 [
     *   { "id": 3, "name": "Player One", "joined_events_count": 15 },
     *   { "id": 1, "name": "Player One", "joined_events_count": 12 }
     * ]
     */
    public function players(): JsonResponse
    {
        $players = User::withCount('participatingEvents')
            ->whereHas('participatingEvents')
            ->orderByDesc('participating_events_count')
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'id'                  => $u->id,
                'name'                => $u->name,
                'joined_events_count' => $u->participating_events_count,
            ]);

        return response()->json($players);
    }

    /**
     * Games leaderboard.
     *
     * Returns all supported games ranked by how many events have been created for them.
     *
     * @group Statistics
     * @unauthenticated
     *
     * @response 200 [
     *   { "id": 1, "name": "Pokémon",             "events_count": 8 },
     *   { "id": 2, "name": "Yu-Gi-Oh!",           "events_count": 5 },
     *   { "id": 8, "name": "Magic: The Gathering", "events_count": 3 }
     * ]
     */
    public function games(): JsonResponse
    {
        $games = Game::withCount('events')
            ->orderByDesc('events_count')
            ->get()
            ->map(fn($g) => [
                'id'           => $g->id,
                'name'         => $g->name,
                'events_count' => $g->events_count,
            ]);

        return response()->json($games);
    }

    /**
     * Top organizers leaderboard.
     *
     * Returns the top 10 organizers ranked by number of events created. Only organizers who have created at least one event are included.
     *
     * @group Statistics
     * @unauthenticated
     *
     * @response 200 [
     *   { "id": 1, "name": "Player One", "organized_events_count": 5 },
     *   { "id": 4, "name": "Player Three", "organized_events_count": 3 }
     * ]
     */
    public function organizers(): JsonResponse
    {
        $organizers = User::withCount('createdEvents')
            ->whereHas('createdEvents')
            ->orderByDesc('created_events_count')
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'id'                     => $u->id,
                'name'                   => $u->name,
                'organized_events_count' => $u->created_events_count,
            ]);

        return response()->json($organizers);
    }
}
