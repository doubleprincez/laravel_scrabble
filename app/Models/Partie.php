<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partie extends Model
{
    protected $table = 'partie';

    protected $guarded = [];

    public $timestamps = false;

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
