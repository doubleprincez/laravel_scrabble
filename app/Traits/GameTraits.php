<?php


namespace App\Traits;


use App\Models\Game;
use App\Models\Lettre;
use App\Models\Message;
use App\Models\Partie;
use App\Models\Reserve;
use App\Models\Stock;
use Carbon\Carbon;

trait GameTraits
{
    use BoardTraits;

    private function get_game_by_id($game_id)
    {
        return Game::with(['stock', 'partie', 'player_1', 'player_2', 'player_3', 'player_4', 'messages', 'messages.post_by'])->where('id', $game_id)->firstOrFail();
    }

    private function update_game_timer($game, $user_id)
    {
        $now = now();
        // get number of game players
        $number_of_players = (int)$game->partie->typePartie;
        // get game current player
        $current_player = (int)$game->current_player;
        // get current player start time
        $start_time = Carbon::parse($game->start_time);

        // if the time is greater than 1 min
        if ($start_time->diffInSeconds($now) > 60) {

            // check if the last player in the game is the one playing
//            dd($current_player < $number_of_players);
            if ($current_player < $number_of_players) {
                $current_player++;
            } else {
                // if yes, then return to player one
                $current_player = 1;
            }
            // lets wait and check
            $game->current_player = $current_player;
            $game->start_time = $now;
            $game->save();
        }
        // boolean to know if current user is the one to play
        $active = $game->current_player == $user_id;
        $position = $this->user_chavolet_position($game, $user_id);

        // get if the player has no more playing piece left
        $user_chavolet = collect($this->get_user_chavolet($game, $user_id, $position));
        $valeur = $this->generate_valeur($user_chavolet);
        return ['start_time' => $now->diff($start_time)->s, 'current_player' => $game->current_player, 'active' => $active, 'game' => $game->formatInformation(), 'my_chovalet' => $valeur, 'messages' => $game->messages->map->format()];
    }

    private function message_manager($game, $user_id, $message)
    {
        $new_chat = new Message();
        $alert = "error";
        $msg = '';
        // get message !parser
        $check_pattern = preg_match("/ !placer/", $message);
        // if message does not contain command then upload as just chat
        if ($check_pattern !== 0) {

            // convert each letter to small letters
            $small = strtolower($message);
            $split = explode('!placer', $small);
            $placer = explode(' ', trim($split[1]));
            $piece_position = $placer[0];
            $direction = strtolower(substr($piece_position, -1, 1)); // e.g h/v horizontal/vertical

            $grid = substr($piece_position, 0, -1); // e.g g15

            $words = strtolower(trim($placer[1]));

            // check if word is in dictionary
            $in_dictionary = $this->check_dic($words);
            if ($in_dictionary == true) {
                // check if the words are in player chavolet
                $check_chavolet = $this->check_words_in_chavolet($game, $user_id, $words);

                if ($check_chavolet == true) {

                    $board = $this->load_server_board($game);

                    // returns board object or errors,'occupied','invalid'
                    $placed = $this->place_user_words($words, $direction, $grid, $board);

                    if (!is_object($placed)) {
                        $msg = $placed; // display error
                        goto rr;
                    }
                    // calculate player move
                    $touch = $this->calculate_move($board->squares, $direction);

                    if ($touch->error != null) {
                        $msg = $touch;
                        goto rr;
                    }
                    // get player position
                    $user_position = $this->get_user_game_position($game, $user_id);
                    // TODO store player scores
                    $this->store_user_score($game, $user_id, $user_position);
                    //  save player game to board
                    $this->save_board_to_server($game, $touch->move);

                    // remove player chavolet for the word
                    $this->remove_words_from_player_chavolet($game, $user_id, $words, $user_position);

                    // message
                    $msg = "Word played successfully";
                    $alert = "success";
                } else {
                    $msg = "Word is not in your Chavolet";
                }
            } else {
                // message
                $msg = 'Word is not in dictionary';
            }
        }
        // save current play as player play details
        $new_chat->user_id = $user_id;
        $new_chat->game_id = $game->id;
        $new_chat->contenu = $message;
        $new_chat->position = 1;
//      $new_chat->save();
        // return mgs, alert, communication
        rr:
        return ['alert' => $alert, 'message' => $msg];
    }

    private function remove_words_from_player_chavolet($game, $user_id, $words, $position)
    {

        $user_chavolet = $this->get_user_chavolet($game, $user_id, $position);
        $collected = collect($user_chavolet);
        $array = $collected;
        // words to array
        $splitted = str_split($words);
        foreach ($splitted as $a_word) {
//            foreach word
            foreach ($collected as $i => $arr) {
                // if word is equal to current array value
                if ($arr == $a_word) {
                    // set it as empty string
                    $array[$i] = "";
                    break;
                }
            }
        }
        // store chavolet back for that user
        $this->store_chavolet($game, $position, $array);
    }

    private function store_user_score($game, $score, $position)
    {
        $user_score = 'user_' . $position . '_score';
        $game->$user_score = $score;
        $game->save();
    }

    private function pass_user_turn($game, $user_id)
    {
        $current_player = (int)$game->current_player;
        $number_of_players = $game->partie->typePartie;
        if ($current_player == (int)$user_id) {
            if ($current_player < $number_of_players) {
                $current_player++;
            } else {
                $current_player = 1;
            }
            $game->current_player = $current_player;
            $game->save();
        }


    }

    private function check_words_in_chavolet($game, $user_id, $word)
    {
        $position = $this->user_chavolet_position($game, $user_id);

        // get if the player has no more playing piece left
        $user_chavolet = collect($this->get_user_chavolet($game, $user_id, $position));
        $failed = 0;
        // TODO more validations can be added for reoccurring numbers

        foreach ($a = str_split($word) as $letter) {
            // check if letters are all in the chavolet
            if (!$user_chavolet->contains($letter)) $failed++;
        }
        return $failed === 0;
    }


    private function check_dic($word)
    {
        $file = json_decode($this->get_dictionary());
        $lookup = collect($file);
        return $lookup->contains($word);
    }

    private function get_dictionary()
    {
        $doc = public_path('liste.de.mots.json');
        $data = file_get_contents($doc);
//        $curl = curl_init($doc);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        $data = curl_exec($curl);
//        curl_close($curl);
        return json_decode(json_encode($data), true);
    }

    private function get_letters()
    {
        return Lettre::all();
    }

    private function check_previous_game($user_id)
    {
        // check if user has any running game
        $previous_game = $this->check_all_user_game($user_id);

        if ($previous_game->count() > 0) {
            // user has previous game
            // check if any of those game is still active

            foreach ($games = $previous_game->get() as $game) {
                // looping through all games and checking their stock if it still remains to determine game that is finished
                if ($this->check_game_stock($game)) {
                    return $game;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    private function check_game_stock($game)
    {
        $checker = (int)$this->sum_stock_quantite($game) != 0;
        if ($checker == true) {
            // break out of the loop returning that game
            return $game;

        }
        return false;
    }

    private function select_any_waiting_game()
    {
        return Game::with(['stock', 'partie'])->inRandomOrder()->get()->map(function ($game) {

            $counter = 0;
            if ($game->user_id_1) {
                $counter++;
            }
            if ($game->user_id_2) {
                $counter++;
            }
            if ($game->user_id_3) {
                $counter++;
            }
            if ($game->user_id_4) {
                $counter++;
            }
            return (int)$counter !== (int)$game->partie->typePartie ? $game : null;
        })->first();

    }

    private function check_game_finished($game)
    {
        return $this->check_game_stock($game);
    }

    private function add_player_to_game($game, $user_id, $position)
    {
        $user_position = 'user_id_' . $position;
        $game->$user_position = $user_id;
        return $this->get_user_chavolet($game, $user_id, $position);

    }

    private function get_list_of_players($game)
    {
        $players = array();
        // since games have a limit of 4 players we will loop through
        for ($i = 0; $i < 4; $i++) {
            $check = 'player_' . $i;
            if ($game->$check) {
                $players[] = $game->$check;
            }
        }
        return $players;
    }

    private function get_empty_position($game)
    {
        if ($game->user_id_4) {
            return null;
        }
        if ($game->user_id_3) {
            return 4;
        }
        if ($game->user_id_2) {
            return 3;
        }
        if ($game->user_id_1) {
            return 2;
        }
    }

    private function get_user_game_position($game, int $user_id)
    {
        return $this->user_chavolet_position($game, $user_id);
    }

    private function create_game(int $user_id, int $type)
    {
        $game = Game::create([
            'user_id_1' => $user_id,
            'game_status' => true
        ]);
        // create partie for game
        $this->create_partie($game, $type);
        // create stock for game
        $this->create_game_stock($game);

        $this->create_new_game_user_chavolet($game, $user_id);
        return $game->load('player_1');
    }

    private function create_new_game_user_chavolet($game, $user_id)
    {
        // store it into first user chavolet
        $this->store_chavolet($game, 1);
    }

    private function store_chavolet(Game $game, $position, $pieces = null)
    {
        // get all game pieces
        if ($pieces == null)
            $pieces = $this->generate_new_pieces($game->id, $position);
        $user_position = 'user_' . $position . '_chavolet';
        $game->$user_position = $pieces;
        $game->save();

    }

    private function create_partie(Game $game, int $type)
    {
        return Partie::create([
            'game_id' => $game->id,
            'dateDebutPartie' => now(),
            'typePartie' => $type
        ]);
    }

    private function create_game_stock(Game $game)
    {
        $reserve = Reserve::all();
        foreach ($reserve as $item) {
            Stock::create([
                'game_id' => $game->id,
                'lettre' => $item->lettre,
                'quantite' => $item->quantite
            ]);
        }

    }

    private function check_all_user_game($user_id)
    {
        // and game is not empty
        return Game::with(['stock', 'partie', 'player_1', 'player_2', 'player_3', 'player_4', 'messages'])->where('user_id_1', $user_id)
            ->orWhere('user_id_2', $user_id)
            ->orWhere('user_id_3', $user_id)
            ->orWhere('user_id_4', $user_id);
    }

    private function get_user_chavolet($game, $user_id, $position)
    {
        $user_chavolet = json_decode($this->get_user_pieces($game, $user_id));

        if ($user_chavolet !== null) {
            $check_chavolet = $this->check_empty_array($user_chavolet);
            if ($check_chavolet === null && $check_chavolet === []) {
                return $this->generate_new_pieces($game->id, $position);
            }
            return $user_chavolet;
        }
        return $this->generate_new_pieces($game->id, $position);
    }

    private function sum_stock_quantite(Game $model)
    {
        return $model->stock->sum('quantite');
    }

    private function generate_new_pieces(int $game_id, int $position)
    {
        // To generate piece, we need the game stock, so we are sure the values are available
        // first get all values that are not 0
        $game_stock = Stock::where('game_id', $game_id)->where('quantite', '!=', 0)->get();

        // now get the count of those number and use it to select value on random
        $count = count($game_stock);
        // if values in the stock is less than 7, then use the stock current count else
        // use 7 because we are generating 7 items in user chavolet
        $condition = $count <= 7 ? $count : 7;
        // empty stack for holding new items we are about to pick from stock
        $stack = array();
        $parse = $game_stock;
        for ($i = 0; $i < $condition; $i++) {
            // generate random values using the condition stated above
            $rand = random_int(1, $condition);
            //
            $removed_letter = $parse[$rand];
            if ($removed_letter->quantite > 0) {
                $stack[] = $removed_letter->lettre;
                $qty = (int)$removed_letter->quantite;
                $removed_letter->quantite = $qty--;
                $removed_letter->save();
                $parse[$rand]->quantite--;
                $game_stock[$rand]->save(['quantite' => $qty]);
            }
        }
        // pass values in the stack into user_chavolet
        $user_chavolet = 'user_' . $position . '_chavolet';
        $game = $this->get_game_by_id($game_id);
        $game->$user_chavolet = json_encode($stack);
        $game->save();
        // return array pieces picked from the game stock
        return $stack;
    }

    private function generate_valeur($user_chavolet)
    {

        $valeur = [];
        foreach ($user_chavolet as $i) {

            if ($i != "" && $i != null) {
                $valeur[] = Lettre::where('lettre', '=', $i)->first(['lettre', 'valeur']);

            } else {
                $valeur[] = null;
            }
        }
        return $valeur;
    }

    private function check_empty_array(array $array)
    {
        // create an empty array called stack
        $stack = array();
        // foreach of the array we passed in
        foreach ($array as $key) {
            // if the value is not an empty string or is not null,
            // pass that value into our stack
            if ($key != "" && $key != null) $stack[] = $key;
        }
        // return the stack
        return $stack;
    }

    private function get_user_messages($game, $user_id)
    {
        // creating a limit to the amount of messages that display on user chat screen
        $limit = config('app.chat_limit');
        return Message::where('game_id', $game->id)->where('user_id', $user_id)->paginate($limit);
    }

    private function get_user_pieces(Game $game, $user_id)
    {
        $user_position = $this->user_chavolet_position($game, $user_id);

        if ($user_position == 1) {
            return $game->user_1_chavolet;
        } elseif ($user_position == 2) {
            return $game->user_2_chavolet;
        } elseif ($user_position == 3) {
            return $game->user_3_chavolet;
        } elseif ($user_position = 4) {
            return $game->user_4_chavolet;
        } else
            return null;
    }


    private function user_chavolet_position($game, int $user_id)
    {
        if ((int)$game->user_id_1 == $user_id) {
            return 1;
        } elseif ((int)$game->user_id_2 == $user_id) {
            return 2;
        } elseif ((int)$game->user_id_3 == $user_id) {
            return 3;
        } elseif ((int)$game->user_id_4 == $user_id) {
            return 4;
        } else {
            return null;
        }
    }

    private
    function generate_nick()
    {
        // creating random user name
        return 'user' . time();
    }

    private
    function upload_image($image_nom)
    {
        request()->validate([$image_nom => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048']);

        if (request()->$image_nom) {
            $set_nom = time() . '.' . request()->$image_nom->extension();
            $path = public_path('img/players');
            request()->$image_nom->move($path, $set_nom);
            return 'img/players/' . $set_nom;
        }
        return null;

    }
}