<?php


namespace App\Classes;


class Bag
{
    public $contents;

    public function __construct($contents)
    {
//    $this->contents = $contents ? explode(' ',$contents->slice() : [];
    }

    public function add($element)
    {
        $this->contents->push($element);
    }

    public function remove($element)
    {
        $index = $this->contents->indexOf($element);
        if ($index != -1) {
            return $this->contents->splice($index, 1)[0];
        }
    }

    public function contains($element)
    {
        return $this->contents->indexOf($element) != -1;
    }
}