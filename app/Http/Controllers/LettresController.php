<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\Lettres;
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
            // get if the player has no more playing piece left
            $user_chavolet = json_decode($this->get_user_pieces($game, $user_id));

            $check_chavolet = $this->check_empty_array($user_chavolet);
            if ($check_chavolet == null && $check_chavolet == []) {

// user has used up his chavolet, so we need to update with new
                $user_position = $this->search_user_chavolet($game, $user_id);

                $lettres = $this->generate_new_pieces($game->id, $user_position);
            }

            $valeur = $this->generate_valeur($user_chavolet);

            return view('jeu')->with(compact('game', 'valeur'));

        } else {
            return redirect()->route('game.ended')->with(['Resultat' => 'Game Ended']);
        }

    }


    public function game_ended()
    {
        // return game stat

        return view('ended');
    }


}
