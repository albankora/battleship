<?php

namespace App\Domain\Repository\Game;

interface GameRepositoryInterface
{
    public function checkShot($gameId, $shot);

    public function saveNewGame($shipContainer);

    public function getGameData($gameId);
}