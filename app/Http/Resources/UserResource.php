<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'email'            => $this->email,
            'bio'              => $this->bio,
            'country'          => $this->country,
            'favorite_game_id' => $this->favorite_game_id,
        ];
    }
}
