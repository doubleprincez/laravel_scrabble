@extends('layout')
@section('title',"Salle d'attente")
@section('content')
    <div class="container-fluid mb-5">
        <div class="row align-content-center">
            <div class="col align-self-center text-center">
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
                    <p><span id="counter"> {{ $players_count }}</span> Joueur(s) restant(s)</p><br>
                @endisset
                <div id="spinner"
                     class=" @if($players_count!==$game->partie->typePartie) spinner-border  @endif text-danger"></div>

                <br>
                <div class="form-group form-group-inline">
                    <h3>Veuillez attendre vos adversaires..</h3>

                </div>
                <div class="form-group">

                    <div class="col-md-8 offset-md-2 bootstrap snippets bootdeys">
                        <div class="widget-container scrollable list rollodex my-5">
                            <h3 class="heading text-left">
                                <i class="fa fa-comment"></i>Players<i class="fa fa-plus pull-right"></i><i
                                        class="fa fa-search pull-right"></i><i class="fa fa-refresh pull-right"></i>
                            </h3>
                            <div class="widget-content text-left">
                                <ul id="players">
                                    @if($game->player_1)
                                        <li class="flex-item my-3">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img
                                                            src="{{ asset($game->player_1->photo) }}"
                                                            class="" style="width:50px;height:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_1->nick }} &nbsp; <i
                                                                class="fa fa-dot-circle text-success"></i></h4>
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
                                                            class="" style="width:50px;height:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_2->nick }} &nbsp; <i
                                                                class="fa fa-dot-circle text-success"></i></h4>
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
                                                            class="" style="width:50px;height:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_3->nick }} &nbsp; <i
                                                                class="fa fa-dot-circle text-success"></i></h4>
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
                                                            class="" style="width:50px;height:50px">
                                                </div>
                                                <div class="col-10 ">
                                                    <h4 class="text-info">{{ $game->player_4->nick }} &nbsp; <i
                                                                class="fa fa-dot-circle text-success"></i></h4>
                                                </div>
                                            </div>

                                        </li>
                                    @endif
                                    <li class="flex-item my-3 " style="position:absolute;bottom:0">
                                        <button id="continueBtn" class="btn btn-success"
                                                onclick=" window.location.href='{{ route('jeu',['game'=>$game]) }}'"
                                                @if($players_count!==$game->partie->typePartie)style="display:none" @endif>
                                            Proceed
                                            To Game
                                        </button>
                                    </li>

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
    <audio id="audio" src="{{ asset('wav/turn.mp3') }}" preload="auto"></audio>
    <script>
        function quitGame(id) {
            if (confirm('Quitter Game?')) {
                window.location.href = '{{ route('game.quitter') }}?game=' + id;
            }
        }

        // Add new Player that has been added to game


        setInterval(checkPlayers, 5000);
        var play = 0;
        var audio = document.getElementById('audio');
        audio.loop = false;

        function checkPlayers() {
            var url = '{{ route('game.checkNewPlayer') }}';
            var jeuUrl = " {{ route('jeu',['game'=>$game]) }}";
            // function to check if player time is still active

            $.ajax({
                url: url,
                type: 'post',
                data: {_token: "{{ csrf_token() }}", gameId:{{ $game->id }} },
                success: function (data) {
                    // get list of players as array
                    $('#counter').text(data.count);
                    if (data.alert) {
                        sendNotification(data.type, data.message);
                    }
                    if (data.players) {
                        var players = [];
                        for (var i = 1; i <= data.players.length; i++) {
                            var player = data.players[i - 1];
                            players[i - 1] = '<li class="flex-item my-3"> <div class="row"> <div class="col-2"> <img src="' + player.photo + '" class="" style="width:50px;height:50px">  </div> <div class="col-10 "> <h4 class="text-info">' + player.nick + ' &nbsp; <i class="fa fa-dot-circle text-success"></i></h4></div></div></li>';


                            // display list of users in player field
                            $('#players').html(players);
                        }
                        if (data.complete === true || data.complete === 'true') {
                            var btn = '<li class="flex-item my-3 " style="position:absolute;bottom:0"><button id="continueBtn" class="btn btn-success" onclick="window.location.href=\'' + jeuUrl + '\'" > Proceed To Game </button> </li>';
                            // game alarm
                            if (play === 0) {
                                audio.play();
                                play++;
                            }

                            $('#spinner').hide();
                            $('#players').append(btn);
                        } else {
                            $('#spinner').show();
                        }

                    }
                }
            });
        }
    </script>

@endsection