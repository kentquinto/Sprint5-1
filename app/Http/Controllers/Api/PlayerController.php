<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicProfileResource;
use App\Models\User;

class PlayerController extends Controller
{
    public function show(User $user): PublicProfileResource
    {
        $user->load('favoriteGame')
             ->loadCount(['createdEvents', 'participatingEvents']);

        return new PublicProfileResource($user);
    }
}
