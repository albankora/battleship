<?php

namespace App\Persistence\Database;

use App\Persistence\Contracts\Database\ShotPersistenceInterface;

class ShotPersistence extends AbstractPersistence implements ShotPersistenceInterface
{
    protected $table = 'shots';

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function findAllHitShots($gameId)
    {
        $this->db->query("SELECT * FROM $this->table WHERE game_id = :game_id AND result = :result");
        $this->db->bind(':game_id', $gameId);
        $this->db->bind(':result', 'hit');
        return $this->db->resultSet();
    }

    public function shotExist($gameId, $shot)
    {
        $this->db->query("SELECT * FROM $this->table WHERE game_id = :game_id AND shot = :shot");
        $this->db->bind(':game_id', $gameId);
        $this->db->bind(':shot', $shot);
        $this->db->resultSet();
        return $this->db->rowCount();
    }

    public function save($gameId, $shot, $result)
    {
        $this->db->query("INSERT INTO $this->table (game_id, shot, result) VALUES (:game_id, :shot, :result)");
        $this->db->bind(':game_id', $gameId);
        $this->db->bind(':shot', $shot);
        $this->db->bind(':result', $result);
        $this->db->execute();

        return $this->db->lastInsertId();
    }

    public function getGameShots($gameId)
    {
        $this->db->query("SELECT * FROM $this->table WHERE game_id = :game_id");
        $this->db->bind(':game_id', $gameId);
        $data['count'] = $this->db->rowCount();
        $data['data'] = $this->db->resultSet();

        return $data;
    }

    public function getCountShots($gameId)
    {
        $this->db->query("SELECT * FROM $this->table WHERE game_id = :game_id");
        $this->db->bind(':game_id', $gameId);
        $this->db->resultSet();
        return $this->db->rowCount();
    }
}
