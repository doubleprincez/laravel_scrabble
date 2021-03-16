<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use App\Traits\GameTraits;
use Illuminate\Database\Seeder;

class GameTableSeeder extends Seeder
{
    use GameTraits;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Game::count() < 1) {
            Game::factory()->count(5)->create()->each(function ($game) {
                // create partie
                $user = User::inRandomOrder()->first();
                $this->create_partie($game->id, random_int(1, 4), $user->id);
                // create stock
                $this->create_game_stock($game->id);
            });
        }
    }

}
