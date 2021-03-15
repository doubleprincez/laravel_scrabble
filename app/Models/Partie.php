<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partie extends Model
{
    protected $table = 'partie';
    protected $fillable = ['typePartie','Reserve','grille','dateCreation','dateDebutPartie','dateFin','statutPartie'];
    public $timestamps=false;

}
