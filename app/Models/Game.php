<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function partie()
    {
        return $this->hasOne(Partie::class);
    }
}
