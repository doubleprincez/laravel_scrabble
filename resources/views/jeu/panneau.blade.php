<div class="col-md-8 col-md-offset-2 bootstrap snippets bootdeys">
    <div class="widget-container scrollable list rollodex">
        <div class="heading" id="panneau">
            <i class="fa"></i>Information du jeu</i>
        </div>
        <div class="widget-content">
            <!-- <div class="roll-title">
                L'information du jeu
            </div>  -->
            <ul>
                @if($game->player_1)
                    <li>
                        <img width="30" height="30" src="{{ asset($game->player_1->photo) }}"><a
                                href="#">{{ $game->player_1->nick }}</a>
                        <div class="score"><b>score:</b>{{ $game->user_1_score }} | <b>nb lettres dans le
                                chevalet:</b>{{ $game->user_1_chavolet?count(json_decode($game->user_1_chavolet)):0 }}
                        </div>
                        @if($game->current_player==1)
                            <div class="counter"> 2:54</div>
                        @endif
                    </li>
                @endif

            </ul>

        </div>
    </div>
</div>