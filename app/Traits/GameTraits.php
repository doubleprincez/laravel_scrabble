<?php


namespace App\Traits;


use App\Models\Game;
use App\Models\Partie;
use App\Models\Reserve;
use App\Models\Stock;

trait GameTraits
{

    private function get_game_by_id($game_id)
    {
        return Game::with(['stock', 'partie', 'player_1', 'player_2', 'player_3', 'player_4'])->where('id', $game_id)->firstOrFail();
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

    public function check_game_finished($game)
    {
        return $this->check_game_stock($game);
    }

    public function create_game(int $user_id, int $type)
    {
        $game = Game::create([
            'user_id_1' => $user_id,
            'game_status' => true
        ]);
        $this->create_new_game_user_chavolet($game->id, $game);
        // create partie for game
        $this->create_partie($game->id, $type);
        // create stock for game
        $this->create_game_stock($game->id);
        return $game->load('player_1');
    }

    private function create_new_game_user_chavolet(int $game_id, Game $game = null)
    {
        if ($game != null) {
            // get game
            $game = Game::where('id', $game_id)->first();
        }

        // store it into first user chavolet
        $this->store_chavolet($game, 'user_1_chavolet');
    }

    private function store_chavolet(Game $game, $chavolet_user_column)
    {
        // get all game pieces
        $pieces = $this->generate_new_pieces();
        $game->$chavolet_user_column = $pieces;
        $game->save();

    }

    private function create_partie(int $game_id, int $type)
    {
        return Partie::create([
            'game_id' => $game_id,
            'dateDebutPartie' => now(),
            'typePartie' => $type
        ]);
    }

    private function create_game_stock(int $game_id)
    {
        $reserve = Reserve::all();
        foreach ($reserve as $item) {
            Stock::create([
                'game_id' => $game_id,
                'lettre' => $item->lettre,
                'quantite' => $item->quantite
            ]);
        }

    }

    private function check_all_user_game($user_id)
    {
        // and game is not empty
        return Game::with(['stock', 'partie', 'player_1', 'player_2', 'player_3', 'player_4'])->where('user_id_1', $user_id)
            ->orWhere('user_id_2', $user_id)
            ->orWhere('user_id_3', $user_id)
            ->orWhere('user_id_4', $user_id);


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
        $condition = $count < 7 ? $count : 7;
        // empty stack for holding new items we are about to pick from stock
        $stack = array();
        for ($i = 0; $i < $condition; $i++) {
            // generate random values using the condition stated above
            $rand = random_int(1, $condition);
            //
            $removed_letter = $game_stock[$rand];
            if ($removed_letter->quantite > 0) {
                $stack[] = $removed_letter->lettre;
                $qty = (int)$removed_letter->quantite;
                $removed_letter->quantite = $qty--;
                $removed_letter->save();
            }

        }
        // pass values in the stack into user_chavolet
        $user_chavolet = 'user_' . $position . '_chavolet';
        $game = $this->get_game_by_id($game_id);
        $game->$user_chavolet = json_encode($stack);
        $game->save();
        dd($game);
        return $game;
    }

    private function update_user_letters($id, $lettres)
    {
        return Joueur::find($id)->update(['chevalet' => $lettres]);
//    return Joueur::selectRaw('chevalet as chevalet')->where('idJoueur',1)
        // ->update(['chevalet'=>$lettres]);
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

    private function get_user_pieces(Game $game, $user_id)
    {
        $user_position = $this->search_user_chavolet($game, $user_id);

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


    private
    function search_user_chavolet($game, int $user_id)
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
        request()->validate([$image_nom => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
        if (request()->$image_nom) {
            $set_nom = time() . '.' . request()->$image_nom->extension();
            $path = public_path('img/players');
            request()->$image_nom->move($path, $set_nom);
            return 'img/players/' . $set_nom;
        } else {
            return null;
        }
    }
}