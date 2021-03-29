<?php


namespace App\Classes;


class MakeBoardArray
{
    public array $array;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $retval = array(15);
        for ($x = 0; $x < 15; $x++) {
            $retval[$x] = array(15);
        }
        return $this->array = $retval;
    }

    public function __toArray()
    {
        return (array) $this->array;
    }
}