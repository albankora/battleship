<?php

namespace App\Persistence\Contracts\Database;

interface ShotPersistenceInterface
{
    public function findAllHitShots($gameId);

    public function shotExist($gameId, $shot);

    public function save($gameId, $shot, $result);

    public function getGameShots($gameId);

    public function getCountShots($gameId);
}