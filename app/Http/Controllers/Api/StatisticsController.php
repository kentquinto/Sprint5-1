<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
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
