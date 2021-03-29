<?php


namespace App\Classes;


use App\Models\Lettre;

class Board
{
    public $squares;


//Board>prototype>Dimension = 15;
    public $Dimension = 15;


    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $store = new MakeBoardArray();
        $this->squares = (array)$store->array;

        for ($y = 0; $y < $this->Dimension; $y++) {
            for ($x = 0; $x < $this->Dimension; $x++) {
                $centerStart = false;
                $square = new Square('Normal', $this);

                $middle = floor($this->Dimension / 2);
                $halfMiddle = ceil($middle / 2);
                $test1_middle1 = ($x == 0 || $x == $this->Dimension - 1 || $x == $middle);
                $test1_middle2 = ($y == 0 || $y == $this->Dimension - 1 || ($y == $middle && $x != $middle));

                $test1 = $test1_middle1 && $test1_middle2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////
                $test2_middle1 = $x == $middle && $y == $middle;
                $test2_middle2 = $x > 0 && $x < $middle - 2 && ($y == $x || $y == $this->Dimension - $x - 1);
                $test2_middle3 = $x > $middle + 2 && $x < $this->Dimension - 1 && ($x == $y || $x == $this->Dimension - $y - 1);
                $test2 = $test2_middle1 || $test2_middle2 || $test2_middle3;
                ////////////////////////////////////////////////////////////////////////////
                $test3_middle1 = ($x == $middle - 1 || $x == $middle + 1) && ($y == $middle - 1 || $y == $middle + 1);
                $test3_middle2 = ($x == 0 || $x == $this->Dimension - 1 || $x == $middle) && ($y == $middle + $halfMiddle || $y == $middle - $halfMiddle);
                $test3_middle3 = ($y == 0 || $y == $this->Dimension - 1 || $y == $middle) && ($x == $middle + $halfMiddle || $x == $middle - $halfMiddle);
                $test3_middle4 = ($y == $middle + 1 || $y == $middle - 1) && ($x == $middle + $halfMiddle + 1 || $x == $middle - $halfMiddle - 1);
                $test3_middle5 = ($x == $middle + 1 || $x == $middle - 1) && ($y == $middle + $halfMiddle + 1 || $y == $middle - $halfMiddle - 1);

                $test3 = $test3_middle1 || $test3_middle2 || $test3_middle3 || $test3_middle4 || $test3_middle5;
                ///////////////////////////////////////////////////////////////////////////////////////////////////
                $test4_middle1 = ($x == $middle - 2 || $x == $middle + 2) && ($y == $middle - 2 || $y == $middle + 2);
                $test4_middle2 = ($y == $middle + 2 || $y == $middle - 2) && ($x == $middle + $halfMiddle + 2 || $x == $middle - $halfMiddle - 2);
                $test4_middle3 = ($x == $middle + 2 || $x == $middle - 2) && ($y == $middle + $halfMiddle + 2 || $y == $middle - $halfMiddle - 2);
                $test4 = $test4_middle1 || $test4_middle2 || $test4_middle3;
                //////////////////////////////////////////////////////////////////////////////////////////////////////////
                if ($test1) {
                    $square = new Square('TripleWord', $this);
                } else if ($test2) {
                    $square = new Square('DoubleWord', $this);
                    if ($x == $middle && $y == $middle) {
                        $centerStart = true;
                    }
                } else if ($test3) {
                    $square = new Square('DoubleLetter', $this);
                } else if ($test4) {
                    $square = new Square('TripleLetter', $this);
                }

                $square->x = $x;
                $square->y = $y;

                $this->squares[$x][$y] = $square;
            }
        }
//    triggerEvent('BoardReady', [ $this ]);

    }


    /**
     * @param $letter
     * @return int|string
     */
    public function get_letter_grid_position($letter)
    {
        $positions = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o'];
        foreach ($positions as $i => $iValue) {
            if ($letter == $iValue) {
                return $i+1;
                break;
            }
        }
    }


    public function word_within_board($x, $y, $direction, $spliced_word): bool
    {
        $word_count = count($spliced_word);
        // vertically the words does not go beyond board range
        if ($direction == 'v') {
            return $x + $word_count < 15;
        }
        // horizontally the word does not exceed range too
        return $y + $word_count < 15;
    }


    protected function forAllSquares($f)
    {
        for ($y = 0; $y < $this->Dimension; $y++) {
            for ($x = 0; $x < $this->Dimension; $x++) {
                $f($this->squares[$x][$y]);
            }
        }
    }

    public function emptyTiles()
    {
        $this->forAllSquares(function ($square) {
            $square->placeTile(null);
        });
    }

    public function toString()
    {
        return "Board " . $this->Dimension . " x " . $this->Dimension;
    }



}