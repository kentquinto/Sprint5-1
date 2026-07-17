<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
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
    public function update(UpdateProfileRequest $request): UserResource
    {
        $request->user()->update($request->only(['name', 'bio', 'country', 'favorite_game_id']));

        return new UserResource($request->user()->fresh()->load('favoriteGame'));
    }

    /**
     * Update your password.
     *
     * Changes the authenticated user's password. Requires the current password
     * for confirmation. Existing tokens remain valid after the change.
     *
     * @group Profile
     * @authenticated
     *
     * @bodyParam current_password string required Your current password. Example: yourpassword
     * @bodyParam password string required The new password. Min 8 characters, must be different from the current one. Example: new-password-123
     * @bodyParam password_confirmation string required Must match `password`. Example: new-password-123
     *
     * @response 200 { "message": "Password updated successfully" }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     * @response 422 scenario="Wrong current password" {
     *   "message": "The password is incorrect.",
     *   "errors": { "current_password": ["The password is incorrect."] }
     * }
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $request->user()->update(['password' => $request->password]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * Delete your account.
     *
     * Permanently deletes the authenticated user's account. Requires the current
     * password for confirmation. Events you organized are deleted with the
     * account; your participations in other events are removed.
     *
     * @group Profile
     * @authenticated
     *
     * @bodyParam password string required Your current password. Example: yourpassword
     *
     * @response 200 { "message": "Account deleted successfully" }
     * @response 401 scenario="Unauthenticated" { "message": "Unauthenticated." }
     * @response 422 scenario="Wrong password" {
     *   "message": "The password is incorrect.",
     *   "errors": { "password": ["The password is incorrect."] }
     * }
     */
    public function deleteAccount(DeleteAccountRequest $request): JsonResponse
    {
        $user = $request->user();

        // Passport's oauth tables have no FK cascade to users — clean up
        // the tokens explicitly so no orphaned rows are left behind.
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}
