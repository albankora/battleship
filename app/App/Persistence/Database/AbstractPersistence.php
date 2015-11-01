<?php

namespace App\Persistence\Database;

class AbstractPersistence
{
    /**
     * @var \Core\DB
     */
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}