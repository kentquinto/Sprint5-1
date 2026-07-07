<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Game;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $games = Game::pluck('id', 'name');

        $users = [
            [
                'name'             => 'tester1',
                'email'            => 'tester1@test.com',
                'role'             => 'organizer',
                'bio'              => 'Competitive Pokémon player and event organizer from the Philippines.',
                'country'          => 'Philippines',
                'favorite_game_id' => $games['Pokémon'] ?? null,
            ],
            [
                'name'             => 'tester2',
                'email'            => 'tester2@test.com',
                'role'             => 'organizer',
                'bio'              => 'Yu-Gi-Oh! enthusiast who has been dueling since the original TV series.',
                'country'          => 'Japan',
                'favorite_game_id' => $games['Yu-Gi-Oh!'] ?? null,
            ],
            [
                'name'             => 'tester3',
                'email'            => 'tester3@test.com',
                'role'             => 'organizer',
                'bio'              => 'Magic: The Gathering grinder. Legacy and Modern are my formats.',
                'country'          => 'United States',
                'favorite_game_id' => $games['Magic: The Gathering'] ?? null,
            ],
            [
                'name'             => 'tester4',
                'email'            => 'tester4@test.com',
                'role'             => 'organizer',
                'bio'              => 'Casual Disney Lorcana collector who loves the art more than the game.',
                'country'          => 'Spain',
                'favorite_game_id' => $games['Disney Lorcana'] ?? null,
            ],
            [
                'name'             => 'tester5',
                'email'            => 'tester5@test.com',
                'role'             => 'organizer',
                'bio'              => 'Dragon Ball Super Card Game tournament judge and content creator.',
                'country'          => 'Italy',
                'favorite_game_id' => $games['Dragon Ball Super Card Game'] ?? null,
            ],
            [
                'name'             => 'tester6',
                'email'            => 'tester6@test.com',
                'role'             => 'player',
                'bio'              => 'One Piece Card Game player — Luffy deck main.',
                'country'          => 'Japan',
                'favorite_game_id' => $games['One Piece'] ?? null,
            ],
            [
                'name'             => 'tester7',
                'email'            => 'tester7@test.com',
                'role'             => 'player',
                'bio'              => 'Flesh and Blood hero — mostly Blitz format at my local game store.',
                'country'          => 'Ireland',
                'favorite_game_id' => $games['Flesh and Blood'] ?? null,
            ],
            [
                'name'             => 'tester8',
                'email'            => 'tester8@test.com',
                'role'             => 'player',
                'bio'              => 'Digimon Card Game regional finalist. BT set collector.',
                'country'          => 'South Korea',
                'favorite_game_id' => $games['Digimon Card Game'] ?? null,
            ],
            [
                'name'             => 'tester9',
                'email'            => 'tester9@test.com',
                'role'             => 'player',
                'bio'              => 'Star Wars: Unlimited player chasing the perfect control deck.',
                'country'          => 'United Kingdom',
                'favorite_game_id' => $games['Star Wars: Unlimited'] ?? null,
            ],
            [
                'name'             => 'tester10',
                'email'            => 'tester10@test.com',
                'role'             => 'player',
                'bio'              => 'Final Fantasy TCG fan — Cloud deck forever.',
                'country'          => 'Germany',
                'favorite_game_id' => $games['Final Fantasy TCG'] ?? null,
            ],
        ];

        foreach ($users as $data) {
            User::create([
                ...$data,
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
