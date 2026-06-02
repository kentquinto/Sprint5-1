<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'createdEvents'       => $user->createdEvents()->with('game')->latest()->get(),
            'participatingEvents' => $user->participatingEvents()->with('game')->latest()->get(),
        ]);
    }
}
