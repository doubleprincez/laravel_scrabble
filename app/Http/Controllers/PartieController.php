<?php

namespace App\Http\Controllers;

use App\Models\Partie;
use Illuminate\Http\Request;


class PartieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $partie = Partie::all()->toArray(); 
        return view('index',compact('partie')); 
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'typePartie'=>'required',
        ]);

        $partie = new Partie([
            'idPartie' => $request->get('idPartie'),
            'idJoueur2' => $request->get('idJoueur2'),
            'idJoueur3' => $request->get('idJoueur3'),
            'idJoueur4' => $request->get('idJoueur4'),
            'typePartie' => $request->get('typePartie'),
            'grille' => $request->get('grille'),
            'dateCreation' => $request->get('dateCreation'),
            'dateDebutPartie' => $request->get('dateDebutPartie'),
            'dateFin' => $request->get('dateFin'),
            'statutPartie' => $request->get('statutPartie')
        ]);
        $partie->save();
        return redirect('/type-partie')->with('success', 'Partie saved!');

    }   

private function validator()
{

}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partie  $partie
     * @return \Illuminate\Http\Response
     */
    public function show(Partie $partie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partie  $partie
     * @return \Illuminate\Http\Response
     */
    public function edit(Partie $partie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partie  $partie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partie $partie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partie  $partie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partie $partie)
    {
        //
    }
}
