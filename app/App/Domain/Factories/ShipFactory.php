<?php

namespace App\Domain\Factories;

use App\Domain\Entity\Ship;

class ShipFactory
{
    public function make($name, $length)
    {
        $ship = new Ship();
        $ship->setName($name);
        $ship->setLength($length);
        return $ship;
    }
}