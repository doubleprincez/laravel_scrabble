<?php


namespace App\Classes;


class Tile
{
    public $letter;
    public $score;

    public function __construct($letter, $score)
    {
        $this->letter = $letter;
        $this->score = $score;
    }

    public function isBlank()
    {
        return $this->score == 0;
    }

    public function toString()
    {
        return "Tile: [" . ($this->isBlank() ? "blank" : $this->letter) . "]   " . $this->score;
    }
}