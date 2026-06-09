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
    public function index(Request $request): AnonymousResourceCollection
    {
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
            ->get();

        return EventResource::collection($events);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'       => 'required|string|max:45',
            'description' => 'required|string|max:2000',
            'location'    => 'nullable|string|max:45',
            'date_time'   => 'required|date|after:now',
            'max_players' => 'required|integer|min:2|max:100',
            'entry_fee'   => 'required|numeric|min:0|max:999.99',
            'game_id'     => 'required|exists:games,id',
        ]);

        $event = Event::create([
            ...$request->only(['title', 'description', 'location', 'date_time', 'max_players', 'entry_fee', 'game_id']),
            'creator_id' => $request->user()->id,
            'status'     => 'upcoming',
        ]);

        $event->load('game', 'creator')->loadCount('participants');

        return (new EventResource($event))->response()->setStatusCode(201);
    }

    public function update(Request $request, Event $event): EventResource
    {
        $this->authorize('update', $event);

        $request->validate([
            'title'       => 'required|string|max:45',
            'description' => 'required|string|max:2000',
            'location'    => 'nullable|string|max:45',
            'date_time'   => 'required|date|after:now',
            'max_players' => 'required|integer|min:2|max:100',
            'entry_fee'   => 'required|numeric|min:0|max:999.99',
            'game_id'     => 'required|exists:games,id',
        ]);

        $event->update($request->only(['title', 'description', 'location', 'date_time', 'max_players', 'entry_fee', 'game_id']));
        $event->load('game', 'creator')->loadCount('participants');

        return new EventResource($event);
    }

    public function destroy(Event $event): Response
    {
        $this->authorize('delete', $event);
        $event->delete();

        return response()->noContent();
    }

    public function show(Event $event): EventResource
    {
        $event->load('game', 'creator')->loadCount('participants');

        return new EventResource($event);
    }
}
