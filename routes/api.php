<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games',        [GameController::class,  'index']);
Route::get('/events',           [EventController::class, 'index']);
Route::get('/events/{event}',   [EventController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/events',             [EventController::class, 'store']);
    Route::put('/events/{event}',      [EventController::class, 'update']);
    Route::delete('/events/{event}',   [EventController::class, 'destroy']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me',      [AuthController::class, 'me']);
    Route::put('/me',      [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
