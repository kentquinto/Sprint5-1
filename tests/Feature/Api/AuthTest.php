<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('registers a new user successfully', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'user1',
        'email' => 'user1@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['message', 'token']);

    expect(DB::table('users')->where('email', 'user1@example.com')->exists())->toBeTrue();
});

it('validates email is required on registration', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'user1',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('validates email format on registration', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'user1',
        'email' => 'invalid-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('validates password confirmation on registration', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'user1',
        'email' => 'user1@example.com',
        'password' => 'password',
        'password_confirmation' => 'different',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('login returns token for valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'user1@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'user1@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['message', 'token']);
});

it('login fails with invalid credentials', function () {
    User::factory()->create([
        'email' => 'user1@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'user1@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
             ->assertJson(['message' => 'Invalid credentials']);
});

it('returns current user with GET /api/me', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->accessToken;

    $response = $this->getJson('/api/me', [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['id', 'name', 'email', 'bio', 'country', 'favorite_game_id'])
             ->assertJson(['id' => $user->id, 'email' => $user->email]);
});

it('requires authentication to access GET /api/me', function () {
    $response = $this->getJson('/api/me');

    $response->assertStatus(401);
});

it('updates user profile with PUT /api/me', function () {
    $user = User::factory()->create([
        'name' => 'oldname',
        'bio' => 'oldbio',
    ]);
    $token = $user->createToken('test')->accessToken;

    $response = $this->putJson('/api/me', [
        'name' => 'newname',
        'bio' => 'newbio',
        'country' => 'ES',
    ], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)
             ->assertJson(['name' => 'newname', 'bio' => 'newbio', 'country' => 'ES']);

    expect(DB::table('users')->where(['id' => $user->id, 'name' => 'newname'])->exists())->toBeTrue();
});

it('requires authentication to update profile', function () {
    $response = $this->putJson('/api/me', [
        'name' => 'newname',
    ]);

    $response->assertStatus(401);
});

it('logout revokes token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->accessToken;

    $response = $this->postJson('/api/logout', [], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)
             ->assertJson(['message' => 'Logged out successfully']);

    $response = $this->getJson('/api/me', [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(401);
});
