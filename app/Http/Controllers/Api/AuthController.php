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
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user  = User::create($request->only(['name', 'email', 'password']));
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
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

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json(['message' => 'Logged in successfully', 'token' => $token]);
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
}
