<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Board
 * @package App\Models
 * @property integer game_id
 * @property integer player_id
 * @property string direction
 * @property mixed position
 * @property  mixed word
 */
class Board extends Model
{
    use HasFactory;

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
