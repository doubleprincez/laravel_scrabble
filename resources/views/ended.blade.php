@extends('layout')
@section('title',"Game Ended")
@section('content')
    <div class="container-fluid mb-5">
        <div class="row align-content-center">
            <div class="col align-self-center text-center">
                <div id="loading" class="spinner-border text-danger" style="display:none"></div>
                <br>
                <div class="vh-100 mx-auto">
                    <h3>Game Ended</h3>
                    <div>

                        Player Won!
                        <p>Player Stat</p>
                        Player 1
                        Player 2
                        Player 3
                        Player 4
                    </div>
                    <button class="btn btn-success">Start New Game</button>
                    <button class="btn btn-info">Restart this game</button>
                </div>
            </div>
        </div>
    </div>
@endsection