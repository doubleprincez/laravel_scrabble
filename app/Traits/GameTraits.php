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
                $checker = (int)$this->sum_stock_quantite($game) != 0;
                if ($checker == true) {
                    // break out of the loop returning that game
                    return $game;
                }
            }

            return false;
        } else {
            return false;
        }
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

    private function generate_new_pieces()
    {
        $arr = collect(Reserve::get('lettre')
            ->random(7)->toArray());
        return $arr->flatten(1);
    }

    private function update_user_letters($id, $lettres)
    {
        return Joueur::find($id)->update(['chevalet' => $lettres]);
//    return Joueur::selectRaw('chevalet as chevalet')->where('idJoueur',1)
        // ->update(['chevalet'=>$lettres]);
    }

    private function check_user_pieces($id)
    {

        // use User Id to check if user still has game
        $lettre = Joueur::where('id', $id)->first();

        return !$lettre->chevalet || $lettre->chevalet == [] || empty($lettre->chevalet);
    }

    private function generate_nick(){
        // creating random user name
        $nick = 'user' . time();
    }
}