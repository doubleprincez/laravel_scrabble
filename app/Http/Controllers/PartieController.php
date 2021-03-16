<?php

namespace App\Http\Controllers;

use App\Models\Partie;
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
//        $partie = Partie::all()->toArray();, compact('partie')
        return view('type-partie');
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function wait()
    {

        if (request()->has('game')) {
            $game = request()->get('game');
            if (gettype($game) != "object") {
                $game = $this->get_game_by_id((int)$game);
            }
        } else {
            return redirect()->route('game.wait');
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

            return redirect()->route('game.wait', compact('game'));
        }

        $request->validate([
            'typePartie' => 'required',
        ]);

        $type = (int)trim($request->get('typePartie'));

        // here we create the game
        $game = $this->create_game($user_id, $type);

        // move user to the waiting room if the number of players is not complete yet


        return redirect()->route('game.wait', compact('game'))->with(['success' => 'Partie saved!']);

    }

    private function validator()
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Partie $partie
     * @return \Illuminate\Http\Response
     */
    public function show(Partie $partie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Partie $partie
     * @return \Illuminate\Http\Response
     */
    public function edit(Partie $partie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Partie $partie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partie $partie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Partie $partie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partie $partie)
    {
        //
    }
}
