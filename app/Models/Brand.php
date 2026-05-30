<?php

class Brand
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM brands WHERE status='active' ORDER BY name ASC");
        return $stmt->fetchAll();
    }
}