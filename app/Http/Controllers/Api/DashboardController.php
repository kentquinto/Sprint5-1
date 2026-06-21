<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardController extends Controller
{
    /**
     * Your organized events.
     *
     * Returns a paginated list of all events you have created as an organizer (20 per page).
     *
     * @group Dashboard
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Pokémon Regional Championship",
     *       "description": "A competitive tournament open to all trainers.",
     *       "location": "Barcelona",
     *       "entry_fee": 10.00,
     *       "max_players": 32,
     *       "date_time": "2026-12-01 14:00:00",
     *       "status": "upcoming",
     *       "participants_count": 12,
     *       "game": { "id": 1, "name": "Pokémon" },
     *       "creator": { "id": 1, "name": "Player One" }
     *     }
     *   ],
     *   "links": { "first": "...", "last": "...", "prev": null, "next": null },
     *   "meta": { "current_page": 1, "last_page": 1, "per_page": 20, "total": 1 }
     * }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     */
    public function organizedEvents(Request $request): AnonymousResourceCollection
    {
        $events = $request->user()
            ->createdEvents()
            ->with('game', 'creator')
            ->withCount('participants')
            ->latest()
            ->paginate(20);

        return EventResource::collection($events);
    }

    /**
     * Your joined events.
     *
     * Returns a paginated list of all events you have joined as a participant (20 per page).
     *
     * @group Dashboard
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 3,
     *       "title": "Yu-Gi-Oh! Spring Showdown",
     *       "description": "Bring your best deck.",
     *       "location": "Madrid",
     *       "entry_fee": 5.00,
     *       "max_players": 16,
     *       "date_time": "2026-11-15 10:00:00",
     *       "status": "upcoming",
     *       "participants_count": 8,
     *       "game": { "id": 2, "name": "Yu-Gi-Oh!" },
     *       "creator": { "id": 4, "name": "Player Three" }
     *     }
     *   ],
     *   "links": { "first": "...", "last": "...", "prev": null, "next": null },
     *   "meta": { "current_page": 1, "last_page": 1, "per_page": 20, "total": 1 }
     * }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     */
    public function joinedEvents(Request $request): AnonymousResourceCollection
    {
        $events = $request->user()
            ->participatingEvents()
            ->with('game', 'creator')
            ->withCount('participants')
            ->latest()
            ->paginate(20);

        return EventResource::collection($events);
    }
}
