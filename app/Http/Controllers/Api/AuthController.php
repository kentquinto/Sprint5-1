<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create($request->only(['name', 'email', 'password']));

        return response()->json(['message' => 'User registered successfully', 'token' => $this->issueToken($user)], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['message' => 'Logged in successfully', 'token' => $this->issueToken($user)]);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function update(Request $request): UserResource
    {
        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'bio'     => 'sometimes|nullable|string',
            'country' => 'sometimes|nullable|string|max:10',
        ]);

        $request->user()->update($request->only(['name', 'bio', 'country']));

        return new UserResource($request->user()->fresh());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function organizedEvents(Request $request): AnonymousResourceCollection
    {
        $events = $request->user()
            ->createdEvents()
            ->with('game', 'creator')
            ->withCount('participants')
            ->latest()
            ->get();

        return EventResource::collection($events);
    }

    public function joinedEvents(Request $request): AnonymousResourceCollection
    {
        $events = $request->user()
            ->participatingEvents()
            ->with('game', 'creator')
            ->withCount('participants')
            ->latest()
            ->get();

        return EventResource::collection($events);
    }

    private function issueToken(User $user): string
    {
        return $user->createToken('api')->accessToken;
    }
}
