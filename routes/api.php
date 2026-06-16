<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::get('/games',        [GameController::class,  'index']);
Route::get('/events',           [EventController::class, 'index']);
Route::get('/events/{event}',   [EventController::class, 'show']);
Route::get('/events/{event}/participants', [ParticipantController::class, 'index']);
Route::get('/players/{user}',             [PlayerController::class,      'show']);
Route::get('/stats/players',              [StatisticsController::class,  'players']);
Route::get('/stats/games',               [StatisticsController::class,  'games']);
Route::get('/stats/organizers',          [StatisticsController::class,  'organizers']);

Route::middleware('auth:api')->group(function () {
    Route::post('/events',                    [EventController::class,     'store']);
    Route::put('/events/{event}',             [EventController::class,     'update']);
    Route::delete('/events/{event}',          [EventController::class,     'destroy']);
    Route::post('/events/{event}/participants',   [ParticipantController::class, 'store']);
    Route::delete('/events/{event}/participants', [ParticipantController::class, 'destroy']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me',                  [AuthController::class, 'me']);
    Route::put('/me',                  [AuthController::class, 'update']);
    Route::post('/logout',             [AuthController::class, 'logout']);
    Route::get('/me/organized-events', [AuthController::class, 'organizedEvents']);
    Route::get('/me/joined-events',    [AuthController::class, 'joinedEvents']);
});
