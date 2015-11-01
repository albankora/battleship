<?php

namespace App\Domain\Logic;

use App\Domain\Factories\ShipFactory;

class GameLogic
{
    protected $shipFactory;

    public function __construct(ShipFactory $shipFactory)
    {
        $this->shipFactory = $shipFactory;
    }

    protected $shipsDefaults = [
        'battleship' => 5,
        'destroyer_one' => 4,
        'destroyer_two' => 4
    ];

    protected $grid = [
        'row' => 10,
        'col' => 10,
    ];

    protected $shipsContainer = [];

    public function init()
    {
        foreach ($this->shipsDefaults as $name => $size) {
            $this->shipsContainer[] = $this->shipFactory->make($name, $size);
        }

        return $this->setUpShipCoordinates();
    }

    protected function setUpShipCoordinates()
    {
        $shipCoordinated = array();
        foreach ($this->shipsContainer as $ship) {
            $loop = true;
            while ($loop) {
                $coordinates = $this->generateCoordinates($ship->getLength(), $this->grid['col'], $this->grid['row']);
                if (count($shipCoordinated) == 0) {
                    $ship->setCoordinates($coordinates);
                    $shipCoordinated[] = $ship;
                    $loop = false;
                } else {
                    $overlaps = $this->shipOverlaps($shipCoordinated, $coordinates);
                    if ($overlaps == false) {
                        $ship->setCoordinates($coordinates);
                        $shipCoordinated[] = $ship;
                        $loop = false;
                    }
                }
            }
        }

        return $shipCoordinated;
    }

    protected function generateCoordinates($length, $col, $row)
    {
        $isHorizontal = (rand(0, 1));
        $start = ($isHorizontal)
            ? array(rand(1, $col - $length), rand(1, $row))
            : array(rand(1, $col), rand(1, $row - $length));

        $coordinates = array($start);
        $counter = 1;
        while ($counter < $length) {
            if ($isHorizontal) {
                $coordinates[] = array($start[0] + $counter, $start[1]);
            } else {
                $coordinates[] = array($start[0], $start[1] + $counter);
            }
            $counter++;
        }
        return $coordinates;
    }

    protected function shipOverlaps($shipContainer, $newCoordinates)
    {
        foreach ($shipContainer as $ship) {
            $overlaps = $this->overlapsWith($newCoordinates, $ship->getCoordinates());
            if ($overlaps) {
                return true;
            }
        }
        return false;
    }

    protected function overlapsWith($newCoordinates, $shipCoordinates)
    {
        foreach ($newCoordinates as $newCoordinate) {
            if ($this->overlaps($shipCoordinates, $newCoordinate)) {
                return true;
            }
        }
        return false;
    }

    protected function overlaps($shipCoordinates, $newCoordinate)
    {
        foreach ($shipCoordinates as $shipCoordinate) {
            if ($shipCoordinate === $newCoordinate) {
                return true;
            }
        }
        return false;
    }
}