<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            'Yu-Gi-Oh!',
            'Pokémon',
            'Magic: The Gathering',
            'One Piece',
            'League of Legends Riftbound',
            'Disney Lorcana',
            'Dragon Ball Super Card Game',
            'Star Wars: Unlimited',
            'Final Fantasy TCG',
            'Flesh and Blood',
            'Digimon Card Game',
            'Gundam Card Game',
            'Altered'
        ];

        foreach ($games as $game) {
            Game::create(['name' => $game]);
        }
        //
    }
}
