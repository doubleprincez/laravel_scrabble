<?php

namespace App\Http\Controllers;

use App\Traits\GameTraits;

class HomeController extends Controller
{
    use GameTraits;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     */
    public function index()
    {

        if (auth()->user()->photo) {
            return redirect()->route('game.select');
        }else{
            $nick = $this->generate_nick();
            return view('acceuil')->with(compact('nick'));
        }
    }

    public function ready()
    {

        // check if user already has avatar
        if (auth()->user()->photo) {
            return redirect()->route('game.select');

        } else {
         $nick = $this->generate_nick();
            // take user to avatar settings page
            return view('acceuil')->with(compact('nick'));
        }
    }




}
