@extends('jeu')
@section('jeu.boite-communication')
<div class="container" id="positionbt"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2 bootstrap snippets bootdeys" >
            <div class="widget-container scrollable list rollodex">
                <div class="heading" id="btc">
                    <span class="fa"></span> Boite de communication

                </div>
                <div class="panel-body">
                    <ul class="chat">
                    <li class="left clearfix"><span class="chat-img pull-left" >
                            <img  width="40" height="40" src="{{ asset('img/scrabblelogo.png') }}" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix" class="text">
                                <div class="header">
                                    <strong class="primary-font">!Aide</strong> 
                                </div>
                                <div class="text">
                                    <p>
                                        Bonjour! Afin de pouvoir effectuer les tâches ci-dessous, vous pouvez utiliser ces 5 commandes:<br>
                                        <b>1. Placer un mot:</b> !placer ligne colonne (h|v) mot<br>
                                        <b>2. Changer une lettre:</b> !changer lettre<br>
                                        <b>3. Passer le tour à un autre joueur:</b> !passer<br>
                                        <b>4. Afficher le menu d'aide:</b> !aide<br>
                                        <b>5. Quitter menu d'aide:</b> !quitter<br>
                                    </p>
                                </div>
                            </div>
                        </li>

                        <li class="left clearfix"><span class="chat-img pull-left">
                            <img  width="40" height="40" src="{{ asset('img/j1.jpg') }}" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">Mohamed Aziz Touali</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
                                </div>
                                <p>
                                    Bonjour! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                </p>
                            </div>
                        </li>
                        <li class="right clearfix"><span class="chat-img pull-right">
                            <img  width="40" height="40" src="{{ asset('img/j4.jpg') }}" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
                                    <strong class="pull-right primary-font">Nesrine Hadj Khelil</strong>
                                </div>
                                <p>
                                   Bonjour! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                </p>
                            </div>
                        </li>
                        <li class="left clearfix"><span class="chat-img pull-left">
                            <img  width="40" height="40" src="{{ asset('img/j2.jpg') }}" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">Jelel Fliss</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span>14 mins ago</small>
                                </div>
                                <p>
                                    Bonjour! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                </p>
                            </div>
                        </li>
                        <li class="right clearfix"><span class="chat-img pull-right">
                            <img  width="40" height="40" src="{{ asset('img/j3.jpg') }}" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>15 mins ago</small>
                                    <strong class="pull-right primary-font">Achref Mabrouk</strong>
                                </div>
                                <p>
                                    Bonjour!Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Tapez votre message ici..." />
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-sm" id="btn-chat">
                                Envoyer</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection