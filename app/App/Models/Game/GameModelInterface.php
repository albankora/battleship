<?php
/**
 * Created by PhpStorm.
 * User: nikos
 * Date: 10/29/2015
 * Time: 6:59 PM
 */

namespace App\Models\Game;

interface GameModelInterface
{
    public function shot($square);

    public function getGameData();

    public function newGame();
}