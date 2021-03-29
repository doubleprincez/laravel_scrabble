<?php

namespace App\Http\Controllers;

use App\Traits\GameTraits;

class JeuAPIController extends Controller
{
    use  GameTraits;

    public function __construct()
    {
        // This will ensure we pick user id because they must be logged in
        $this->middleware('auth');
    }

    public function check_timer()
    {
        // current user id
        $user_id = auth()->id();
        // get game
        if (request()->has('gameId')) {
            $game = $this->get_game_by_id(request()->get('gameId'));
            // check if current user is the one playing now or the time has changed.
            $update_time = $this->update_game_timer($game, $user_id);
            // TODO check for new messages and send along
            $player_messages = $this->get_user_messages($game, $user_id);

            // TODO then send details of current stock for he game as it is reducing
            if ($game->stock) {
                $stock = $game->stock->remaining();
            } else {
                $stock = 0;
            }

            return response()->json($update_time +
                [
                    'game' => $game,
                    'stock' => $stock,
                    'messages' => $player_messages
                ]);
        }
    }

    public function check_new_player()
    {
        // check if new players have been added dynamically to game list
        if (request()->has('gameId')) {
            // return list of game players and their details as well as the game type for checking
            $game = $this->get_game_by_id((int)request()->get('gameId'));

            // get all the players currently available in the game
            $players = $this->get_list_of_players($game);

            $counter = count($players);
            // check if players are complete and game can proceed
            $complete = $counter === (int)$game->partie->typePartie;
            return ['players' => $players, 'count' => $counter, 'complete' => $complete];
        }
    }


}
