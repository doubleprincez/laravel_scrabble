@extends('layout')
@section('title',"Game Ended")
@section('content')
    <div class="container-fluid mb-5">
        <div class="row align-content-center">
            <div class="col align-self-center text-center">
                <div id="loading" class="spinner-border text-danger" style="display:none"></div>
                <br>
                <div class="vh-100 mx-auto">
                    <h3>Jeu terminé</h3>
                    <video muted autoplay loop="true" onloadstart>
                        <source src="{{ asset('video/congrats.mp4') }}" type="video/mp4">
                        Votre navigateur ne prend pas en charge la balise vidéo.
                    </video>
                    <div>
                        <?php
                        $test1 = $game->user_1_score > $game->user_2_score;
                        $test2 = $game->user_1_score > $game->user_3_score;
                        $test3 = $game->user_1_score > $game->user_4_score;
                        $test4 = $game->user_2_score > $game->user_3_score;
                        $test5 = $game->user_2_score > $game->user_4_score;
                        $test6 = $game->user_3_score > $game->user_4_score;

                        if ($test1 && $test2 && $test3) {
                            $highest = $game->user_1_score;
                            $user = $game->player_1;
                        } elseif ($test4 && $test5) {
                            $highest = $game->user_2_score;
                            $user = $game->player_2;
                        } elseif ($test6) {
                            $highest = $game->user_3_score;
                            $user = $game->player_3;
                        } else {
                            $highest = $game->user_4_score;
                            $user = $game->player_4;
                        }
                        ?>
                        <h1 class="text-success">Le joueur  {{ $user->nick }} a gagné! </h1>
                        <p>Statistiques du joueur</p>
                        @for($i = 0; $i < $game->partie->typePartie; $i++)
                            <p>Joueur <?php $g = 'player_' . ($i + 1); echo $game->$g->nick;  ?>
                                marquer <?php  $sc = 'user_' . ($i + 1) . '_score'; echo $game->$sc;  ?></p>
                        @endfor
                    </div>
                    <button class="btn btn-success" onclick="window.location.href='{{ route('game.select') }}'">Start
                        Nouveau jeu
                    </button>
                    <button class="btn btn-info" onclick="restartGame()">Recommencer le jeu</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var restartGame = () => {
            if (confirm('Voulez-vous redémarrer le jeu?')) {
                var url = '{{ route('game.restart') }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {_token: "{{ csrf_token() }}", gameId:{{ $game->id }}},
                    success: function (data) {
                        if (data.message === 'redirect') {
                            window.location.href = '{{ route('jeu') }}';
                        } else {
                            sendNotification(data.alert, data.message);
                        }
                    }
                });

            }
        }
    </script>
@endsection