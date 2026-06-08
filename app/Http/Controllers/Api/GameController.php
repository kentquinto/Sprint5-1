<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GameController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return GameResource::collection(Game::orderBy('name')->get());
    }
}
