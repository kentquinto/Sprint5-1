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
    public function index(Event $event): AnonymousResourceCollection
    {
        return ParticipantResource::collection($event->participants);
    }

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
