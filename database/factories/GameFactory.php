<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use App\Traits\GameTraits;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    use GameTraits;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id_1' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'game_status' => true
        ];
    }
}
