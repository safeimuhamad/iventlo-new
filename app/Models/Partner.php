<?php

class Partner
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT * 
            FROM partners 
            WHERE status = 'active' 
            ORDER BY partner_name ASC
        ");

        return $stmt->fetchAll();
    }
}