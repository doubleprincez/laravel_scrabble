@extends('layout')

@section('title') Game @endsection
@section('styles')
    <!-- Boite de communication  -->

    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <link rel="stylesheet" type="text/css" href="css/panneau.css"/>
    <link rel="stylesheet" type="text/css" href="css/rack.css"/>
    <link rel="stylesheet" type="text/css" href="css/jeu.css"/>
    <link rel="stylesheet" type="text/css" href="css/boite-communication.css"/>

    <!-- Boite de communication  -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

    <!-- <script src="{{ asset('js/jeu.js') }}" defer></script> -->


    <!-- Styles -->
    <link href="{{ asset('css/plateau.css') }}" rel="stylesheet" type="text/css">


@endsection

@section('content')


    <div class="panneau-inf">
                @include('jeu.panneau')
    </div>

    <div class="rack-div">
                @include('jeu.rack')
    </div>

    <!-- <div class="logo">
	<img src="{{ asset('img/scrabble.png') }}" class="logo" width="90" height="40">
</div> -->

    <div class="reserve">
        <h7><b>Nb lettres dans la reserve:</b>102</h7>
    </div>
    <div class="btcom">
                @include('jeu.boite-communication')

    </div>

    <div class="topright1">1</div>
    <div class="topright2">2</div>
    <div class="topright3">3</div>
    <div class="topright4">4</div>
    <div class="topright5">5</div>
    <div class="topright6">6</div>
    <div class="topright7">7</div>
    <div class="topright8">8</div>
    <div class="topright9">9</div>
    <div class="topright10">10</div>
    <div class="topright11">11</div>
    <div class="topright12">12</div>
    <div class="topright13">13</div>
    <div class="topright14">14</div>
    <div class="topright15">15</div>
    <div class="lefta">A</div>
    <div class="leftb">B</div>
    <div class="leftc">C</div>
    <div class="leftd">D</div>
    <div class="lefte">E</div>
    <div class="leftf">F</div>
    <div class="leftg">G</div>
    <div class="lefth">H</div>
    <div class="lefti">I</div>
    <div class="leftj">J</div>
    <div class="leftk">K</div>
    <div class="leftl">L</div>
    <div class="leftm">M</div>
    <div class="leftn">N</div>
    <div class="lefto">O</div>
    <div class="board_box">
                @include('jeu.plateau')

    </div>

    <!--

        <div style="border-radius:0.6vmin; padding:0.2vmin 1.5vmin; font-size:calc(8px + 1vmin);  background:#00000044; margin-top:4vmin; color:#fff; cursor:pointer;" class="refreshTiles">Rafraîchir les tuiles</div><br>

    </div>
        <div style="border-radius:0.6vmin; padding:0.2vmin 1.5vmin; font-size:calc(8px + 1vmin); background:#7a1204; margin-top:1vmin; color:#fff; cursor:pointer;" class="endTurn">Quitter</div>
    </div>  -->

@endsection
