<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'description'        => $this->description,
            'location'           => $this->location,
            'entry_fee'          => $this->entry_fee,
            'max_players'        => $this->max_players,
            'date_time'          => $this->date_time,
            'status'             => $this->status,
            'participants_count' => $this->participants_count,
            'game'               => [
                'id'   => $this->game->id,
                'name' => $this->game->name,
            ],
            'creator'            => [
                'id'   => $this->creator->id,
                'name' => $this->creator->name,
            ],
        ];
    }
}
