<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function partie()
    {
        return $this->hasOne(Partie::class);
    }

    public function player_1()
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    public function player_2()
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }

    public function player_3()
    {
        return $this->belongsTo(User::class, 'user_id_3');
    }

    public function player_4()
    {
        return $this->belongsTo(User::class, 'user_id_4');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }


}
