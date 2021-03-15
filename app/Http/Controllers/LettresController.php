<?php

namespace App\Http\Controllers;

use App\Models\Lettres;
use App\Models\Joueur;
use App\Models\Lettre;
use App\Models\Partie;
use App\Models\Reserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class LettresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*     public function getvaleur(){
            $letter = DB::table('lettres')->get('lettre');
             if (($letter ='a') || ($letter = 'e') || ($letter = "i") || ($letter = "o") || ($letter = "u") || ($letter ="l") || ($letter = "n") || ($letter = "r") || ($letter = "s") || ($letter = "t")) {
                $valeur= DB::table('lettres')->where('lettre','=',$letter)->get('valeur');
            }  elseif (($letter = 'd') || ($letter = 'g')) {
                $valeur= DB::table('lettres')->where('lettre','=',$letter)->get('valeur');
            } elseif (($letter = 'b') || ($letter = 'c') || ($letter = 'm') || ($letter = 'p')) {
                $valeur= DB::table('lettres')->where('lettre','=',$letter)->get('valeur');
            } elseif (($letter = 'f') || ($letter = 'h') || ($letter = 'v') || ($letter = 'w') || ($letter = 'y')) {
                $valeur= DB::table('lettres')->where('lettre','=',$letter)->get('valeur');
            } elseif ($letter = 'k') {
                $valeur= DB::table('lettres')->where('lettre','=',$letter)->get('valeur');
            } elseif (($letter == 'j') || ($letter = 'x')) {
                $valeur= DB::table('lettres')->where('lettre','=',$letter)->get('valeur');
            } elseif (($letter = 'q') || ($letter = 'z')) {
                $valeur= DB::table('lettres')->where('lettre','=',$letter)->get('valeur');
            }

          return ['valeur'=>$valeur];
        }
    */

    public function ChoisirLettresAléatoiresDuReserve()
    {
        dd('in lettres here');
        // First check if the player has no more playing piece left
        $id = auth()->id();
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

    private function generate_new_pieces()
    {
        $arr = collect(Reserve::get('lettre')
            ->random(7)->toArray());
        return $arr->flatten(1);
    }


    private function update_user_letters($id, $lettres)
    {
        return Joueur::find($id)->update(['chevalet' => $lettres]);
//    return Joueur::selectRaw('chevalet as chevalet')->where('idJoueur',1)
        // ->update(['chevalet'=>$lettres]);
    }

    private function check_user_pieces($id)
    {

        // use User Id to check if user still has game
        $lettre = Joueur::where('id', $id)->first();

        return !$lettre->chevalet || $lettre->chevalet == [] || empty($lettre->chevalet);
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
