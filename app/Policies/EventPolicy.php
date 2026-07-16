<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    public function create(User $user): Response
    {
        return $user->role === 'organizer'
            ? Response::allow()
            : Response::deny('Only organizers can create events.');
    }

    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->creator_id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->creator_id;
    }
}
