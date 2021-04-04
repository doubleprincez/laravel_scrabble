<?php

namespace App\Http\Controllers;

use App\Traits\GameTraits;

class JeuAPIController extends Controller
{
    use  GameTraits;

    public function __construct()
    {
        // This will ensure we pick user id because they must be logged in
        $this->middleware('auth');
    }

    public function check_timer()
    {
        // current user id
        $user_id = auth()->id();
        // get game
        if (request()->has('gameId')) {
            $game = $this->get_game_by_id(request()->get('gameId'));
            // check if current user is the one playing now or the time has changed.
            $update_time = $this->update_game_timer($game, $user_id);
            // TODO check for new messages and send along
            $player_messages = $this->get_user_messages($game, $user_id);

            // TODO then send details of current stock for he game as it is reducing
            if ($game->stock) {
                $stock = $game->stock->remaining();
            } else {
                $stock = 0;
            }


            $board = $this->load_server_board($game)->squares;
            $selected = $this->remove_recursion($board);
            return $update_time +
                [
                    'game' => $game,
                    'stock' => $stock,
                    'messages' => $player_messages,
                    'board' => $selected
                ];
        }
    }

    public function check_new_player()
    {
        // check if new players have been added dynamically to game list
        if (request()->has('gameId')) {
            // return list of game players and their details as well as the game type for checking
            $game = $this->get_game_by_id((int)request()->get('gameId'));

            // get all the players currently available in the game
            $players = $this->get_list_of_players($game);

            $counter = count($players);
            // check if players are complete and game can proceed
            $complete = $counter === (int)$game->partie->typePartie;
            return ['players' => $players, 'count' => (int)$game->partie->typePartie - $counter, 'complete' => $complete];
        }
    }

    public function skip_turn()
    {
        $alert = 'error';
        $msg = '';
        if (request()->has('gameId')) {
            $user_id = auth()->id();
            $game_id = request()->get('gameId');
            $game = $this->get_game_by_id($game_id);
            // skip player turn
            $array = $this->pass_user_turn($game, $user_id);
            $msg = $array['msg'];
            $alert = $array['alert'];
        } else {
            $msg = 'Impossible de sélectionner le jeu';
        }
        return $this->format_response($alert, $msg);
    }

    public function reload_pieces()
    {
        $msg = '';
        $alert = 'error';
        if (request()->has('gameId')) {
            $user_id = auth()->id();
            $game_id = request()->get('gameId');
            $game = $this->get_game_by_id($game_id);
            $new_chavolet = $this->reload_user_chavolet($game, $user_id);
            $position = $this->get_user_game_position($game, $user_id);
            $this->store_chavolet($game, $user_id, $new_chavolet);
            $msg = 'Reloaded';
            $alert = 'success';

        } else {
            $msg = 'Impossible de sélectionner le jeu';
        }
        return $this->format_response($alert, $msg);
    }

    public function rack_change()
    {
        $msg = '';
        $alert = 'error';

        if (request()->has('gameId')) {
            $user_id = auth()->id();
            $game_id = request()->get('gameId');
            $game = $this->get_game_by_id($game_id);
            // skip player turn
            $array = $this->switch_player_pieces($game, $user_id);
            $msg = $array['msg'];
            $alert = $array['alert'];
        } else {
            $msg = 'Impossible de sélectionner le jeu';
        }
        return $this->format_response($alert, $msg);

    }

    private function format_response($alert, $message)
    {
        return response()->json(['alert' => $alert, 'message' => $message]);
    }

    public function restart_game()
    {
        $alert = 'error';
        $msg = '';
        if (request()->has('gameId')) {
            $user_id = auth()->id();
            $game_id = request()->get('gameId');
            //
            $game = $this->get_game_by_id($game_id);
            if ($game->user_id_1 == $user_id) {
                $msg = $this->restart_game_from_scratch($game);
                $alert = 'success';
            } else {
                $msg = 'Seul le joueur 1 peut redémarrer';
            }

        }
        return $this->format_response($alert, $msg);
    }
}
