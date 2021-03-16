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
        dd('here');
        $joueur = Joueur::all()->toArray(); /* toArray methode will convert to array format to stored in $joueur variable */
        return view('index', compact('joueur')); /* this compact function will create  an array from  $joueurs which we can access in index file folder  */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {

        // validate nick name
        request()->validate([
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

    private function validator()
    {
        return request()->validate([
            'nom' => 'required|max:4|alpha',
            'photo' => 'sometimes|image|max:5000',
            'idJoueur' => 'required|integer'
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Joueur $joueur
     * @return \Illuminate\Http\Response
     */
    public function show(Joueur $joueur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Joueur $joueur
     * @return \Illuminate\Http\Response
     */
    public function edit(Joueur $joueur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Joueur $joueur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Joueur $joueur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Joueur $joueur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Joueur $joueur)
    {

    }
}
