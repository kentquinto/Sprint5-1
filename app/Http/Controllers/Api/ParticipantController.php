<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParticipantResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ParticipantController extends Controller
{
    /**
     * List participants.
     *
     * Returns all players who have joined a specific event.
     *
     * @group Participants
     * @unauthenticated
     *
     * @urlParam event_id integer required The event ID. Example: 1
     *
     * @response 200 {
     *   "data": [
     *     { "id": 2, "name": "Player One" },
     *     { "id": 3, "name": "Player Two" }
     *   ]
     * }
     * @response 404 scenario="Event not found" { "message": "No query results for model [App\\Models\\Event] 99" }
     */
    public function index(Event $event): AnonymousResourceCollection
    {
        return ParticipantResource::collection($event->participants);
    }

    /**
     * Join an event.
     *
     * Registers the authenticated user as a participant in an event.
     *
     * **Business rules:**
     * - You cannot join your own event.
     * - You cannot join the same event twice.
     * - You cannot join a full event.
     * - You cannot join a finished or cancelled event.
     *
     * @group Participants
     * @authenticated
     *
     * @urlParam event_id integer required The event ID. Example: 1
     *
     * @response 201 { "id": 1, "name": "Player One" }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     * @response 403 scenario="Own event" { "message": "You cannot join your own event." }
     * @response 422 scenario="Already joined" { "message": "You have already joined this event." }
     * @response 422 scenario="Event full" { "message": "This event is at full capacity." }
     * @response 422 scenario="Event closed" { "message": "This event is no longer accepting participants." }
     */
    public function store(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();
        $event->load('participants');

        if (in_array($event->status, ['finished', 'cancelled'])) {
            return response()->json(['message' => 'This event is no longer accepting participants.'], 422);
        }

        if ($event->creator_id === $user->id) {
            return response()->json(['message' => 'You cannot join your own event.'], 403);
        }

        if ($event->participants->contains('id', $user->id)) {
            return response()->json(['message' => 'You have already joined this event.'], 422);
        }

        if ($event->participants->count() >= $event->max_players) {
            return response()->json(['message' => 'This event is at full capacity.'], 422);
        }

        $event->participants()->attach($user->id);

        return response()->json(new ParticipantResource($user), 201);
    }

    /**
     * Leave an event.
     *
     * Removes the authenticated user from the event's participant list.
     *
     * @group Participants
     * @authenticated
     *
     * @urlParam event_id integer required The event ID. Example: 1
     *
     * @response 204 scenario="Left successfully" {}
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     * @response 403 scenario="Not a participant" { "message": "You are not a participant of this event." }
     * @response 404 scenario="Event not found" { "message": "No query results for model [App\\Models\\Event] 99" }
     */
    public function destroy(Request $request, Event $event): JsonResponse|Response
    {
        $user = $request->user();

        if (! $event->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not a participant of this event.'], 403);
        }

        $event->participants()->detach($user->id);

        return response()->noContent();
    }
}
