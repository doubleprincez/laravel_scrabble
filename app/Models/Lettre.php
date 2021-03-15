<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lettre extends Model
{
    protected $table = 'lettres';
    
    public $timestamps=false;
  public function letter($letter){
    $letter_value = 0;
    if (($letter == "a") || ($letter == "e") || ($letter == "i") || ($letter == "o") || ($letter == "u") || ($letter =="l") || ($letter == "n") || ($letter == "r") || ($letter == "s") || ($letter == "t")) {
        $letter_value = 1;
    } elseif (($letter == "d") || ($letter == "g")) {
        $letter_value = 2;
    } elseif (($letter == "b") || ($letter == "c") || ($letter == "m") || ($letter == "p")) {
        $letter_value = 3;
    } elseif (($letter == "f") || ($letter == "h") || ($letter == "v") || ($letter == "w") || ($letter == "y")) {
        $letter_value = 4;
    } elseif ($letter == "k") {
        $letter_value = 5;
    } elseif (($letter == "j") || ($letter == "x")) {
        $letter_value = 8;
    } elseif (($letter == "q") || ($letter == "z")) {
        $letter_value = 10;
    }
    return $letter_value;
  }
}
