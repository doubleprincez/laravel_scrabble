<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Board
 * @package App\Models
 * @property integer game_id
 * @property integer user_id
 * @property mixed words
 * @property mixed score
 * @property mixed allTilesBonus
 * @property mixed tilesPlaced
 */
class Board extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
