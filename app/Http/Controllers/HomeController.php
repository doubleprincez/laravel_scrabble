<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('acceuil');
    }

    public function ready()
    {
        // check if user already has avatar
        if (auth()->user()->avatar) {
            return redirect()->route('jeu');

        } else {
            // creating random user name
            $nick = 'user'.time();
            // take user to avatar settings page
            return view('acceuil')->with(compact('nick'));
        }
    }
}
