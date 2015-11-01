<?php

namespace App\Persistence\Database;

use App\Persistence\Contracts\Database\GamePersistenceInterface;

class GamePersistence extends AbstractPersistence implements GamePersistenceInterface
{
    protected $table = 'games';

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function findById($id)
    {
        $this->db->query('SELECT * FROM games WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function save($coordinates)
    {
        $this->db->query("INSERT INTO $this->table (coordinates) VALUES (:coordinates)");
        $this->db->bind(':coordinates', $coordinates);
        $this->db->execute();

        return $this->db->lastInsertId();
    }
}
