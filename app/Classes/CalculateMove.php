<?php


namespace App\Classes;


class CalculateMove
{

    public $topLeftX = 0;
    public $topLeftY = 0;
    public $tile = null;
    public $squares;
    public $move;
    public $dictionary = null;
    public $error = null;

    public function __construct($input, $direction)
    {
        $squares = (array)$input;
        $this->squares = $squares;
        $this->move = (object)['words' => [], 'score' => 0, 'allTilesBonus' => null, 'tilesPlaced' => null];
        $this->init($squares, $direction);
    }

    public function init($squares, $direction)
    {
        // Check that the start field is occupied
        if (!$squares[7][7]->tile) $this->error = "start field must be used";
        // Determine that the placement of the Tile(s) is legal

        // Find top-leftmost placed tile

        for ($y = 0; empty($this->tile) && $y < 15; $y++) {
            for ($x = 0; empty($this->tile) && $x < 15; $x++) {
                if (!empty($squares[$y][$x]->tile) && (!$squares[$y][$x]->tileLocked || $squares[$y][$x]->tileLocked == false)) {
                    $this->tile = $squares[$y][$x]->tile;
                    $this->topLeftX = $x;
                    $this->topLeftY = $y;
                }
            }
        }
        if (!$this->tile) {
            $this->error = "no new tile found";
        }

        // Remember which newly placed tile positions are legal
        $first = new MakeBoardArray();
        $legalPlacements = (array)$first;
        $legalPlacements[$this->topLeftX][$this->topLeftY] = true;

        $isTouchingOld = $this->touchingOld($this->topLeftX, $this->topLeftY);
        $horizontal = $direction !== 'v';

        for ($x = $this->topLeftX + 1; $x < 15; $x++) {
            if (!$this->squares[$this->topLeftY][$x]->tile) {
                break;
            } else if (!$this->squares[$this->topLeftY][$x]->tileLocked) {
                if (isset($legalPlacements[$this->topLeftY][$x])) $legalPlacements[$this->topLeftY][$x] = true;
                $horizontal = true;
                $isTouchingOld = $isTouchingOld || $this->touchingOld($x, $this->topLeftY);
            }
        }
        if (!$horizontal) {
            for ($y = $this->topLeftY + 1; $y < 15; $y++) {
                if (!$this->squares[$y][$this->topLeftX]->tile) {
                    break;
                } else if (!$this->squares[$y][$this->topLeftX]->tileLocked) {
                    if (isset($legalPlacements[$y][$this->topLeftX])) $legalPlacements[$y][$this->topLeftX] = true;
                    $isTouchingOld = $isTouchingOld || $this->touchingOld($this->topLeftX, $y);
                }
            }
        }

        if (!$isTouchingOld && (isset($legalPlacements[7][7]) && !$legalPlacements[7][7])) {
            $this->error = 'Not touching old tile ' . $this->topLeftX . '/' . $this->topLeftY;
        }

        // Check whether there are any unconnected other placements, count total tiles on board
        $totalTiles = 0;
        for ($y = 0; $y < 15; $y++) {
            for ($x = 0; $x < 15; $x++) {
                $square = $this->squares[$y][$x];
                if ($square->tile) {
                    $totalTiles++;
                    if ($square->tileLocked == false && (isset($legalPlacements[$y][$x]) && !$legalPlacements[$y][$x])) {
                        $this->error = 'unconnected placement';
                    }
                }
            }
        }

        if ($totalTiles == 1) {
            $this->error = 'first word must consist of at least two letters';
        }

        $this->move->score = $this->horizontalWordScores($squares);
        // Create rotated version of the board to calculate vertical word scores->
        $second = new MakeBoardArray();
        $rotatedSquares = (array)$second;
        for ($y = 0; $y < 15; $y++) {
            for ($x = 0; $x < 15; $x++) {
                $rotatedSquares[$y][$x] = $squares[$x][$y];
            }
        }
        $this->move->score += $this->horizontalWordScores($rotatedSquares);

        // Collect and count tiles placed->
        $tilesPlaced = [];
        for ($y = 0; $y < 15; $y++) {
            for ($x = 0; $x < 15; $x++) {
                $square = $squares[$y][$x];
                if ($square->tile && $square->tileLocked == false) {
                    $tilesPlaced[] = ([
                        'letter' => $square->tile->letter,
                        'score' => $square->tile->score,
                        'tileLocked' => true,
                        'x' => $square->x,
                        'y' => $square->y,
                        'blank' => $square->tile->isBlank()]);
                }
            }
        }
        if (count($tilesPlaced) == 7) {
            $this->move->score += 50;
            $this->move->allTilesBonus = true;
        }
        $this->move->tilesPlaced = $tilesPlaced;
        return $this->move;
    }

    // The move was legal, calculate scores
    public function horizontalWordScores($squares): int
    {
        $score = 0;
        for ($y = 0; $y < 15; $y++) {
            for ($x = 0; $x < 14; $x++) {
                if ($squares[$y][$x]->tile && $squares[$y][($x + 1)]->tile) {
                    $wordScore = 0;
                    $letters = '';
                    $touching = collect([]);
                    $wordMultiplier = 1;
                    $isNewWord = false;
                    $foundTouch = false;
                    for (; $x < 15 && $squares[$y][$x]->tile; $x++) {
                        $square = $squares[$y][$x];
                        $letterScore = $square->tile->score;
                        $isNewWord = $isNewWord || $square->tileLocked == false;
                        if ($square->tileLocked == false) {
//                            $touches = $this->removeEmptyCells($this->getOldTouch($square->x, $square->y));
//                            if ($touches !== []) {
//                                $foundTouch = true;
//                                $touching->add($touches);
//                            }
                            // check if word is in dictionary
//                            $in_dictionary = $this->check_dic($words);
                            switch ($square->type) {
                                case
                                'DoubleLetter':
                                    $letterScore *= 2;
                                    break;
                                case 'TripleLetter':
                                    $letterScore *= 3;
                                    break;
                                case 'DoubleWord':
                                    $wordMultiplier *= 2;
                                    break;
                                case 'TripleWord':
                                    $wordMultiplier *= 3;
                                    break;
                            }
                        }
                        // calculates and store new words
                        $wordScore += $letterScore;
                        $letters .= $square->tile->letter;
                    }

                    $wordScore *= $wordMultiplier;

                    if ($isNewWord) {
                        $inDic = $this->check_in_dictionary($letters);
                        if ($inDic) {
                            $this->move->words[] = ['word' => $letters, 'score' => $wordScore];
                            $score += $wordScore;
                        }

                    }
                }
            }
        }
        return (int)$score;
    }

    private function check_in_dictionary($word)
    {
        if ($this->dictionary == null) {
            $file = json_decode($this->get_dictionary());
            $this->dictionary = collect($file);
            $lookup = collect($file);
        } else {
            $lookup = $this->dictionary;
        }
        return $lookup->contains($word);
    }

    private function get_dictionary()
    {
        $doc = public_path('liste.de.mots.json');
        $data = file_get_contents($doc);
//        $curl = curl_init($doc);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        $data = curl_exec($curl);
//        curl_close($curl);
        return json_decode(json_encode($data), true);
    }

    public function touchingOld($x, $y): bool
    {

        $check_sub1 = $x > 0 && (isset($this->squares[($x - 1)][$y]) && $this->squares[($x - 1)][$y]->tile);

        $check_sub2 = isset($this->squares[($x - 1)][$y]) && $this->squares[($x - 1)][$y]->tileLocked == true;

        $check_sub3 = $x < 14 && (isset($this->squares[($x + 1)][$y]) && $this->squares[($x + 1)][$y]->tile);

        $check_sub4 = isset($this->squares[($x + 1)][$y]) && $this->squares[($x + 1)][$y]->tileLocked == true;

        $check_sub5 = $y > 0 && (isset($this->squares[$x][($y - 1)]) && $this->squares[$x][($y - 1)]->tile);
        $check_sub6 = isset($this->squares[$x][($y - 1)]) && $this->squares[$x][($y - 1)]->tileLocked == true;

        $check_sub7 = $y < 14 && (isset($this->squares[$x][($y + 1)]) && $this->squares[$x][($y + 1)]->tile);

        $check_sub8 = isset($this->squares[$x][($y + 1)]) && $this->squares[$x][($y + 1)]->tileLocked == true;

        $check_1 = ($check_sub1 && $check_sub2);
        $check_2 = ($check_sub3 && $check_sub4);
        $check_3 = ($check_sub5 && $check_sub6);
        $check_4 = ($check_sub7 && $check_sub8);
        return
            $check_1 || $check_2 || $check_3 || $check_4;

    }

//    private function getOldTouch($x, $y)
//    {
//
//        $values = [];
//        $check_sub1 = $x > 0 && (isset($this->squares[($x - 1)][$y]) && $this->squares[($x - 1)][$y]->tile);
//        $check_sub2 = isset($this->squares[($x - 1)][$y]) && $this->squares[($x - 1)][$y]->tileLocked == true;
//        $check_1 = ($check_sub1 && $check_sub2);
//        if ($check_1) {
//            $values[] = $this->squares[($x - 1)][$y];
//        } else {
//            $values[] = null;
//        }
//
//        $check_sub3 = $x < 14 && (isset($this->squares[($x + 1)][$y]) && $this->squares[($x + 1)][$y]->tile);
//        $check_sub4 = isset($this->squares[($x + 1)][$y]) && $this->squares[($x + 1)][$y]->tileLocked == true;
//        $check_2 = ($check_sub3 && $check_sub4);
//        if ($check_2) {
//            $values[] = $this->squares[($x + 1)][$y];
//        } else {
//            $values[] = null;
//        }
//
//        $check_sub5 = $y > 0 && (isset($this->squares[$x][($y - 1)]) && $this->squares[$x][($y - 1)]->tile);
//        $check_sub6 = isset($this->squares[$x][($y - 1)]) && $this->squares[$x][($y - 1)]->tileLocked == true;
//        $check_3 = ($check_sub5 && $check_sub6);
//        if ($check_3) {
//            $values[] = $this->squares[$x][($y - 1)];
//        } else {
//            $values[] = null;
//        }
//
//        $check_sub7 = $y < 14 && (isset($this->squares[$x][($y + 1)]) && $this->squares[$x][($y + 1)]->tile);
//        $check_sub8 = isset($this->squares[$x][($y + 1)]) && $this->squares[$x][($y + 1)]->tileLocked == true;
//        $check_4 = ($check_sub7 && $check_sub8);
//        if ($check_4) {
//            $values[] = $this->squares[$x][($y + 1)];
//        } else {
//            $values[] = null;
//        }
//
//        return $values;
//    }

    private function removeEmptyCells($array)
    {
        return array_filter($array);
    }

}