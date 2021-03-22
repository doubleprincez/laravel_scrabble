@extends('layout')
@section('title',"Salle d'attente")
@section('content')
    <div class="container-fluid mb-5">
        <div class="row align-content-center">
            <div class="col align-self-center text-center">
                <div class="spinner-border text-danger"></div>
                <br>
                <div class="form-group form-group-inline">
                    <h3>Veuillez attendre vos adversaires..</h3>
                    @isset($game)
                        <?php
                        $players_count = 0;
                        if ($game->player_4) {
                            $players_count++;
                        }
                        if ($game->player_3) {
                            $players_count++;
                        }
                        if ($game->player_2) {
                            $players_count++;
                        }
                        if ($game->player_1) {
                            $players_count++;
                        }
                        ?>
                        <p>{{ $players_count }} Joueur(s) restant(s)</p><br>
                    @endisset
                </div>
                <div class="form-group">

                    <div class="col-md-8 offset-md-2 bootstrap snippets bootdeys">
                        <div class="widget-container scrollable list rollodex my-5">
                            <h3 class="heading text-left">
                                <i class="fa fa-comment"></i>Players<i class="fa fa-plus pull-right"></i><i
                                        class="fa fa-search pull-right"></i><i class="fa fa-refresh pull-right"></i>
                            </h3>
                            <div class="widget-content text-left">
                                <ul>
                                    @if($game->player_1)
                                        <li class="flex-item my-3">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img
                                                            src="{{ asset($game->player_1->photo) }}"
                                                            class="" style="width:50px;heigth:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_1->nick }} &nbsp; <i class="fa fa-dot-circle text-success"></i></h4>
                                                </div>
                                            </div>

                                        </li>
                                    @endif
                                    @if($game->player_2)
                                        <li class="flex-item my-3">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img
                                                            src="{{ asset($game->player_2->photo) }}"
                                                            class="" style="width:50px;heigth:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_2->nick }} &nbsp; <i class="fa fa-dot-circle text-success"></i></h4>
                                                </div>
                                            </div>

                                        </li>
                                    @endif
                                    @if($game->player_3)
                                        <li class="flex-item my-3">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img
                                                            src="{{ asset($game->player_3->photo) }}"
                                                            class="" style="width:50px;heigth:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_3->nick }} &nbsp; <i class="fa fa-dot-circle text-success"></i></h4>
                                                </div>
                                            </div>

                                        </li>
                                    @endif
                                    @if($game->player_4)
                                        <li class="flex-item my-3">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img
                                                            src="{{ asset($game->player_4->photo) }}"
                                                            class="" style="width:50px;heigth:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_4->nick }} &nbsp; <i class="fa fa-dot-circle text-success"></i></h4>
                                                </div>
                                            </div>

                                        </li>
                                    @endif

                                    @if($players_count==$game->partie->typePartie)
                                        <li class="flex-item my-3 " style="position:absolute;bottom:0">
                                            <button class="btn btn-success"
                                                    onclick=" window.location.href='{{ route('jeu',['game'=>$game]) }}'">Proceed
                                                To Game
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                                <br>
                            </div>
                            <button class="btn btn-danger" type="button" onclick="quitGame({{ $game->id }})">
                                Quitter
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quitGame(id) {
            if (confirm('Quitter Game?')) {
                window.location.href = '{{ route('game.quitter') }}?game=' + id;
            }
        }
    </script>

@endsection