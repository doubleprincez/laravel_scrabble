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
                            <?php $start = \Carbon\Carbon::parse($game->start_time);
                            $time = $start->diff(now())->format('%H:%i:%s');
                            ?>
                            <div class="counter">{{ $time }}</div>
                        @endif
                    </li>
                @endif
                @if($game->player_2)
                    <li>
                        <img width="30" height="30" src="{{ asset($game->player_2->photo) }}"><a
                                href="#">{{ $game->player_2->nick }}</a>
                        <div class="score"><b>score:</b>{{ $game->user_2_score }} | <b>nb lettres dans le
                                chevalet:</b>{{ $game->user_2_chavolet?count(json_decode($game->user_2_chavolet)):0 }}
                        </div>
                        @if($game->current_player==2)
                            <?php $start = \Carbon\Carbon::parse($game->start_time);
                            $time = $start->diff(now())->format('%H:%i:%s');
                            ?>
                            <div class="counter">{{ $time }}</div>
                        @endif
                    </li>
                @endif
                    @if($game->player_3)
                        <li>
                            <img width="30" height="30" src="{{ asset($game->player_3->photo) }}"><a
                                    href="#">{{ $game->player_3->nick }}</a>
                            <div class="score"><b>score:</b>{{ $game->user_3_score }} | <b>nb lettres dans le
                                    chevalet:</b>{{ $game->user_3_chavolet?count(json_decode($game->user_3_chavolet)):0 }}
                            </div>
                            @if($game->current_player==3)
                                <?php $start = \Carbon\Carbon::parse($game->start_time);
                                $time = $start->diff(now())->format('%H:%i:%s');
                                ?>
                                <div class="counter">{{ $time }}</div>
                            @endif
                        </li>
                    @endif
                    @if($game->player_4)
                        <li>
                            <img width="30" height="30" src="{{ asset($game->player_4->photo) }}"><a
                                    href="#">{{ $game->player_4->nick }}</a>
                            <div class="score"><b>score:</b>{{ $game->user_4_score }} | <b>nb lettres dans le
                                    chevalet:</b>{{ $game->user_4_chavolet?count(json_decode($game->user_4_chavolet)):0 }}
                            </div>
                            @if($game->current_player==4)
                                <?php $start = \Carbon\Carbon::parse($game->start_time);
                                $time = $start->diff(now())->format('%H:%i:%s');
                                ?>
                                <div class="counter">{{ $time }}</div>
                            @endif
                        </li>
                    @endif
            </ul>

        </div>
    </div>
</div>