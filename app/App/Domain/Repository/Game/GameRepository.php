<?php

namespace App\Domain\Repository\Game;

use App\Persistence\Contracts\Database\GamePersistenceInterface;
use App\Persistence\Contracts\Database\ShotPersistenceInterface;

class GameRepository implements GameRepositoryInterface
{
    protected $gamePersistence;

    public function __construct(GamePersistenceInterface $gamePersistence, ShotPersistenceInterface $shotPersistence)
    {
        $this->gamePersistence = $gamePersistence;
        $this->shotPersistence = $shotPersistence;
    }

    public function checkShot($gameId, $shot)
    {
        $result = '';

        if ($this->shotPersistence->shotExist($gameId, $shot)) {
            return false;
        }

        $gameData = $this->gamePersistence->findById($gameId);
        $allHitShots = $this->shotPersistence->findAllHitShots($gameId);
        $coordinatesContainer = explode('|', $gameData['coordinates']);

        $result = array_search($shot, $coordinatesContainer);
        $result = ($result === false) ? 'miss' : 'hit';
        $this->shotPersistence->save($gameId, $shot, $result);

        if ($result == 'hit' && (count($allHitShots) + 1 === count($coordinatesContainer))) {
            $result = $this->shotPersistence->getCountShots($gameId);
        }

        return $result;
    }

    public function getGameData($gameId)
    {
        return $this->shotPersistence->getGameShots($gameId);
    }

    public function saveNewGame($shipsContainer)
    {
        $coordinatesString = '';

        foreach ($shipsContainer as $ship) {
            $coordinates = $ship->getCoordinates();
            foreach ($coordinates as $coord) {
                $coordinatesString .= implode(',', $coord) . '|';
            }
        }

        $coordinatesString = rtrim($coordinatesString, '|');

        return $this->gamePersistence->save($coordinatesString);
    }
}
