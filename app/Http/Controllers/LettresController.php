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

// user has used up his chavolet, so we need to update with new
            $position = $this->search_user_chavolet($game, $user_id);

            // get if the player has no more playing piece left
            $user_chavolet = $this->get_user_chavolet($game, $user_id, $position);

            $valeur = $this->generate_valeur($user_chavolet);

            return view('jeu')->with(compact('game', 'valeur', 'position'));

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
