<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * List events.
     *
     * Returns a paginated list of all events (20 per page). Use the query parameters to filter the results.
     *
     * @group Events
     * @unauthenticated
     *
     * @queryParam game integer Filter by game ID (see `/api/games`). Example: 1
     * @queryParam status string Filter by status. Must be one of `upcoming`, `ongoing`, `finished`, `cancelled`. Example: upcoming
     * @queryParam price string Filter by price. Use `free` for free events, `paid` for paid events. Example: free
     * @queryParam date string Filter by date (YYYY-MM-DD). Returns events happening on that day. Example: 2026-12-01
     * @queryParam search string Search by event title (partial match). Example: Pokémon
     * @queryParam location string Filter by location (partial match). Example: Barcelona
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
     *       "creator": { "id": 2, "name": "Player One" }
     *     }
     *   ],
     *   "links": { "first": "...", "last": "...", "prev": null, "next": null },
     *   "meta": { "current_page": 1, "last_page": 1, "per_page": 20, "total": 1 }
     * }
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'game'     => 'sometimes|integer|exists:games,id',
            'status'   => 'sometimes|in:upcoming,ongoing,finished,cancelled',
            'price'    => 'sometimes|in:free,paid',
            'date'     => 'sometimes|date',
            'search'   => 'sometimes|string|max:100',
            'location' => 'sometimes|string|max:100',
        ]);

        $events = Event::with('game', 'creator')
            ->withCount('participants')
            ->when($request->game,     fn($q) => $q->where('game_id', $request->game))
            ->when($request->search,   fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->location, fn($q) => $q->where('location', 'like', "%{$request->location}%"))
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->when($request->price === 'free', fn($q) => $q->where('entry_fee', 0))
            ->when($request->price === 'paid', fn($q) => $q->where('entry_fee', '>', 0))
            ->when($request->date,     fn($q) => $q->whereDate('date_time', $request->date))
            ->latest()
            ->paginate(20);

        return EventResource::collection($events);
    }

    /**
     * Get a single event.
     *
     * Returns the full details of one event, including its game, creator, and participant count.
     *
     * @group Events
     * @unauthenticated
     *
     * @urlParam event_id integer required The event ID. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Pokémon Regional Championship",
     *   "description": "A competitive tournament open to all trainers.",
     *   "location": "Barcelona",
     *   "entry_fee": 10.00,
     *   "max_players": 32,
     *   "date_time": "2026-12-01 14:00:00",
     *   "status": "upcoming",
     *   "participants_count": 12,
     *   "game": { "id": 1, "name": "Pokémon" },
     *   "creator": { "id": 2, "name": "Player One" }
     * }
     * @response 404 scenario="Event not found" { "message": "No query results for model [App\\Models\\Event] 99" }
     */
    public function show(Event $event): EventResource
    {
        return new EventResource($this->loadEvent($event));
    }

    /**
     * Create an event.
     *
     * Creates a new tournament event. The authenticated user becomes the organizer.
     *
     * @group Events
     * @authenticated
     *
     * @bodyParam title string required Event name. Max 45 characters. Example: Pokémon Regional Championship
     * @bodyParam description string required Full description of the event. Max 2000 characters. Example: A competitive tournament open to all trainers.
     * @bodyParam location string Optional venue name or city. Max 45 characters. Example: Barcelona
     * @bodyParam date_time string required Event date and time (must be in the future). Example: 2026-12-01 14:00:00
     * @bodyParam max_players integer required Maximum number of participants (2–100). Example: 32
     * @bodyParam entry_fee number required Entry fee in EUR. Use 0 for free events. Max 999.99. Example: 10.00
     * @bodyParam game_id integer required ID of the game being played (see `/api/games`). Example: 1
     *
     * @response 201 {
     *   "id": 5,
     *   "title": "Pokémon Regional Championship",
     *   "description": "A competitive tournament open to all trainers.",
     *   "location": "Barcelona",
     *   "entry_fee": 10.00,
     *   "max_players": 32,
     *   "date_time": "2026-12-01 14:00:00",
     *   "status": "upcoming",
     *   "participants_count": 0,
     *   "game": { "id": 1, "name": "Pokémon" },
     *   "creator": { "id": 1, "name": "Player One" }
     * }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     * @response 422 scenario="Validation error" {
     *   "message": "The title field is required.",
     *   "errors": { "title": ["The title field is required."] }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate($this->eventRules());

        $event = Event::create([
            ...$request->only(['title', 'description', 'location', 'date_time', 'max_players', 'entry_fee', 'game_id']),
            'creator_id' => $request->user()->id,
            'status'     => 'upcoming',
        ]);

        return (new EventResource($this->loadEvent($event)))->response()->setStatusCode(201);
    }

    /**
     * Update an event.
     *
     * Updates an existing event. Only the organizer (creator) of the event can do this.
     *
     * @group Events
     * @authenticated
     *
     * @urlParam event_id integer required The event ID. Example: 1
     *
     * @bodyParam title string required Event name. Max 45 characters. Example: Pokémon Regional Championship
     * @bodyParam description string required Full description of the event. Max 2000 characters. Example: A competitive tournament open to all trainers.
     * @bodyParam location string Optional venue name or city. Max 45 characters. Example: Barcelona
     * @bodyParam date_time string required Event date and time (must be in the future). Example: 2026-12-01 14:00:00
     * @bodyParam max_players integer required Maximum number of participants (2–100). Example: 32
     * @bodyParam entry_fee number required Entry fee in EUR. Use 0 for free events. Max 999.99. Example: 10.00
     * @bodyParam game_id integer required ID of the game being played (see `/api/games`). Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Pokémon Regional Championship",
     *   "description": "Updated description.",
     *   "location": "Madrid",
     *   "entry_fee": 15.00,
     *   "max_players": 64,
     *   "date_time": "2026-12-01 14:00:00",
     *   "status": "upcoming",
     *   "participants_count": 0,
     *   "game": { "id": 1, "name": "Pokémon" },
     *   "creator": { "id": 1, "name": "Player One" }
     * }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     * @response 403 scenario="Not the organizer" { "message": "This action is unauthorized." }
     * @response 404 scenario="Event not found" { "message": "No query results for model [App\\Models\\Event] 99" }
     */
    public function update(Request $request, Event $event): EventResource
    {
        $this->authorize('update', $event);

        $request->validate($this->eventRules());

        $event->update($request->only(['title', 'description', 'location', 'date_time', 'max_players', 'entry_fee', 'game_id']));

        return new EventResource($this->loadEvent($event));
    }

    /**
     * Delete an event.
     *
     * Permanently deletes an event. Only the organizer (creator) of the event can do this.
     *
     * @group Events
     * @authenticated
     *
     * @urlParam event_id integer required The event ID. Example: 1
     *
     * @response 204 scenario="Deleted successfully" {}
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     * @response 403 scenario="Not the organizer" { "message": "This action is unauthorized." }
     * @response 404 scenario="Event not found" { "message": "No query results for model [App\\Models\\Event] 99" }
     */
    public function destroy(Event $event): Response
    {
        $this->authorize('delete', $event);
        $event->delete();

        return response()->noContent();
    }

    private function eventRules(): array
    {
        return [
            'title'       => 'required|string|max:45',
            'description' => 'required|string|max:2000',
            'location'    => 'nullable|string|max:45',
            'date_time'   => 'required|date|after:now',
            'max_players' => 'required|integer|min:2|max:100',
            'entry_fee'   => 'required|numeric|min:0|max:999.99',
            'game_id'     => 'required|exists:games,id',
        ];
    }

    private function loadEvent(Event $event): Event
    {
        return $event->load('game', 'creator')->loadCount('participants');
    }
}
