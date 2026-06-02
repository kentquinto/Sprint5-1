<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function store(Event $event)
    {
      $alreadyJoined = Participant::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyJoined) {
            return back()->with('error', 'You already joined this event');
        }
        if ($event->creator_id === Auth::id()) {
            return back()->with('error', 'You cannot join your own event');
        }
        if ($event->participants()->count() >= $event->max_players) {
            return back()->with('error', 'You cannot join, the event is already full');
        }
        if ($event->status === 'cancelled' || $event->status === 'finished') {
            return back()->with('error', 'You cannot join, the event is already finished or cancelled');
        }

        Participant::create([
            'event_id' => $event->id,
            'user_id'  => Auth::id(),
        ]);

        return back()->with('success', 'You joined the event!');
    }

    public function destroy(Event $event)
    {
        Participant::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'You left the event');
    }
}
