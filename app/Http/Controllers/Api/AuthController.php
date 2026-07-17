<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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
    public function register(RegisterRequest $request): JsonResponse
    {
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
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['message' => 'Logged in successfully', 'token' => $this->issueToken($user)]);
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
