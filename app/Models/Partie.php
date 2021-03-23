<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partie extends Model
{
    use SoftDeletes;

    protected $table = 'partie';

    protected $guarded = [];

    public $timestamps = false;

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
