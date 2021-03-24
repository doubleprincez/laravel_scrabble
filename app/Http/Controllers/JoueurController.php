<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\User;
use App\Traits\GameTraits;
use Illuminate\Http\Request;

class JoueurController extends Controller
{
    use GameTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        dd('joueu index');
        $joueur = Joueur::all()->toArray(); /* toArray methode will convert to array format to stored in $joueur variable */
        return view('index', compact('joueur')); /* this compact function will create  an array from  $joueur which we can access in index file folder  */
    }


    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {

        // validate nick name
        $valid = request()->validate([
            'nick' => 'required|string|max:15|regex:/^[a-zA-Z0-9\s]+$/|unique:users'
        ]);

        $nick = request()->get('nick');
        //print_r($request->input());
        $joueur = User::find(auth()->id());

        $joueur->nick = $nick; // this will be the user nick name
//        $joueur->photo = $request->photo->store('photo');
        $joueur->photo = $this->upload_image('photo');

        $result = $joueur->save();

        if ($result) {
            $msg = ["Resultat" => "Data has been saved "];

        } else {
            $msg = ["Resultat" => "Data has not been saved "];

        }

        return redirect()->route('game.select')->with($msg);

    }
}
