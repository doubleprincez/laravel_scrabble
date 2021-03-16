<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\Lettre;
use App\Models\Lettres;
use App\Models\Reserve;
use App\Traits\GameTraits;
use Illuminate\Http\Request;

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
            if ($check_chavolet != null && $check_chavolet != []) {
                // user still has chavolet left

            } else {
                // user has used up his chavolet, so we need to update with new
                $user_position = $this->search_user_chavolet($game, $user_id);

                $lettres = $this->generate_new_pieces($game->id, $user_position);
            }

        } else {
           return redirect()->route('game.ended')->with(['Resultat'=>'Game Ended']);
        }


        $chevalet = json_decode(Joueur::where('id', $id)->first()->chevalet);

        $let = Reserve::where('lettre', 'empty')->first();

//  $vide=Joueur::selectRaw('chevalet')->where('chevalet','=','')->get('chevalet')->toArray();
        $valeur = [];
        foreach ($chevalet as $i) {

            if ($i != "" && $i != null) {
                $valeur[] = Lettre::where('lettre', '=', $i)->first(['lettre', 'valeur']);

            } else {
                $valeur[] = null;
            }
        }

        /*if(empty($vide)){
            foreach($chevalet as $i)
            $i->chevalet[12]="" ||$i->chevalet[27]="" || $i->chevalet[42]="" ||$i->chevalet[57] =""|| $i->chevalet[72]="" ||$i->chevalet[87] ="" ||$i->chevalet[102] ="";

        }*/

        return view('jeu.plateau')
            . view('jeu.panneau')
            . view('jeu.rack',
                ['let' => $let, 'valeur' => $valeur])
            . view('jeu.boite-communication');


        /*  $quantite = DB::table('reserve')->where('lettre','=',$collection)->get('quantite');*/
        /* if ($collection ='a'){
             $quantite=reserve::selectRaw('lettre as lettre')->where('lettre','=',$collection)->decrement('quantite');

         } elseif ($collection ='b'){

         }*/

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Lettres $lettres
     * @return \Illuminate\Http\Response
     */
    public function show(Lettre $lettre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Lettres $lettres
     * @return \Illuminate\Http\Response
     */
    public function edit(Lettre $lettre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Lettres $lettres
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lettre $lettre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Lettres $lettres
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lettre $lettre)
    {
        //
    }


}
