<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new account.
     *
     * Creates a new player account and returns a Bearer token you can use immediately.
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam name string required Your display name. Min 2, max 255 characters. Example: Player One
     * @bodyParam email string required A valid, unique email address. Example: test@example.com
     * @bodyParam password string required Min 8 characters. Example: yourpassword
     * @bodyParam password_confirmation string required Must match `password`. Example: yourpassword
     * @bodyParam role string Optional. `player` (default) or `organizer`. Players can join events; organizers can also create, edit and delete their own events. Example: organizer
     *
     * @response 201 {
     *   "message": "User registered successfully",
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGci..."
     * }
     * @response 422 scenario="Validation error" {
     *   "message": "The email has already been taken.",
     *   "errors": { "email": ["The email has already been taken."] }
     * }
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|min:2|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role'     => 'sometimes|in:player,organizer',
        ]);

        $user = User::create($request->only(['name', 'email', 'password', 'role']));

        return response()->json(['message' => 'User registered successfully', 'token' => $this->issueToken($user)], 201);
    }

    /**
     * Log in.
     *
     * Returns a Bearer token for an existing account.
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam email string required A valid email address. Example: test@example.com
     * @bodyParam password string required Your account password. Example: yourpassword
     *
     * @response 200 {
     *   "message": "Logged in successfully",
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGci..."
     * }
     * @response 401 scenario="Wrong credentials" { "message": "Invalid credentials" }
     */
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

    /**
     * Get your profile.
     *
     * Returns the authenticated user's own profile, including their favorite game.
     *
     * @group Profile
     * @authenticated
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Player One",
     *   "email": "test@example.com",
     *   "role": "organizer",
     *   "bio": "Pokémon TCG player since 2010.",
     *   "country": "ES",
     *   "favorite_game": { "id": 1, "name": "Pokémon" }
     * }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load('favoriteGame'));
    }

    /**
     * Update your profile.
     *
     * Updates one or more fields on the authenticated user's profile. All fields are optional — only send what you want to change.
     *
     * @group Profile
     * @authenticated
     *
     * @bodyParam name string Your new display name. Example: Player One
     * @bodyParam bio string A short bio shown on your public profile. Example: Pokémon TCG player since 2010.
     * @bodyParam country string A 2–10 character country code or name. Example: ES
     * @bodyParam favorite_game_id integer The ID of your favourite game (must exist in `/api/games`). Pass `null` to clear it. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Player One",
     *   "email": "test@example.com",
     *   "role": "organizer",
     *   "bio": "Pokémon TCG player since 2010.",
     *   "country": "ES",
     *   "favorite_game": { "id": 1, "name": "Pokémon" }
     * }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     */
    public function update(Request $request): UserResource
    {
        $request->validate([
            'name'             => 'sometimes|string|max:255',
            'bio'              => 'sometimes|nullable|string',
            'country'          => 'sometimes|nullable|string|max:10',
            'favorite_game_id' => 'sometimes|nullable|exists:games,id',
        ]);

        $request->user()->update($request->only(['name', 'bio', 'country', 'favorite_game_id']));

        return new UserResource($request->user()->fresh()->load('favoriteGame'));
    }

    /**
     * Log out.
     *
     * Revokes the current Bearer token. The token will no longer work after this call.
     *
     * @group Authentication
     * @authenticated
     *
     * @response 200 { "message": "Logged out successfully" }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logged out successfully']);
    }

    private function issueToken(User $user): string
    {
        return $user->createToken('api')->accessToken;
    }
}
