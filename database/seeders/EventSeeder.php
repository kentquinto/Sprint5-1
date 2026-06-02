<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Game;
use App\Models\User;
use App\Models\Participant;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $user1  = User::where('email', 'tester1@test.com')->first();
        $user2  = User::where('email', 'tester2@test.com')->first();
        $user3  = User::where('email', 'tester3@test.com')->first();
        $user4  = User::where('email', 'tester4@test.com')->first();
        $user5  = User::where('email', 'tester5@test.com')->first();
        $user6  = User::where('email', 'tester6@test.com')->first();
        $user7  = User::where('email', 'tester7@test.com')->first();
        $user8  = User::where('email', 'tester8@test.com')->first();
        $user9  = User::where('email', 'tester9@test.com')->first();
        $user10 = User::where('email', 'tester10@test.com')->first();

        $yugioh   = Game::where('name', 'Yu-Gi-Oh!')->first();
        $pokemon  = Game::where('name', 'Pokémon')->first();
        $mtg      = Game::where('name', 'Magic: The Gathering')->first();
        $onepiece = Game::where('name', 'One Piece')->first();
        $dbs      = Game::where('name', 'Dragon Ball Super Card Game')->first();
        $lorcana  = Game::where('name', 'Disney Lorcana')->first();
        $starwars = Game::where('name', 'Star Wars: Unlimited')->first();
        $fftcg    = Game::where('name', 'Final Fantasy TCG')->first();
        $fab      = Game::where('name', 'Flesh and Blood')->first();
        $digimon  = Game::where('name', 'Digimon Card Game')->first();
        $lol      = Game::where('name', 'League of Legends Riftbound')->first();
        $gundam   = Game::where('name', 'Gundam Card Game')->first();
        $altered  = Game::where('name', 'Altered')->first();

        $e1 = Event::create([
            'title'       => 'Saturday Showdown',
            'description' => 'Weekly Yu-Gi-Oh! tournament open to all skill levels. Bring your best deck and compete for store credit prizes. Swiss format, 5 rounds.',
            'location'    => 'Barcelona Game Store',
            'entry_fee'   => 10.00,
            'max_players' => 16,
            'date_time'   => '2026-06-07 14:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user1->id,
            'game_id'     => $yugioh->id,
        ]);

        $e2 = Event::create([
            'title'       => 'Spring Pokémon Cup',
            'description' => 'Seasonal Pokémon TCG tournament. Standard format only. Top 4 players receive booster packs. Registration closes one hour before start.',
            'location'    => 'The Card Vault',
            'entry_fee'   => 8.00,
            'max_players' => 32,
            'date_time'   => '2026-06-14 11:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user2->id,
            'game_id'     => $pokemon->id,
        ]);

        $e3 = Event::create([
            'title'       => 'Friday Night Magic',
            'description' => 'Classic FNM draft event. Packs provided on entry. Casual and competitive players welcome. Prizes for top finishers every round.',
            'location'    => 'Planeswalker Lounge',
            'entry_fee'   => 15.00,
            'max_players' => 12,
            'date_time'   => '2026-05-30 18:30:00',
            'status'      => 'ongoing',
            'creator_id'  => $user1->id,
            'game_id'     => $mtg->id,
        ]);

        $e4 = Event::create([
            'title'       => 'Grand Line Open',
            'description' => 'One Piece Card Game open tournament. All sets allowed. Single elimination bracket. Bring a printed decklist. Free entry this week.',
            'location'    => 'Straw Hat Games',
            'entry_fee'   => 0.00,
            'max_players' => 24,
            'date_time'   => '2026-06-21 13:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user3->id,
            'game_id'     => $onepiece->id,
        ]);

        $e5 = Event::create([
            'title'       => 'Dragon Ball Super Regional',
            'description' => 'Regional qualifier for the national championship. Top 2 earn an invitation. Official DBS rules apply. Bring two copies of your decklist.',
            'location'    => 'Arena Events Hall',
            'entry_fee'   => 20.00,
            'max_players' => 64,
            'date_time'   => '2026-05-10 10:00:00',
            'status'      => 'finished',
            'creator_id'  => $user2->id,
            'game_id'     => $dbs->id,
        ]);

        $e6 = Event::create([
            'title'       => 'Lorcana Glittering Open',
            'description' => 'Disney Lorcana casual open. All ink colours welcome. Great for new players. Promo cards given to all participants just for showing up.',
            'location'    => 'Inkwell Game Club',
            'entry_fee'   => 5.00,
            'max_players' => 20,
            'date_time'   => '2026-06-28 15:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user3->id,
            'game_id'     => $lorcana->id,
        ]);

        $e7 = Event::create([
            'title'       => 'Galactic Clash',
            'description' => 'Star Wars: Unlimited premiere event. Both Spark of Rebellion and Shadows of the Galaxy sets legal. Top 8 playoff after Swiss rounds.',
            'location'    => 'Rebel Base Cards',
            'entry_fee'   => 12.00,
            'max_players' => 32,
            'date_time'   => '2026-07-05 12:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user1->id,
            'game_id'     => $starwars->id,
        ]);

        $e8 = Event::create([
            'title'       => 'Crystal Chronicles Cup',
            'description' => 'Final Fantasy TCG monthly cup. All opus sets allowed. Deck registration required. Prizes include exclusive promo cards for top finishers.',
            'location'    => 'Midgar Card Shop',
            'entry_fee'   => 10.00,
            'max_players' => 16,
            'date_time'   => '2026-06-20 13:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user2->id,
            'game_id'     => $fftcg->id,
        ]);

        Event::create([
            'title'       => 'Flesh and Blood Skirmish',
            'description' => 'Classic constructed skirmish event. All heroes allowed. Blitz format for faster games. Great entry point for new players to the game.',
            'location'    => 'Legend Story Studios Hub',
            'entry_fee'   => 0.00,
            'max_players' => 20,
            'date_time'   => '2026-05-25 14:00:00',
            'status'      => 'cancelled',
            'creator_id'  => $user3->id,
            'game_id'     => $fab->id,
        ]);

        $e10 = Event::create([
            'title'       => 'Digimon Battle Terminal',
            'description' => 'Digimon Card Game constructed event. All BT sets legal. Single elimination after round robin. Bring your partner Digimon and prove your worth.',
            'location'    => 'Digital World Arena',
            'entry_fee'   => 7.00,
            'max_players' => 24,
            'date_time'   => '2026-07-12 11:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user1->id,
            'game_id'     => $digimon->id,
        ]);

        $e11 = Event::create([
            'title'       => 'Riftbound Ranked Series',
            'description' => 'League of Legends Riftbound competitive series. First official ranked event in the region. Registration is limited so sign up early.',
            'location'    => 'Summoner\'s Rift Game Bar',
            'entry_fee'   => 15.00,
            'max_players' => 32,
            'date_time'   => '2026-07-19 10:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user2->id,
            'game_id'     => $lol->id,
        ]);

        $e12 = Event::create([
            'title'       => 'Iron Warrior Clash',
            'description' => 'Gundam Card Game debut tournament. Pilot your mobile suit to victory. All units legal. Casual atmosphere, good for players trying the game for the first time.',
            'location'    => 'Zeon Game Center',
            'entry_fee'   => 5.00,
            'max_players' => 16,
            'date_time'   => '2026-06-15 15:00:00',
            'status'      => 'upcoming',
            'creator_id'  => $user3->id,
            'game_id'     => $gundam->id,
        ]);

        $e13 = Event::create([
            'title'       => 'Altered Origins Invitational',
            'description' => 'Altered TCG invitational for top-rated players. Unique card system means no two decks are alike. Qualification required to enter this event.',
            'location'    => 'Altered Realms Studio',
            'entry_fee'   => 25.00,
            'max_players' => 16,
            'date_time'   => '2026-05-18 10:00:00',
            'status'      => 'finished',
            'creator_id'  => $user1->id,
            'game_id'     => $altered->id,
        ]);

        // Participants (creator of each event cannot be a participant of their own event)
        // e1 — creator: user1
        Participant::create(['event_id' => $e1->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e1->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e1->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e1->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e1->id, 'user_id' => $user6->id]);

        // e2 — creator: user2
        Participant::create(['event_id' => $e2->id, 'user_id' => $user1->id]);
        Participant::create(['event_id' => $e2->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e2->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e2->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e2->id, 'user_id' => $user6->id]);
        Participant::create(['event_id' => $e2->id, 'user_id' => $user7->id]);

        // e3 — creator: user1
        Participant::create(['event_id' => $e3->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e3->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e3->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e3->id, 'user_id' => $user5->id]);

        // e4 — creator: user3
        Participant::create(['event_id' => $e4->id, 'user_id' => $user1->id]);
        Participant::create(['event_id' => $e4->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e4->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e4->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e4->id, 'user_id' => $user6->id]);
        Participant::create(['event_id' => $e4->id, 'user_id' => $user7->id]);

        // e5 — creator: user2, status: finished
        Participant::create(['event_id' => $e5->id, 'user_id' => $user1->id]);
        Participant::create(['event_id' => $e5->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e5->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e5->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e5->id, 'user_id' => $user6->id]);
        Participant::create(['event_id' => $e5->id, 'user_id' => $user7->id]);
        Participant::create(['event_id' => $e5->id, 'user_id' => $user8->id]);

        // e6 — creator: user3
        Participant::create(['event_id' => $e6->id, 'user_id' => $user1->id]);
        Participant::create(['event_id' => $e6->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e6->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e6->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e6->id, 'user_id' => $user6->id]);

        // e7 — creator: user1
        Participant::create(['event_id' => $e7->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e7->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e7->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e7->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e7->id, 'user_id' => $user6->id]);
        Participant::create(['event_id' => $e7->id, 'user_id' => $user7->id]);
        Participant::create(['event_id' => $e7->id, 'user_id' => $user8->id]);

        // e8 — creator: user2
        Participant::create(['event_id' => $e8->id, 'user_id' => $user1->id]);
        Participant::create(['event_id' => $e8->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e8->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e8->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e8->id, 'user_id' => $user6->id]);

        // e10 — creator: user1
        Participant::create(['event_id' => $e10->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e10->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e10->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e10->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e10->id, 'user_id' => $user7->id]);
        Participant::create(['event_id' => $e10->id, 'user_id' => $user8->id]);

        // e11 — creator: user2
        Participant::create(['event_id' => $e11->id, 'user_id' => $user1->id]);
        Participant::create(['event_id' => $e11->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e11->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e11->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e11->id, 'user_id' => $user6->id]);
        Participant::create(['event_id' => $e11->id, 'user_id' => $user7->id]);
        Participant::create(['event_id' => $e11->id, 'user_id' => $user9->id]);
        Participant::create(['event_id' => $e11->id, 'user_id' => $user10->id]);

        // e12 — creator: user3
        Participant::create(['event_id' => $e12->id, 'user_id' => $user1->id]);
        Participant::create(['event_id' => $e12->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e12->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e12->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e12->id, 'user_id' => $user6->id]);

        // e13 — creator: user1, status: finished
        Participant::create(['event_id' => $e13->id, 'user_id' => $user2->id]);
        Participant::create(['event_id' => $e13->id, 'user_id' => $user3->id]);
        Participant::create(['event_id' => $e13->id, 'user_id' => $user4->id]);
        Participant::create(['event_id' => $e13->id, 'user_id' => $user5->id]);
        Participant::create(['event_id' => $e13->id, 'user_id' => $user6->id]);
        Participant::create(['event_id' => $e13->id, 'user_id' => $user7->id]);
        Participant::create(['event_id' => $e13->id, 'user_id' => $user8->id]);
    }
}
