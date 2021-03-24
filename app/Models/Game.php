<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

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

    public function board()
    {
        return $this->hasMany(Board::class);
    }

    public function formatInformation()
    {
        return ['user_id_1' => $this->user_id_1,
            'user_id_2' => $this->user_id_2,
            'user_id_3' => $this->user_id_3,
            'user_id_4' => $this->user_id_4,
            'reserve' => $this->reserve,
            'user_1_chavolet' => $this->user_1_chavolet ? count(json_decode($this->user_1_chavolet, true)) : 0,
            'user_2_chavolet' => $this->user_2_chavolet ? count(json_decode($this->user_2_chavolet, true)) : 0,
            'user_3_chavolet' => $this->user_3_chavolet ? count(json_decode($this->user_3_chavolet, true)) : 0,
            'user_4_chavolet' => $this->user_4_chavolet ? count(json_decode($this->user_4_chavolet, true)) : 0,
            'user_1_score' => $this->user_1_score,
            'user_2_score' => $this->user_2_score,
            'user_3_score' => $this->user_3_score,
            'user_4_score' => $this->user_4_score,
        ];
    }

}
