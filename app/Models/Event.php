<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'entry_fee',
        'max_players',
        'date_time',
        'status',
        'creator_id',
        'game_id',
    ];

    protected function casts(): array
    {
        return [
            'date_time'  => 'datetime',
            'entry_fee'  => 'decimal:2',
        ];
    }

    public function creator () {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function game () {
        return $this->belongsTo(Game::class);
    }

    public function participants () {
        return $this->belongsToMany(User::class, 'participants');
    }
}
