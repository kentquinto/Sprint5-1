<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Support\Facades\Route;

// ─── Public ──────────────────────────────────────────────────────────────────
Route::get('/games',                          [GameController::class,       'index']);
Route::get('/events',                         [EventController::class,      'index']);
Route::get('/events/{event}',                 [EventController::class,      'show']);
Route::get('/stats/players',                  [StatisticsController::class, 'players']);
Route::get('/stats/games',                    [StatisticsController::class, 'games']);
Route::get('/stats/organizers',               [StatisticsController::class, 'organizers']);

// ─── Guest auth ──────────────────────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ─── Protected ───────────────────────────────────────────────────────────────
Route::middleware('auth:api')->group(function () {
    Route::get('/me',                          [ProfileController::class,    'me']);
    Route::put('/me',                          [ProfileController::class,    'update']);
    Route::put('/me/password',                 [ProfileController::class,    'updatePassword']);
    Route::delete('/me',                       [ProfileController::class,    'deleteAccount']);
    Route::post('/logout',                     [AuthController::class,       'logout']);
    Route::get('/me/organized-events',         [DashboardController::class,  'organizedEvents']);
    Route::get('/me/joined-events',            [DashboardController::class,  'joinedEvents']);

    Route::post('/events',                     [EventController::class,      'store']);
    Route::put('/events/{event}',              [EventController::class,      'update']);
    Route::delete('/events/{event}',           [EventController::class,      'destroy']);

    Route::get('/events/{event}/participants',    [ParticipantController::class, 'index']);
    Route::post('/events/{event}/participants',   [ParticipantController::class, 'store']);
    Route::delete('/events/{event}/participants', [ParticipantController::class, 'destroy']);

    Route::get('/players/{user}',                [PlayerController::class,      'show']);
});
