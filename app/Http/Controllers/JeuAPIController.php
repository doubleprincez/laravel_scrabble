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

            // TODO then send details of current stock for he game as it is reducing
            $stock = $game->stock->remaining();

            return response()->json($update_time+['game'=>$game,'stock'=>$stock]);
        }
    }

    public function message()
    {
        $user_id = auth()->id();
        // message will have the game Id
        if (request()->has('gameId')) {
            $game = $this->get_game_by_id(request()->get('gameId'));
            // trim message i.e remove any spaces before and after message & no html tags allowed
            $message = htmlspecialchars(trim(request()->get('message')));
            // send to the message function that handles chat and playing the pieces
            return $this->message_manager($game, $user_id, $message);
        }
    }
}
