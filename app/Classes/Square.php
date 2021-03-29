<?php


namespace App\Classes;


class Square
{
    public $type, $owner;
    public $x, $y;
    public $tile, $tileLocked;

    public function __construct($type, $owner)
    {
        $this->type = $type;
        $this->owner = $owner;
        $this->x = 0;
        $this->y = 0;
    }

    public function placeTile($tile, $locked = false)
    {

        if ($tile && $this->tile) {
//        throw "square already occupied: " + $this;
            return 0;
        }

        if ($tile) {
            $this->tile = $tile;
            $this->tileLocked = $locked;
            return 1;
        }
//        else {
//            $this->tile->delete();
//            $this->tileLocked->delete();
//            $this->tile = null;
//            $this->tileLocked = false;
//        }

//        triggerEvent('SquareChanged', [$this]);
    }

    public function removeTile($tile, $locked = false)
    {
        if ($tile && $this->tile == $tile) {
            $this->tile = null;
            $this->tileLocked = $locked;
            return 1;
        }
        return 0;
    }

    public function toString()
    {
        $string = 'Square type ' . $this->type . ' x: ' . $this->x;
        if ($this->y != -1) {
            $string .= '/' . $this->y;
        }
        if ($this->tile) {
            $string .= ' => ' . $this->tile;
            if ($this->tileLocked) {
                $string .= ' (Locked)';
            }
        }
        return $string;
    }

}