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
            $msg = ["success" => "Les données ont été enregistrées "];

        } else {
            $msg = ["error" => "Les données n'ont pas été enregistrées "];

        }

        return redirect()->route('game.select')->with($msg);

    }
}
