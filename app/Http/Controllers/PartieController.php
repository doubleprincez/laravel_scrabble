<?php

namespace App\Http\Controllers;

use App\Traits\GameTraits;
use Illuminate\Http\Request;


class PartieController extends Controller
{
    use GameTraits;


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {

        $user_id = auth()->id();
        // check if user has any active game 
        $check_previous_game = $this->check_previous_game($user_id);

        if ($check_previous_game !== false) {
            $game = $check_previous_game;
            return redirect()->route('game.wait', compact('game'));
        } else {
            // user does not have any active game so add user to a new game
            // get all active games
            $game = $this->select_any_waiting_game();      // add user to an empty
            if ($game) {
                // if there are active games then add user to one of them
                $position = $this->get_empty_position($game);
                // add current user to game
                $this->add_player_to_game($game, $user_id, $position);
                // update game timer to accommodate new user
                $this->update_game_timer($game, $user_id);
                // if game is active, redirect back to game
                return redirect()->route('game.wait', compact('game'));
            } else {
                //user can create new game
                return view('type-partie');
            }


        }
        // if no active game, then create new one
//
//
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function wait()
    {
        if (request()->has('game')) {
            $game_id = (int)request()->get('game');
            $game = $this->get_game_by_id($game_id);
            if (!$game) {
                return redirect()->route('game.ready');
            }
            $user_id = auth()->id();
            if ($this->get_user_game_position($game, $user_id) == null) {
                // user is not a part of this game but tried to access the game link
                return redirect()->route('game.ready');
            }
        } else {
            return redirect()->route('game.select');
        }

        return view('salle-d-attente', compact('game'));

    }

    /**
     * Store a newly created resource in storage.
     *
     *
     */
    public function store(Request $request)
    {
        $user_id = auth()->id();
        // need to check if user is already in a game so the user does not join
        // another game till the game ends.
        $check_previous_game = $this->check_previous_game($user_id);

        if ($check_previous_game != false) {
            // get load previous game
            $game = $check_previous_game;
            // go to previous game
// user has used up his chavolet, so we need to update with new
            $position = $this->user_chavolet_position($game, $user_id);

            // get if the player has no more playing piece left
            $user_chavolet = $this->get_user_chavolet($game, $user_id, $position);

            return redirect()->route('game.wait', compact('game'));
        }
        $request->validate([
            'typePartie' => 'required',
        ]);
        $type = (int)trim($request->get('typePartie'));

        // here we create the game
        $game = $this->create_game($user_id, $type);

        // move user to the waiting room if the number of players is not complete yet

        return redirect()->route('game.wait', compact('game'))->with(['success' => 'Partie enregistré!']);

    }

    public function quitter()
    {
        $user_id = auth()->id();
        if (request()->has('game')) {
            // get game
            $game = $this->get_game_by_id((int)request()->get('game'));

            // get the position of the player in  game
            $position = $this->get_user_game_position($game, $user_id);
            if ($position != null) {
                $remove_user = 'user_id_' . $position;
                $remove_chavolet = 'user_' . $position . '_chavolet';
                $remove_score = 'user_' . $position . '_score';
                $game->$remove_user = null;
                $game->$remove_chavolet = null;
                $game->$remove_score = null;
                $game->save();
                // reduce other players position from where the user position is to free space
                $this->reduce_players_position($game, $position);
                return redirect()->route('game.ready')->with(['Resultat' => 'Game Quitted']);
            }
            return back()->with(['error' => 'Impossible de trouver le jeu']);

        }
        return back()->with(['error' => 'Aucun jeu sélectionné']);
    }


}
