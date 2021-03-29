<?php

namespace App\Traits;


use App\Classes\Board;
use App\Classes\CalculateMove;
use App\Classes\Tile;
use App\Models\Game;
use App\Models\Lettre;

trait BoardTraits
{

    /**
     * @return Board
     */
    private function get_board(): Board
    {
        return new Board();
    }

    private function get_board_pieces($game)
    {
        return \App\Models\Board::where('game_id', $game->id)->get();
    }


    public function calculate_move($squares)
    {
        return new CalculateMove($squares);
    }

    public function load_server_board($game)
    {
        $prev_board_pieces = $this->get_board_pieces($game);
        // check if word touches old words
        if ($prev_board_pieces) {
            $board = $this->populate_pieces_to_board($prev_board_pieces);
        } else {
            $board = $this->get_board();
        }

        return $board;
    }

    public function save_board_to_server($game, $board)
    {
        if ($board->squares) {
            foreach ($board->squares as $square) {
                $this->store_game_square($game, $square);
            }
        }
    }

    private function store_game_square($game, $square)
    {
        return \App\Models\Board::where('game_id', $game->id)->updateOrCreate($square);
    }


    public function place_user_words($words, $direction, $grid, $board)
    {
//       $board = $this->get_board();
        $splitted = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $grid);
        // calculating the position of the first word given
        $y = (int)$board->get_letter_grid_position($splitted[0]);
        $x = (int)$splitted[1];
        // Note: the placement on the board starts from origin 0
        return $this->word_placer($words, $x - 1, $y-1, $direction, $board);
    }

    private function word_placer($words, $x, $y, $direction, $board)
    {
        $a_word = str_split($words);
        $placed_word_count = 0;
        // check if word will not exceed board walls
        $check_word = $board->word_within_board($x, $y, $direction, $a_word);
        if ($check_word == true) {
            foreach ($a_word as $iValue) {
                $placement = $iValue;
                $letter = Lettre::where('lettre', $placement)->first();
                $valeur = $letter->valeur;

                if ($direction == 'v') {
                    $placed_word_count += $board->squares[$x++][$y]->placeTile(new Tile($placement, $valeur), true);
                } else {
                    $placed_word_count += $board->squares[$x][$y++]->placeTile(new Tile($placement, $valeur), true);
                }
            }
            if ($placed_word_count != count($a_word)) {
                // Not all words where stored
                foreach ($a_word as $iValue) {
                    $placement = $iValue;
                    $letter = Lettre::where('lettre', $placement)->first();
                    $valeur = $letter->valeur;
                    if ($direction == 'v') {
                        $board->squares[$x++][$y]->removeTile(new Tile($placement, $valeur), true);
                    } else {
                        $board->squares[$x][$y++]->removeTile(new Tile($placement, $valeur), true);
                    }

                }
                return 'cell occupied';
            }
            return $board;
        }
        return 'invalid';
    }

    private function populate_pieces_to_board($pieces)
    {
        $new_board = $this->get_board();
        foreach ($pieces as $item) {
            if (isset($item->type)) $new_board->squares[$item->x][$item->y]->type = $item->type;
            if ($item->owner) $new_board->squares[$item->x][$item->y]->owner = $item->owner;
            $new_board->squares[$item->x][$item->y]->tile = $item->tile;
            $new_board->squares[$item->x][$item->y]->tileLocked = $item->tileLocked;
        }
        return $new_board;
    }


    private function touchingOld($x, $y)
    {
        return
            ($x > 0 && $this->squares[$x - 1][$y]->tile && $this->squares[$x - 1][$y]->tileLocked) || ($x < 14 && $this->squares[$x + 1][$y]->tile && $this->squares[$x + 1][$y]->tileLocked)
            || ($y > 0 && $this->squares[$x][$y - 1]->tile && $this->squares[$x][$y - 1]->tileLocked)
            || ($y < 14 && $this->squares[$x][$y + 1]->tile && $this->squares[$x][$y + 1]->tileLocked);

    }
}