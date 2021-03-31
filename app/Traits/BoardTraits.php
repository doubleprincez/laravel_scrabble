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


    public function calculate_move($squares, $direction)
    {
        return new CalculateMove($squares, $direction);
    }

    public function load_server_board($game)
    {
        $prev_board_records = $this->get_board_pieces($game);

        // check if word touches old words
        if ($prev_board_records) {
            $board = $this->populate_pieces_to_board($prev_board_records);
        } else {
            $board = $this->get_board();
        }
        return $board;
    }

    public function save_board_to_server($game, $board_record)
    {

        if ($board_record) {

            \App\Models\Board::updateOrCreate(['game_id' => $game->id, 'user_id' => auth()->id(), 'words' => json_encode($board_record->words), 'score' => (int)$board_record->words, 'allTilesBonus' => json_encode($board_record->allTilesBonus), 'tilesPlaced' => json_encode($board_record->tilesPlaced)]);

        }
    }


    public function place_user_words($words, $direction, $grid, $board)
    {

//       $board = $this->get_board();
        $splitted = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $grid);
        // calculating the position of the first word given
        $y = (int)$board->get_letter_grid_position($splitted[0]);

        $x = (int)$splitted[1];
        // Note: the placement on the board starts from origin 0
        return $this->word_placer($words, $x - 1, $y - 1, $direction, $board);
    }

    private function word_placer($words, $x, $y, $direction, $board)
    {

        $a_word = str_split($words);
        $placed_word_count = 0;
        // check if word will not exceed board walls
        $check_word = $board->word_within_board($x, $y, $direction, $a_word);
        if ($check_word == true) {
            foreach ($a_word as $k => $iValue) {
                $placement = $iValue;
                $letter = Lettre::where('lettre', $placement)->first();
                $valeur = $letter->valeur;

                if ($direction == 'v') {
                    $placed_word_count += $board->squares[$y][$x + $k]->placeTile(new Tile($placement, $valeur), false);
                } else {
                    $placed_word_count += $board->squares[$y + $k][$x]->placeTile(new Tile($placement, $valeur), false);
                }
            }

            if ((int)$placed_word_count !== count($a_word)) {
                return 'cell occupied';
            }
            return $board;
        }
        return 'invalid';
    }

    private function populate_pieces_to_board($board_records)
    {
        $new_board = $this->get_board();
        foreach ($board_records as $each) {
            if ($each->tilesPlaced) {
                foreach (json_decode($each->tilesPlaced) as $item) {
                    if (isset($item->type)) $new_board->squares[$item->y][$item->x]->type = $item->type;

                    $new_board->squares[$item->y][$item->x]->tile = new Tile($item->letter, $item->score);
                    $new_board->squares[$item->y][$item->x]->tileLocked = $item->tileLocked;
                }
            }
        }
        return $new_board;
    }


}