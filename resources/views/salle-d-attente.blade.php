@extends('layout')
@section('title',"Salle d'attente")
@section('salle-d-attente')
    <div class="media-body">
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

                    <div class="col-md-8 col-md-offset-2 bootstrap snippets bootdeys">
                        <div class="widget-container scrollable list rollodex">
                            <div class="heading">
                                <i class="fa fa-comment"></i>PartieID<i class="fa fa-plus pull-right"></i><i
                                        class="fa fa-search pull-right"></i><i class="fa fa-refresh pull-right"></i>
                            </div>
                            <div class="widget-content">
                                <div class="roll-title">
                                </div>
                                <ul>
                                    @if($game->player_1)
                                        <li>
                                            <img width="30" height="30" src="{{ asset('storage/'.$game->player_1->photo) }}"><a
                                                    href="#"><br>{{ $game->player_1->nick }}</a>
                                        </li>
                                    @endif
                                    @if($game->player_2)
                                        <li>
                                            <img width="30" height="30" src="{{ asset('storage/'.$game->player_2->photo) }}"><a
                                                    href="#"><br>{{ $game->player_2->nick }}</a>
                                        </li>
                                    @endif
                                    @if($game->player_3)
                                        <li>
                                            <img width="30" height="30" src="{{ asset('storage/'.$game->player_3->photo) }}"><a
                                                    href="#"><br>{{ $game->player_3->nick }}</a>
                                        </li>
                                    @endif
                                    @if($game->player_4)
                                        <li>
                                            <img width="30" height="30" src="{{ asset('storage/'.$game->player_4->photo) }}"><a
                                                    href="#"><br>{{ $game->player_4->nick }}</a>
                                        </li>
                                    @endif
                                    @if($players_count==$game->partie->typePartie)
                                        <li>
                                            <button class="btn btn-success"
                                                    onclick=" window.location.href='{{ route('jeu',$game) }}'">Proceed
                                                To Game
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                                <br>
                            </div>
                            <br>
                            <div class="form-group">
                                <button class="btn btn-danger" type="button" onclick="quitGame({{ $game->id }})">
                                    Quitter
                                </button>
                            </div>
                            <script>
                                function quitGame(id) {
                                    if (confirm('Quitter Game?')) {
                                        window.location.href = '{{ route('game.quitter') }}?game=' + id;
                                    }
                                }
                            </script>
@endsection