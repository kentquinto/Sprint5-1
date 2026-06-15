<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'bio'                    => $this->bio,
            'country'                => $this->country,
            'favorite_game'          => $this->favoriteGame
                                            ? ['id' => $this->favoriteGame->id, 'name' => $this->favoriteGame->name]
                                            : null,
            'organized_events_count' => $this->created_events_count,
            'joined_events_count'    => $this->participating_events_count,
        ];
    }
}
