<?php

namespace App\Http\Controllers;

use App\Models\Game;
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

        $id = auth()->id();
        // We are about to start the game now
        // first thing we do is check if current user has any previous games
        // we need to check incase he reloads the browser
        $check_new_game = $this->check_new_game($id);
        dd('here');

        // if user has game running then load game state if game has ended, then restart


        // check if the player has no more playing piece left
        $checker = $this->check_user_pieces($id);

        // generate new random pieces for the player and update it in joeur table
        if ($checker == true) {
            $lettres = $this->generate_new_pieces();

            // passing generated letters into user details
            $update = $this->update_user_letters($id, $lettres);
        } else {

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
