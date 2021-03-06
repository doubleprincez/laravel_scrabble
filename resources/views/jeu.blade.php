@extends('layout')

@section('title') Game @endsection
@section('styles')
    <!-- Boite de communication  -->

    <link rel="stylesheet" type="text/css" href="{{ asset('css/panneau.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/rack.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jeu.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/boite-communication.css') }}"/>

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

    <div class="logo" style="z-index: 10">
        <img src="{{ asset('img/scrabble.png') }}" class="logo" width="90" height="40">
    </div>

    <div class="reserve">
        <h6><b>Nb lettres dans la reserve:</b><span id="stock">{{ $game->stock->remaining() }}</span></h6>
        <div class="container-fluid" style="z-index: 5;position: absolute">
            <button class="btn btn-sm btn-outline-primary" onclick="skipTurn()" title="passer"><i
                        class="fa  fa-angle-double-right "></i></button>
            <button class="btn btn-sm btn-outline-secondary" onclick="reloadPieces($(this))"
                    title="recharger">
                <i class="fa fa-circle-notch"></i>
            </button>
            <button class="btn btn-sm btn-outline-info" onclick="changeRack($(this))" title=" mélanger"><i
                        class="fa fa-recycle"></i>
            </button>
        </div>
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
    <audio id="turn" src="{{ asset('wav/bell.wav') }}" preload="auto"></audio>
    <audio id="send" src="{{ asset('wav/send.mp3') }}" preload="auto"></audio>
@endsection

@section('scripts')

    <script src="{{ asset('js/jeu.js') }}" defer></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var turnCount = 0;
        var turnSound = document.getElementById('turn');
        var sendSound = document.getElementById('send');
        turnSound.loop = false;
        sendSound.loop = false;

        $(document).ready(function () {
            document.getElementById('btn-chat').addEventListener('click', function () {
                var input = $('#btn-input').val();
                var url = '{{ route('game.message') }}';
                var chatBox = document.getElementById('chat');
                // link to dictionary;


                // only when there is input will it send
                if (input) {
                    sendSound.play();
                    // first check if word exists in the dictionary
                    $('#btn-input').val('');
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: {_token: "{{ csrf_token() }}", gameId:{{ $game->id }}, message: input},
                        success: function (data) {
                            if (data) {
                                // TODO append new chat message to array of chatBox
                                if (data.message === '!quitter') {
                                    if (confirm('voulez-vous quitter le jeu?')) {
                                        window.location.href = '{{ route('game.quitter') }}?game=' + {{ $game->id }};
                                    }
                                } else {
                                    if (data.alert) {
                                        sendNotification(data.alert, data.message);
                                    }

                                }
                            }
                        },
                        error: function (err) {
                            $('#btn-input').val(input);
                        }
                    });
                }
            })
        });
        var checkTime = () => {
            var url = '{{ route('game.checkTimer') }}';
            // function to check if player time is still active

            $.ajax({
                url: url,
                type: 'post',
                data: {_token: "{{ csrf_token() }}", gameId:{{ $game->id }} },
                success: function (data) {
                    if (data.alert) {
                        sendNotification(data.alert, data.message);
                    }
                    if (data.active === true || data.active === 'true') {
                        if (turnCount === 0) {
                            // notify current user of turn
                            turnSound.play();
                            turnCount++;
                        }
                    } else {
                        turnCount = 0;
                    }
                    var current = $('#player' + data.current_player);
                    // remove any counter time
                    var counters = $('.counter');
                    $('#stock').text(data.stock);
                    counters.remove();
                    // create new timer on the player that is active
                    var timer = '<div class="counter">00:' + data.start_time + '</div>';
                    current.append(timer);
                    userchavolets(data.game, data.my_chovalet);
                    fetchMessages(data.messages);
                    plotBoard(data.board);
                }
            })
        }
        var userchavolets = (game, current) => {
            var p1, p2, p3, p4 = 0;
            let p1Score, p2Score, p3Score, p4Score = '';
            // user score
            if (game.user_id_1 != null) {
                p1 = game.user_1_chavolet;
                p1Score = game.user_1_score;

                $('#player1').children('div.score').html('<b>score:</b>' + p1Score + ' | <b>nb lettres dans le chavolet:</b>' + p1);

            }
            if (game.user_id_2 != null) {
                p2 = game.user_2_chavolet;
                p2Score = game.user_2_score;
                $('#player2').children('div.score').html('<b>score:</b>' + p2Score + ' | <b>nb lettres dans le chavolet:</b>' + p2);
            }
            if (game.user_id_3 != null) {
                p3 = game.user_3_chavolet;
                p3Score = game.user_3_score;
                $('#player3').children('div.score').html('<b>score:</b>' + p3Score + ' | <b>nb lettres dans le chavolet:</b>' + p3);
            }
            if (game.user_id_4 != null) {
                p4 = game.user_4_chavolet;
                p4Score = game.user_4_score;
                $('#player4').children('div.score').html('<b>score:</b>' + p4Score + ' | <b>nb lettres dans le chavolet:</b>' + p4);
            }

            // current user chavolet
            var arr = [];
            for (var i = 0; i < current.length; i++) {
                if (current[i] != null && current[i] !== "") {
                    arr[i] = '<div class="flex-item selected"><div class="top-left">' + current[i].lettre + '<sub class="number">' + current[i].valeur + '</sub></div></div>';
                } else {
                    arr[i] = '<div class="flex-item selected"><div class="top-left"></div></div>';
                }
            }

            $('#rack').html(arr);

        }
        var skipTurn = () => {
            var url = '{{ route('game.skip') }}';
            $.ajax({
                url: url,
                type: 'post',
                data: {_token: "{{ csrf_token() }}", gameId:{{ $game->id }}},
                success: function (data) {
                    if (data.alert) {
                        sendNotification(data.alert, data.message);
                    }
                }
            });
        }
        var reloadPieces = (el = null) => {
            if (el)
                el.children('i').addClass('fa-spin');

            var url = '{{ route('game.reload') }}';
            $.ajax({
                url: url,
                type: 'post',
                data: {_token: "{{ csrf_token() }}", gameId:{{ $game->id }}},
                success: function (data) {

                    if (el) el.children('i').removeClass('fa-spin');

                    if (data.alert) {
                        sendNotification(data.alert, data.message);
                    }
                },
                error: function (err) {
                    if (el) el.children('i').removeClass('fa-spin');

                }
            });
        }
        var changeRack = (el = null) => {
            if (el) el.children('i').addClass('fa-pulse');
            var url = '{{ route('game.rack.change') }}';
            $.ajax({
                url: url,
                type: 'post',
                data: {_token: "{{ csrf_token() }}", gameId:{{ $game->id }}},
                success: function (data) {
                    if (el) el.children('i').removeClass('fa-pulse');
                    if (data.alert) {
                        sendNotification(data.alert, data.message);
                    }
                },
                error: function (err) {
                    if (el) el.children('i').removeClass('fa-pulse');
                }
            });
        }
        var fetchMessages = (messages) => {
                <?php
                $time = date('H'); if ($time < "12") {
                    $g = "Bonjour!";
                } elseif ($time >= "12" && $time < "17") {
                    $g = "Bon apres-midi!";
                } else {
                    $g = "Bon Sour!";
                } ?>

            var g = '{{ $g }}';
            var chats = '<li class="left clearfix"><span class="chat-img pull-left"><img width="40" height="40" src="{{ asset('img/scrabblelogo.png') }}" alt="User Avatar" class="img-circle"/> </span> <div class="chat-body text clearfix"> <div class="header"> <strong class="primary-font">!Aide</strong> </div> <div class="text"> <p>' + g + ' Afin de pouvoir effectuer les tâches ci-dessous, vous pouvez utiliser  ces 5 commandes:<br>  <b>1. Placer un mot:</b> !placer ligne colonne (h|v) mot<br> <b>2. Changer une lettre:</b> !changer lettre<br>  <b>3. Passer le tour à un autre joueur:</b> !passer<br> <b>4. Afficher le menu d\'aide:</b> !aide<br> <b>5. Quitter menu d\'aide:</b> !quitter<br> </p> </div> </div> </li>';
            for (var i = 0; i < messages.length; i++) {
                var position = messages[i].position === true ? '<li class="right clearfix"><span class="chat-img pull-right">' : '<li class="left clearfix"><span class="chat-img pull-left">';
                var right = messages[i].position === true ? 'text-right' : '';
                chats += position + '<img width="40" height="40" src="' + messages[i].image + '" alt="' + messages[i].user_name + ' Avatar" class="img-circle"/></span> <div class="chat-body clearfix"> <div class="header ' + right + '"> <strong class="primary-font">' + messages[i].user_name + '</strong> <small class="pull-right text-muted"> <span class="glyphicon glyphicon-time"></span>' + messages[i].created_at + '</small> </div> <p> ' + messages[i].contenu + ' </p> </div>';
            }

            $('#chat').html(chats);
        }
        var plotBoard = (data) => {
            var setPiece, children, parent;
            for (var j = 0; j < data.length; j++) {
                for (var i = 0; i < data.length; i++) {
                    parent = $('#sq-' + (data[j][i].y + 1) + '-' + (data[j][i].x + 1));
                    children = parent.children();
                    if (data[j][i].tile) {
                        setPiece = '<div class="flex-item">' + data[j][i].tile.letter + '<sub class="number">' + data[j][i].tile.score + '</sub>';
                        parent.html(setPiece);
                        // if (parent.find('span').length) {
                        //     parent.find('span').html(setPiece);
                        // } else {
                        //     addSpan = '<span>' + setPiece + '</span>';
                        //     parent.append(addSpan);
                        // }
                    }
                }
            }
        }
        setInterval(checkTime, 4000);

    </script>

@endsection
