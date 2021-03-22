<div class="container" id="positionbt">
    <div class="row">
        <div class="col-md-8  bootstrap snippets bootdeys">
            <div class="widget-container scrollable list rollodex">
                <div class="heading" id="btc">
                    <span class="fa"></span> Boite de communication
                </div>
                <div class="panel-body">
                    <ul id="chat" class="chat">
                        <li class="left clearfix"><span class="chat-img pull-left">
                            <img width="40" height="40" src="{{ asset('img/scrabblelogo.png') }}" alt="User Avatar"
                                 class="img-circle"/>
                        </span>
                            <div class="chat-body text clearfix">
                                <div class="header">
                                    <strong class="primary-font">!Aide</strong>
                                </div>
                                <div class="text">
                                    <p><?php
                                        $time = date('H');
                                        if ($time < "12") {
                                            $g = "Bonjour!";
                                        } elseif ($time >= "12" && $time < "17") {
                                            $g = "Bon apres-midi!";
                                        } else {
                                            $g = "Bon Sour!";
                                        }
                                        ?>
                                        {{ $g }} Afin de pouvoir effectuer les tâches ci-dessous, vous pouvez utiliser
                                        ces 5 commandes:<br>
                                        <b>1. Placer un mot:</b> !placer ligne colonne (h|v) mot<br>
                                        <b>2. Changer une lettre:</b> !changer lettre<br>
                                        <b>3. Passer le tour à un autre joueur:</b> !passer<br>
                                        <b>4. Afficher le menu d'aide:</b> !aide<br>
                                        <b>5. Quitter menu d'aide:</b> !quitter<br>
                                    </p>
                                </div>
                            </div>
                        </li>
                        @if($game->messages)

                            @foreach($game->messages->take(6) as $msg)
                                @if($msg->post_by->id == auth()->id())
                                    <li class="right clearfix"><span class="chat-img pull-right">
                                @else
                                    <li class="left clearfix"><span class="chat-img pull-left">
                                        @endif
                            <img width="40" height="40" src="{{ asset($msg->post_by->photo) }}"
                                 alt="{{ $msg->post_by->nick }} Avatar"
                                 class="img-circle"/>

                        </span>
                                        <div class="chat-body clearfix">
                                            <div class="header     @if($msg->post_by->id == auth()->id()) text-right @endif">
                                                <strong class="primary-font">{{ $msg->post_by->nick }}</strong> <small
                                                        class="pull-right text-muted">
                                                    <span class="glyphicon glyphicon-time"></span>{{$msg->post_by->created_at->shortRelativeToNowDiffForHumans() }}
                                                </small>
                                            </div>
                                            <p>
                                                {{ $msg->contenu }}
                                            </p>
                                        </div>
                                    </li>
                                    @endforeach
                                @endif
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm"
                               placeholder="Tapez votre message ici..."/>
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
