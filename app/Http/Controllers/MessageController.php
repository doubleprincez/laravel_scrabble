<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Traits\GameTraits;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use GameTraits;

    public function index()
    {
        $user_id = auth()->id();
        // message will have the game Id
        if (request()->has('gameId')) {
            $game = $this->get_game_by_id(request()->get('gameId'));
            // trim message i.e remove any spaces before and after message & no html tags allowed
            $message = htmlspecialchars(trim(request()->get('message')));

            // send to the message function that handles chat and playing the pieces
            return $this->message_manager($game, $user_id, $message);
        }
    }

}
