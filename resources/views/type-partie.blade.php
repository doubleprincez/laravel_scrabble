@extends('layout')
@section('title','Type de Partie')
@section('type-partie')
    <div class="container justify-content-center">

        <h2 id="type">Veuillez choisir le type de partie que vous voulez jouer</h2><br><br>


        <div class="container-fluid">
            <div class="row">
                <form class="form-horizontal" action="{{ route('game.select') }}" method="POST" id="nbj">
                    @csrf
                    <fieldset>
                        <legend>Nombre minimum des joueurs</legend>
                        <div class="form-group" id="nbjbox">
                            <div class="col-lg-9">
                                <select name="typePartie" class="form-control" id="typePartie" required>
                                    <option value=2>2</option>
                                    <option value=3>3</option>
                                    <option value=4>4</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-2">
                                <a href="/" class="btn btn-danger btn-raised">Quitter</a>
                                <button type="submit" class="btn btn-default border-info" name="insert">Jouer</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

        <script>

        </script>


    </div>

@endsection