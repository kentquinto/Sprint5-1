<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public routes - visitors
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Protected routes - users
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Events - specific routes before {event} wildcard to avoid conflict
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Participants
    Route::post('events/{event}/join', [ParticipantController::class, 'store'])->name('events.join');
    Route::delete('events/{event}/leave', [ParticipantController::class, 'destroy'])->name('events.leave');
});

// Wildcard show route last so specific routes above are matched first
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Public player profiles
Route::get('/players/{user}', [ProfileController::class, 'show'])->name('profile.show');

require __DIR__.'/auth.php';
