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

    // ─── Query scopes (used by GET /api/events filters) ──────────────────────

    public function scopeForGame($query, $gameId) {
        $query->when($gameId, fn ($q) => $q->where('game_id', $gameId));
    }

    public function scopeSearch($query, $term) {
        $query->when($term, fn ($q) => $q->where('title', 'like', "%{$term}%"));
    }

    public function scopeInLocation($query, $location) {
        $query->when($location, fn ($q) => $q->where('location', 'like', "%{$location}%"));
    }

    public function scopeWithStatus($query, $status) {
        $query->when($status, fn ($q) => $q->where('status', $status));
    }

    public function scopePriced($query, $price) {
        $query->when($price === 'free', fn ($q) => $q->where('entry_fee', 0))
              ->when($price === 'paid', fn ($q) => $q->where('entry_fee', '>', 0));
    }

    public function scopeOnDate($query, $date) {
        $query->when($date, fn ($q) => $q->whereDate('date_time', $date));
    }
}
