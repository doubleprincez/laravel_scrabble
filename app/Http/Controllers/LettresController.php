<?php

namespace App\Http\Controllers;

use App\Traits\GameTraits;

class LettresController extends Controller
{
    use GameTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ChoisirLettresAléatoiresDuReserve()
    {

        $user_id = auth()->id();
        // check if there is game in the request
        if (!request()->has('game')) {
            return redirect()->route('game.select');
        }
        // getting game ID and fetching game
        $game = $this->get_game_by_id((int)request()->get('game'));

        // check if the game has finished or is still running
        if ($this->check_game_finished($game)) {
            $this->update_game_timer($game, $user_id);
// user has used up his chavolet, so we need to update with new
            $position = $this->user_chavolet_position($game, $user_id);

            if ($position === null) {
                // user might have entered a game id but was never part of the game
                return redirect()->route('game.select');
            }
            // get if the player has no more playing piece left
            $user_chavolet = $this->get_user_chavolet($game, $user_id, $position);

            $valeur = $this->generate_valeur($user_chavolet);

            $game = $this->get_game_by_id((int)request()->get('game'));
            return view('jeu')->with(compact('game', 'valeur', 'position'));

        }

        return redirect()->route('game.ended')->with(['Resultat' => 'Game Ended']);

    }


    public function game_ended()
    {
        // return game stat

        return view('ended');
    }


}
