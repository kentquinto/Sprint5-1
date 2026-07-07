<?php

namespace App\Http\Resources;

use App\Http\Resources\GameResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'role'          => $this->role,
            'bio'           => $this->bio,
            'country'       => $this->country,
            'favorite_game' => GameResource::make($this->whenLoaded('favoriteGame')),
        ];
    }
}
