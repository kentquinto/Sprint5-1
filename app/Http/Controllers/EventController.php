<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $events = Event::with('game', 'creator', 'participants')
            ->when($request->game, fn($q) => $q->where('game_id', $request->game))
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->date, fn($q) => $q->whereDate('date_time', $request->date))
            ->when($request->price === 'free', fn($q) => $q->where('entry_fee', 0))
            ->when($request->price === 'paid', fn($q) => $q->where('entry_fee', '>', 0))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(12)
            ->withQueryString();
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $games = Game::all();
        return view ('events.create', compact ('games'));
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:45',
            'description'   => 'required|string|max:2000',
            'location'      => 'required|string|max:45',
            'entry_fee'     => 'required|numeric|min:0',
            'max_players'   => 'required|integer|min:2',
            'date_time'     => 'required|date|after:now',
            'game_id'       => 'required|exists:games,id',
        ]);
        Event::create([
            ...$request->only(['title', 'description', 'location', 'entry_fee', 'max_players', 'date_time', 'game_id']),
            'creator_id'    => Auth::id(),
            'status'        => 'upcoming',
        ]);
        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }
        //

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('game', 'creator', 'participants');
        $joined = $event->participants->contains('id', auth()->id());
        return view('events.show', compact('event', 'joined'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $games = Game::all();
        return view('events.edit', compact('event', 'games'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'title'       => 'required|string|max:45',
            'description' => 'required|string|max:2000',
            'location'    => 'required|string|max:45',
            'entry_fee'   => 'required|numeric|min:0',
            'max_players' => 'required|integer|min:2',
            'date_time'   => 'required|date',
            'game_id'     => 'required|exists:games,id',
            'status'      => 'required|in:upcoming,ongoing,finished,cancelled',
        ]);

        $event->update($request->only([
            'title', 'description', 'location',
            'entry_fee', 'max_players', 'date_time', 'game_id', 'status'
        ]));

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
        //
    }
}
