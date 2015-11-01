<?php

namespace App\Persistence\Database;

class AbstractPersistence
{
    /**
     * @var \Libs\DB
     */
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}