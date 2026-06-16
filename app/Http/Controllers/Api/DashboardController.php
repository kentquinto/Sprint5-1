<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardController extends Controller
{
    public function organizedEvents(Request $request): AnonymousResourceCollection
    {
        $events = $request->user()
            ->createdEvents()
            ->with('game', 'creator')
            ->withCount('participants')
            ->latest()
            ->get();

        return EventResource::collection($events);
    }

    public function joinedEvents(Request $request): AnonymousResourceCollection
    {
        $events = $request->user()
            ->participatingEvents()
            ->with('game', 'creator')
            ->withCount('participants')
            ->latest()
            ->get();

        return EventResource::collection($events);
    }
}
