<?php


namespace App\Classes;


class CalculateMove
{

    public $topLeftX = 0;
    public $topLeftY = 0;
    public $tile = 0;
    public $squares;
    public $move;

    public function __construct($input)
    {

        $squares = (array)$input;
        $this->squares = $squares;
        $this->move = (object)['words' => [], 'score' => [], 'allTilesBonus' => null, 'tilesPlaced' => null];
        $this->init($squares);
    }

    public function init($squares): array
    {
        // Check that the start field is occupied
        if (!$squares[7][7]->tile) return ['error' => "start field must be used"];
        // Determine that the placement of the Tile(s) is legal

        // Find top-leftmost placed tile

        for ($y = 0; !$this->tile && $y < 15; $y++) {
            for ($x = 0; !$this->tile && $x < 15; $x++) {
                if ($squares[$x][$y]->tile && !$squares[$x][$y]->tileLocked) {
                    $tile = $squares[$x][$y]->tile;
                    $this->topLeftX = $x;
                    $this->topLeftY = $y;
                }
            }
        }
        if (!$this->tile) {
            return ['error' => "no new tile found"];
        }

        // Remember which newly placed tile positions are legal
        $first = new MakeBoardArray();
        $legalPlacements = (array)$first;
        $legalPlacements[$this->topLeftX][$this->topLeftY] = true;


        $isTouchingOld = $this->touchingOld($this->topLeftX, $this->topLeftY);
        $horizontal = false;
        for ($x = $this->topLeftX + 1; $x < 15; $x++) {
            if (!$this->squares[$x][$this->topLeftY]->tile) {
                break;
            } else if (!$this->squares[$x][$this->topLeftY]->tileLocked) {
                $legalPlacements[$x][$this->topLeftY] = true;
                $horizontal = true;
                $isTouchingOld = $isTouchingOld || $this->touchingOld($x, $this->topLeftY);
            }
        }

        if (!$horizontal) {
            for ($y = $this->topLeftY + 1; $y < 15; $y++) {
                if (!$this->squares[$this->topLeftX][$y]->tile) {
                    break;
                } else if (!$this->squares[$this->topLeftX][$y]->tileLocked) {
                    $legalPlacements[$this->topLeftX][$y] = true;
                    $isTouchingOld = $isTouchingOld || $this->touchingOld($this->topLeftX, $y);
                }
            }
        }

        if (!$isTouchingOld && !$legalPlacements[7][7]) {
            return [
                'error' =>
                    'not touching old tile ' . $this->topLeftX . '/' . $this->topLeftY];
        }

        // Check whether there are any unconnected other placements, count total tiles on board
        $totalTiles = 0;
        for ($x = 0; $x < 15; $x++) {
            for ($y = 0; $y < 15; $y++) {
                $square = $this->squares[$x][$y];
                if ($square->tile) {
                    $totalTiles++;
                    if (!$square->tileLocked && !$legalPlacements[$x][$y]) {
                        return [
                            'error' =>
                                'unconnected placement'];
                    }
                }
            }
        }

        if ($totalTiles == 1) {
            return [
                'error' =>
                    'first word must consist of at least two letters'];
        }


        $this->move->score = $this->horizontalWordScores($squares);
        // Create rotated version of the board to calculate vertical word scores->
        $second = new MakeBoardArray();
        $rotatedSquares = (array)$second;
        for ($x = 0; $x < 15; $x++) {
            for ($y = 0; $y < 15; $y++) {
                $rotatedSquares[$x][$y] = $squares[$y][$x];
            }
        }
        $this->move->score += $this->horizontalWordScores($rotatedSquares);

        // Collect and count tiles placed->
        $tilesPlaced = [];
        for ($x = 0; $x < 15; $x++) {
            for ($y = 0; $y < 15; $y++) {
                $square = $squares[$x][$y];
                if ($square->tile && !$square->tileLocked) {
                    $tilesPlaced->push(['letter' => $square->tile->letter,
                        'x' => $x,
                        'y' => $y,
                        'blank' => $square->tile->isBlank()]);
                }
            }
        }
        if (count($tilesPlaced) == 7) {
            $this->move->score += 50;
            $this->move->allTilesBonus = true;
        }
        $this->move->tilesPlaced = $tilesPlaced;

        return $this->move->toArray();
    }

    // The move was legal, calculate scores
    public function horizontalWordScores($squares): int
    {
        $score = 0;
        for ($y = 0; $y < 15; $y++) {
            for ($x = 0; $x < 14; $x++) {
                if ($squares[$x][$y]->tile && $squares[$x + 1][$y]->tile) {
                    $wordScore = 0;
                    $letters = '';
                    $wordMultiplier = 1;
                    $isNewWord = false;
                    for (; $x < 15 && $squares[$x][$y]->tile; $x++) {
                        $square = $squares[$x][$y];
                        $letterScore = $square->tile->score;
                        $isNewWord = $isNewWord || !$square->tileLocked;
                        if (!$square->tileLocked) {
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
                        $wordScore += $letterScore;
                        $letters += $square->tile->letter;
                    }
                    $wordScore *= $wordMultiplier;
                    if ($isNewWord) {
                        $this->move->words[] = ['word' => $letters, 'score' => $wordScore];
                        $score += $wordScore;
                    }
                }
            }
        }
        return $score;
    }

    public function touchingOld($x, $y)
    {
        return
            ($x > 0 && $this->squares[$x - 1][$y]->tile && $this->squares[$x - 1][$y]->tileLocked) || ($x < 14 && $this->squares[$x + 1][$y]->tile && $this->squares[$x + 1][$y]->tileLocked)
            || ($y > 0 && $this->squares[$x][$y - 1]->tile && $this->squares[$x][$y - 1]->tileLocked)
            || ($y < 14 && $this->squares[$x][$y + 1]->tile && $this->squares[$x][$y + 1]->tileLocked);

    }
}