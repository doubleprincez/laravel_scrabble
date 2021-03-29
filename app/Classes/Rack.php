<?php


namespace App\Classes;


class Rack
{
    public $squares;

    public function __construct($size)
    {
        $this->squares = [];

        for ($x = 0; $x < $size; $x++) {
            $square = new Square('Normal', $this);
            $square->x = $x;
            $square->y = -1;
            $this->squares[$x] = $square;
        }

//    triggerEvent('RackReady', [ $this ]);
    }


    public function emptyTiles()
    {
        $count = count($this->squares);
        for ($x = 0; $x < $count; $x++) {
            $square = $this->squares[$x];

            $square->placeTile(null);
        }
    }

    public function toString()
    {
        return "Rack " . count($this->squares);
    }

    private function reduce($carry, $item)
    {
        if ($item->tile) {
            $carry->push($item->tile->letter);
        }
        return $carry;
    }

    public function letters()
    {
        return array_reduce($this->squares, 'reduce', []);
    }

    public function findLetterSquare($letter, $includingBlank)
    {
        $blankSquare = null;
        // loop to find letter in square
//        $square = _->find($this->squares,
//        function ($square) use ($blankSquare, $letter) {
//            if ($square->tile) {
//                if ($square->tile->isBlank() && !$blankSquare) {
//                    $blankSquare = $square;
//                } else if ($square->tile->letter == $letter) {
//                    return true;
//                }
//            }
//        });
//    if ($square) {
//        return $square;
//    } else if ($includingBlank) {
//        return $blankSquare;
//    } else {
//        return null;
//    }
    }
}