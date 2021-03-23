<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(JoueurTableSeeder::class); // CREATE DEMO USERS FOR TEST PURPOSES
        $this->call(LettreTableSeeder::class); // CREATE ALL LETTERS REQUIRED FOR EACH NEW GAME
        $this->call(ReserveTableSeeder::class); // CREATE RESERVE FOR ALL GAME STOCK
//        $this->call(GameTableSeeder::class); // CREATE DEMO GAMES
    }
}
