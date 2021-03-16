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
        $partie = Partie::all()->toArray();
        return view('type-partie', compact('partie'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('type-partie');
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
        dd($check_previous_game);
        if ($check_previous_game != false) {
            // get load previous game
            $game = $check_previous_game->first();
            // go to previous game
            return redirect()->route('jue')->with(compact('game'));
        }
        $request->validate([
            'typePartie' => 'required',
        ]);
        $type = (int)trim($request->get('typePartie'));

        // here we create the game
        $game = $this->create_game($user_id);
        $this->create_partie($game->id, $type, $user_id);
        // first we create the game with the current user as the first user
        // we create the stock for holding the new game reserve values
        // we create the partie for each game to ensure those that want to join
        // can only join if there is empty space left to join.

        // move user to the waiting room if the number of players is not complete yet


//        return redirect('/type-partie')->with('success', 'Partie saved!');

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
