<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;


    public function post_by()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function format()
    {
        // if post belongs to current user, set true (i.e right) else (false i.e left)
        if (auth()->check()) {
            $p = (int)$this->user_id === (int)auth()->id();
        } else {
            $p = false;
        }
        return [
            'user_id' => $this->user_id,
            'contenu' => $this->contenu,
            'user_name' => $this->post_by->nick,
            'position' => $p,
            'image' => asset($this->post_by->photo),
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
