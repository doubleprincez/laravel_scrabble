<?php


namespace App\Traits;


use App\Models\Game;
use App\Models\Partie;
use App\Models\Reserve;

trait GameTraits
{

    private function check_previous_game($user_id)
    {
        // check if user has any running game
        $previous_game = $this->check_new_game($user_id);

        if ($previous_game->count() > 0) {
            // user has previous game
            return $previous_game;
        } else {
            return false;
        }
    }

    public function create_game($user_id)
    {
        return Game::create([

        ]);
    }

    private function create_partie(int $game_id, int $type, int $user_id = null)
    {
        return Partie::create([
            'game_id' => $game_id,
            'user_id_1' => $user_id != null ? $user_id : auth()->id(),
            'typePartie' => $type
        ]);
    }

    private function create_game_stock(int $game_id)
    {
        $reserve = Reserve::all();
        foreach ($reserve as $item) {
            Partie::create([
                'game_id' => $game_id,
                'lettre' => $item->lettre,
                'quantite' => $item->quantite
            ]);
        }

    }

    private function check_new_game($user_id)
    {
        // and game is not empty
        return Game::where('user_id_1', $user_id)
            ->orWhere('user_id_2', $user_id)
            ->orWhere('user_id_3', $user_id)
            ->orWhere('user_id_4')->whereHas('stock', function ($query) {
                return $query->where('lettre', 'empty')->where('quantite', 0);
            })->get();
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


}