<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $events = Event::with('game', 'creator')
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

    public function show(Event $event): EventResource
    {
        $event->load('game', 'creator');

        return new EventResource($event);
    }
}
