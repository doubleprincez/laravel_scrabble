<?php

namespace Database\Seeders;

use App\Models\Joueur;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JoueurTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() < 1) {
            // create demo user
            User::create([
                'name' => 'test',
                'email' => 'test@test.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);

            User::create([
                'name' => 'test2',
                'email' => 'test2@test.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'highscore' => 2312,

            ]);
        }
    }
}
