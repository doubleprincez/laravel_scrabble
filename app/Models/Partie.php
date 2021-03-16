<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partie extends Model
{
    protected $table = 'partie';
    protected $fillable = ['typePartie', 'Reserve', 'grille', 'dateCreation', 'dateDebutPartie', 'dateFin', 'statutPartie'];
    public $timestamps = false;

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
